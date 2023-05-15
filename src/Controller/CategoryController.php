<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category): Response
    {
        $products = $category->getProducts();
        return $this->render('categories/list.html.twig',
        compact('category','products'));
        // return $this->render('categories/list.html.twig',[
        //     'category' =>$category,
        //     'products' =>$products,
        // ]);
    }
}