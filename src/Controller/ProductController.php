<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    //  /**
    //  * @Route("/store/contact", name="cat-contact")
    //  */
    // public function contact(): Response
    // {
    //     return $this->render('category/catContact.html.twig', [
    //         // 'controller_name' => 'BlogController',
    //     ]);
    // }

    /**
     * @Route("/product", name="products")
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        // dd($products); //dump and die
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
     /**
     * @Route("/productClient", name="productsClient")
     */
    public function indexClient(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        // dd($products); //dump and die
        return $this->render('product/indexClient.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/new",name="prod-new")
     * @Route("/product/edit/{id}",name="prod-edit")
     */
    public function addOrUpdateProduct(
        Product $product = null,
        Request $req,
        EntityManagerInterface $em
    ) {
        if (!$product) {
            $product = new Product();
        }

        $formProduct = $this->createForm(ProductType::class, $product);

        $formProduct->handleRequest($req);
        // dump($req);
        // dump($article);
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('products', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/prodForm.html.twig', [
            'formProduct' => $formProduct->createView(),
            'mode' => $product->getId() != null,
        ]);
    }
    /**
     * @Route("/product/delete/{id}",name="prod-delete")
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();
        
        return $this->redirectToRoute('products', [
            'id' => $product->getId()
        ]);
    }
    
    // /**
    //  * @Route("/category/{id}", name="cat-detail")
    //  */
    // public function artDetail(Category $category): Response
    // {
    //     // $articles = $articleRepo->findAll();
    //     // dd($article); //dump and die
    //     return $this->render('category/catDetail.html.twig', [
    //         'category' => $category,
    //     ]);
    // }
}
