<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ArticleListController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/article/list', name: 'app_article_list')]
    public function index(SessionInterface $session, Request $request): Response
    {
        $role = $session->get('user_role');

        // Check if the user's role is 'admin'
        if ($role !== 'admin') {
            // Redirect to another page (e.g., login page)
            return $this->redirectToRoute('login');
        }

        $loginName = $session->get('user_login');
        $searchQuery = $request->query->get('q');

        // Query the database to get articles based on the search query
        if ($searchQuery) {
            $articles = $this->doctrine->getRepository(Article::class)->findBySearchQuery($searchQuery);
        } else {
            // If no search query provided, fetch all articles
            $articles = $this->doctrine->getRepository(Article::class)->findAll();
        }

        return $this->render('article_list/index.html.twig', [
            'controller_name' => 'ArticleListController',
            'articles' => $articles,
            'login_name' => $loginName,
        ]);
    }


    #[Route('/article-details/{id}', name: 'article_details')]
    public function details(Request $request, int $id, SessionInterface $session): Response
    {
        $role = $session->get('user_role');

        // Check if the user's role is 'admin'
        if ($role !== 'admin') {
            // Redirect to another page (e.g., login page)
            return $this->redirectToRoute('login');
        }
        $loginName = $session->get('user_login');
        $article = $this->doctrine->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Get the search query from the request
        $searchQuery = $request->query->get('q');

        // Query the database to get articles based on the search query
        if ($searchQuery) {
            $articles = $this->doctrine->getRepository(Article::class)->findBySearchQuery($searchQuery);
        } else {
            // If no search query provided, fetch all articles
            $articles = $this->doctrine->getRepository(Article::class)->findAll();
        }

        return $this->render('article_list/details.html.twig', [
            'article' => $article,
            'login_name' => $loginName,
        ]);
    }

    #[Route('/update-article/{id}', name: 'update_article')]
    public function updateArticle(Request $request, int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $name = $request->request->get('name');
        $price = $request->request->get('price');

        // Update the article with new name and price
        $article->setLibArt($name);
        $article->setPrixArt($price);

        $entityManager->flush();

        $this->addFlash('success', 'Article updated successfully.');

        return $this->redirectToRoute('article_details', ['id' => $id]);
    }
}
