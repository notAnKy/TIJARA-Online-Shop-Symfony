<?php

namespace App\Controller;

use App\Entity\Internaute;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; // Import Request class
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\InternauteRepository;

class InternauteListController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/internaute/list', name: 'app_internaute_list')]
    public function index(Request $request, SessionInterface $session, InternauteRepository $internauteRepository): Response
    {
        $role = $session->get('user_role');

        if ($role !== 'admin') {
            return $this->redirectToRoute('login');
        }

        $loginName = $session->get('user_login');
        $searchQuery = $request->query->get('q');

        if ($searchQuery) {
            $internautes = $internauteRepository->findBySearchQuery($searchQuery);
        } else {
            $internautes = $internauteRepository->findAll();
        }

        return $this->render('internaute_list/index.html.twig', [
            'internautes' => $internautes,
            'login_name' => $loginName,
        ]);
    }

    #[Route('/internaute/panier/{id}', name: 'app_internaute_panier')]
    public function viewPanier(int $id, SessionInterface $session): Response
    {
        // $session = $request->getSession(); // Remove this line
        $role = $session->get('user_role');

        // Check if the user's role is 'admin'
        if ($role !== 'admin') {
            // Redirect to another page (e.g., login page)
            return $this->redirectToRoute('login');
        }

        $internaute = $this->doctrine->getRepository(Internaute::class)->find($id);

        if (!$internaute) {
            throw $this->createNotFoundException('Internaute not found');
        }

        $panier = $this->doctrine->getRepository(Panier::class)->findBy(['internaute' => $internaute]);

        return $this->render('internaute_list/panier.html.twig', [
            'internaute' => $internaute,
            'panier' => $panier,
        ]);
    }
    
}
