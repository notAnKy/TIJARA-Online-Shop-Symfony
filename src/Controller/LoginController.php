<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Internaute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/', name: 'login', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $error = false;

        if ($request->isMethod('POST')) {
            $login = $request->request->get('login');
            $password = $request->request->get('password');

            $user = $this->entityManager->getRepository(Internaute::class)->findOneBy(['login' => $login, 'password' => $password]);

            if ($user) {
                $this->storeUserDataInSession($user);

                if ($user->getRole() === 'admin') {
                    return $this->redirectToRoute('adminpage');
                } elseif ($user->getRole() === 'employee') {
                    return $this->redirectToRoute('app_employeepage');
                }
            } else {
                $error = true;
            }
        }

        return $this->render('login/index.html.twig', [
            'error' => $error,
        ]);
    }

    public function logout(SessionInterface $session): Response
    {
        // Invalidate the session
        $session->invalidate();

        // Redirect to the login page
        return $this->redirectToRoute('login');
    }

    private function storeUserDataInSession(Internaute $user): void
    {
        $this->session->set('user_id', $user->getId());
        $this->session->set('user_login', $user->getLogin());
        $this->session->set('user_role', $user->getRole());
    }
}





