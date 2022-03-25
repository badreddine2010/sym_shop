<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
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
     * @Route("/category", name="categories")
     */
    public function index(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();
        // dd($articles); //dump and die
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/new",name="cat-new")
     * @Route("/category/edit/{id}",name="cat-edit")
     */
    public function addOrUpdateCategory(
        Category $category = null,
        Request $req,
        EntityManagerInterface $em
    ) {
        if (!$category) {
            $category = new Category();
        }

        $formCategory = $this->createForm(CategoryType::class, $category);

        $formCategory->handleRequest($req);
        // dump($req);
        // dump($article);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('categories', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('category/catForm.html.twig', [
            'formCategory' => $formCategory->createView(),
            'mode' => $category->getId() != null,
        ]);
    }
    /**
     * @Route("/category/delete/{id}",name="cat-delete")
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($category);
        $entityManager->flush();
        
        return $this->redirectToRoute('categories', [
            'id' => $category->getId()
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
