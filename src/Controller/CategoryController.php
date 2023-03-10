<?php

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    #[Route('/category/new', name: 'app_add_category')]
    
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $newCat = new Category;
        $form = $this->createForm(CategoryType::class, $newCat)
        ->add('Enregistrer', SubmitType::class);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newCat = $form->getData();
            $em->persist($newCat);
            $em->flush();
        }
        

        return $this->render('category/add.html.twig', [
            'controller_name' => 'CategoryController',
            'form'=>$form
        ]);
    }
}
