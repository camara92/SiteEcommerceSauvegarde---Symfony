<?php

namespace App\Controller;


use App\Filtrer\Filter;
use App\Entity\Product;
use App\Form\SearchType;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{ private $entitymanager;
    public function __construct(EntityManagerInterface $entityManager){
    $this->entitymanager= $entityManager;
    }
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        $products = $this->entitymanager->getRepository(Product::class)->findAll();
        $filter = new Filter();
        $form = $this->createForm(SearchType::class, $filter);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$filter= $form->getData();
            //dd($filter);
            $products = $this->entitymanager->getRepository(Product::class)->findWithFilter($filter);

        }else{
            $products = $this->entitymanager->getRepository(Product::class)->findAll();
        }
        return $this->render('product/index.html.twig', [
            'products'=> $products,
            'form'=>$form->createView()

        ]);
    }
    /**
    * @Route("/produit/{name}", name="product")
    */
    public function show($name): Response
    {
        $product = $this->entitymanager->getRepository(Product::class)->findOneByName(['name' => $name]);
        if (!$product) {
            return $this->redirectToRoute('products');
        }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
    }

