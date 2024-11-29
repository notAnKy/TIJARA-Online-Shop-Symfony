<?php

namespace App\Controller;

use App\Entity\Internaute;
use App\Form\SignupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SignupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/signup', name: 'signup')]
    public function index(Request $request): Response
    {
        try {
            $internaute = new Internaute();
            $internaute->setDateInscrip(new \DateTime()); 
            $form = $this->createForm(SignupType::class, $internaute);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Set the role field
                $internaute->setRole('employee');
                
                // Handle form submission and persist the new user data
                $this->entityManager->persist($internaute);
                $this->entityManager->flush();

                // Return JSON response indicating success
                return new JsonResponse(['success' => true]);
            }

            return $this->render('signup/index.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            // Return JSON response with error message
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
