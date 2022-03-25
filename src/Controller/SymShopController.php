<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SymShopController extends AbstractController
{
    // #[Route('/', name: 'home')]
    // public function index(): Response
    // {
    //     return $this->render('sym_shop/index.html.twig', [
    //         'controller_name' => 'SymShopController',
    //     ]);
    // }
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        // dd($products); //dump and die
        return $this->render('sym_shop/index.html.twig', [
            'products' => $products,
            'controller_name' => 'SymShopController',

        ]);
    }
}
