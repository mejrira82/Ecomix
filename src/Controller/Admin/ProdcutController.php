<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use \App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin_products_')]
class ProdcutController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products=$productsRepository->findAll();
        return $this->render('admin/products/index.html.twig',compact('products'));
    }
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = new Products();
        $productForm = $this->createForm(ProductsFormType::class, $product);
        $productForm->handleRequest($request);
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $images = $productForm->get('images')->getData();
            foreach ($images as $image) {
                $folder = 'products';
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product added succefuly');
            return $this->redirectToRoute('admin_products_index');
        }
        return $this->renderForm('admin/products/add.html.twig', compact('productForm'));
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger,PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        $prix = $product->getPrice() / 100;
        $product->setPrice($prix);
        $productForm = $this->createForm(ProductsFormType::class, $product);
        $productForm->handleRequest($request);
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $images = $productForm->get('images')->getData();
            foreach ($images as $image) {
                $folder = 'products';
                $fichier = $pictureService->add($image, $folder, 500, 500);
                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit updated succefuly');
            return $this->redirectToRoute('admin_products_index');
        }
        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
        //return $this->renderForm('admin/products/edit.html.twig', compact('productForm'));
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }



    #[Route('/delete/image/{id}', name: 'delete_image',methods:['DELETE'])]
    public function deleteImage(Images $image,Request $request,EntityManagerInterface $em,PictureService $picture): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $name = $image->getName();
            if ($picture->delete($name, 'products', 500, 500)) {
                $em->remove($image);
                $em->flush();
                return new JsonResponse(['success' => 'Suppresion successfuly'], 400);
            }
            return new JsonResponse(['error' => 'Suppresion error'], 400);
        }
        return new JsonResponse(['error' => 'Invalid token'], 400);
    }
}