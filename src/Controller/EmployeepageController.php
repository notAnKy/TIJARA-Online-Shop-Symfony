<?php

// EmployeePageController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticleRepository; 
use App\Entity\Article;
use App\Entity\Internaute; 
use Doctrine\ORM\Query\Expr;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Panier;
use Doctrine\Persistence\ManagerRegistry;

class EmployeepageController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    #[Route('/employeepage', name: 'app_employeepage')]
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $loginName = $session->get('user_login');
        $role = $session->get('user_role');

        if ($role !== 'employee') { 
            return $this->redirectToRoute('login');
        }

        // Fetch all products
        $products = $articleRepository->findAll();

        // Shuffle the products array to get random products
        shuffle($products);

        // Take the first 4 products as featured products
        $featuredProducts = array_slice($products, 0, 4);

        // Fetch the latest product
        $latestProduct = $articleRepository->findOneBy([], ['id' => 'DESC']);

        // Fetch the last added products
        $lastAddedProducts = $articleRepository->findBy([], ['id' => 'DESC'], 4);

        return $this->render('employeepage/index.html.twig', [
            'login_name' => $loginName,
            'products' => $products, 
            'featuredProducts' => $featuredProducts,
            'latestProduct' => $latestProduct,
            'lastAddedProducts' => $lastAddedProducts, // Pass last added products to the template
        ]);
    }

    #[Route('/all-products', name: 'all_products')]
    public function allProducts(Request $request, SessionInterface $session, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $loginName = $session->get('user_login');
        $role = $session->get('user_role');
        
        if ($role !== 'employee') { 
            return $this->redirectToRoute('login');
        }
        
        $category = $request->query->get('category');
        $price = $request->query->get('price');
        
        // Fetch all products or filter by category and/or price if selected
        $queryBuilder = $articleRepository->createQueryBuilder('a');
        
        if ($category && $category !== 'all') {
            $queryBuilder->andWhere('a.catArt = :category')
                        ->setParameter('category', $category);
        }
        
        if ($price) {
            // Assuming you want to filter products with price less than or equal to the provided price
            $queryBuilder->andWhere('a.prixArt <= :price')
                        ->setParameter('price', $price);
        }
        
        // Paginate the results
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Doctrine Query, not results
            $request->query->getInt('page', 1), // Define the page parameter
            20 // Items per page
        );
    
        return $this->render('employeepage/all_products.html.twig', [
            'login_name' => $loginName,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/search-products', name: 'search_products')]
    public function searchProducts(Request $request, ArticleRepository $articleRepository, SessionInterface $session, PaginatorInterface $paginator): Response
    {
        $loginName = $session->get('user_login');
        $searchQuery = $request->query->get('query');

        // Query the database to get articles based on the search query
        if ($searchQuery) {
            $queryBuilder = $articleRepository->createQueryBuilder('a')
                ->where('a.libArt LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');

            // Paginate the results
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(), // Doctrine Query, not results
                $request->query->getInt('page', 1), // Define the page parameter
                20 // Items per page
            );
        } else {
            // If no search query provided, fetch all products
            $pagination = $paginator->paginate(
                $articleRepository->findAll(), // Doctrine Query, not results
                $request->query->getInt('page', 1), // Define the page parameter
                20 // Items per page
            );
        }

        return $this->render('employeepage/all_products.html.twig', [
            'pagination' => $pagination,
            'login_name' => $loginName,
        ]);
    }

    #[Route('/product/{id}', name: 'product_details')]
    public function productDetails($id, ArticleRepository $articleRepository, SessionInterface $session): Response
    {
        $loginName = $session->get('user_login');
        $role = $session->get('user_role');

        if ($role !== 'employee') { 
            return $this->redirectToRoute('login');
        }
        
        // Fetch the product by id
        $product = $articleRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Fetch other products from the same category, excluding the current product
        $relatedProducts = $articleRepository->findBy(
            ['catArt' => $product->getCatArt()], // Filter by category
            ['id' => 'DESC'], // Order by descending ID
            4 // Limit to 4 products
        );

        // Exclude the current product from the related products list
        $relatedProducts = array_filter($relatedProducts, function($relatedProduct) use ($product) {
            return $relatedProduct->getId() !== $product->getId();
        });

        return $this->render('employeepage/product_details.html.twig', [
            'product' => $product,
            'login_name' => $loginName,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    #[Route('/panier-form-page/{id}', name: 'panier_form_page')]
    public function panierFormPage($id, Request $request, SessionInterface $session): Response
    {
        $loginName = $session->get('user_login');
        $role = $session->get('user_role');

        if ($role !== 'employee') { 
            return $this->redirectToRoute('login');
        }

        // Fetch the product by ID
        $product = $this->doctrine->getRepository(Article::class)->find($id);

        // Render the panier form page with the product information
        return $this->render('employeepage/panier_form_page.html.twig', [
            'product' => $product,
            'login_name' => $loginName,
        ]);
    }

    #[Route('/handle-panier-form', name: 'handle_panier_form', methods: ['POST'])]
    public function handlePanierForm(Request $request, SessionInterface $session): Response
    {
        // Retrieve form data
        $productId = $request->request->get('product_id');
        $quantity = $request->request->get('quantity');
        $packaging = $request->request->get('packaging');
    
        // Fetch the product by ID
        $product = $this->doctrine->getRepository(Article::class)->find($productId);
    
        // Generate a random number for 'num_panier'
        $numPanier = mt_rand(100000, 999999); // Generate a 6-digit random number
    
        // Fetch the logged-in user's ID
        $userId = $session->get('user_id');
    
        // Fetch the user entity by ID
        $user = $this->doctrine->getRepository(Internaute::class)->find($userId);
    
        // Create a new Panier entity and set its properties
        $panier = new Panier();
        $panier->setNumPanier($numPanier);
        $panier->setArticle($product);
        $panier->setQuantite($quantity);
        $panier->setEmballage($packaging);
        $panier->setInternaute($user); // Set the logged-in user
    
        // Persist the Panier entity to the database
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();
    
        // Redirect back to the product details page
        return $this->redirectToRoute('product_details', ['id' => $productId]);
    }
    

    
    #[Route('/cart', name: 'cart')]
    public function cart(SessionInterface $session): Response
    {
        $loginName = $session->get('user_login');
        $role = $session->get('user_role');

        if ($role !== 'employee') { 
            return $this->redirectToRoute('login');
        }


        $userId = $session->get('user_id');
    
        // Fetch cart items based on the user's ID
        $cartItems = $this->doctrine->getRepository(Panier::class)->findBy(['internaute' => $userId]);
    
        // Calculate total price
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $total += $cartItem->getQuantite() * $cartItem->getArticle()->getPrixArt();
        }
    
        return $this->render('employeepage/cart.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
            'login_name' => $loginName,
        ]);
    }
    

    
}
