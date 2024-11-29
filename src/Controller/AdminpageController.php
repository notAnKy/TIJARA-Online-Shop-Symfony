<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AdminpageController extends AbstractController
{
    private $doctrine;
    

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/adminpage', name: 'app_adminpage')]
    public function index(Request $request,SessionInterface $session): Response
    {
        $session = $request->getSession();
        $role = $session->get('user_role');

        // Check if the user's role is 'admin'
        if ($role !== 'admin') {
            // Redirect to another page (e.g., login page)
            return $this->redirectToRoute('login');
        }

        $loginName = $session->get('user_login');
        $article = new Article();
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article added successfully.');

            return $this->redirectToRoute('app_adminpage');
        }

        // Fetch the last two articles
        $articles = $this->doctrine->getRepository(Article::class)->findBy([], ['id' => 'DESC'], 2);

        return $this->render('adminpage/index.html.twig', [
            'articleForm' => $articleForm->createView(),
            'lastTwoArticles' => $articles,
            'login_name' => $loginName,
        ]);
    }

    #[Route('/modify-article/{id}', name: 'modify_article')]
    public function modifyArticle(Request $request, int $id, SessionInterface $session): Response
    {
        $session = $request->getSession();
        $role = $session->get('user_role');

        // Check if the user's role is 'admin'
        if ($role !== 'admin') {
            // Redirect to another page (e.g., login page)
            return $this->redirectToRoute('login');
        }

        $loginName = $session->get('user_login');
        $entityManager = $this->doctrine->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Article updated successfully.');
            return $this->redirectToRoute('app_adminpage');
        }

        // Fetch the last two articles
        $lastTwoArticles = $entityManager->getRepository(Article::class)->findBy([], ['id' => 'DESC'], 2);

        return $this->render('adminpage/index.html.twig', [
            'articleForm' => $articleForm->createView(),
            'lastTwoArticles' => $lastTwoArticles,
            'login_name' => $loginName,
        ]);
    }


    #[Route('/delete-article/{id}', name: 'delete_article')]
    public function deleteArticle(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();
        $articleId = $request->attributes->get('id');
        $article = $entityManager->getRepository(Article::class)->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article deleted successfully.');

        return $this->redirectToRoute('app_adminpage');
    }
}

