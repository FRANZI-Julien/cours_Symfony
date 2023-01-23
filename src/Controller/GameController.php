<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $repo): Response
    {   
        $games = $repo->findAll();
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games'=> $games
        ]);
    }
    #[Route('/game/new', name:'app_add_game')]

    public function add(Request $resquest, ManagerRegistry $doctrine, SluggerInterface $slugger): Response {
        $em = $doctrine->getManager();
        $newGame = new Game;

        $form =$this->createForm(GameType::class, $newGame)
        ->add('Enregistrer', SubmitType::class);
        
        $form->handleRequest($resquest);
        if($form->isSubmitted() && $form->isValid()){
             /** @var UploadedFile $pictureFile */
             $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $newGame->setPicture($newFilename);
            }

            $newGame = $form->getData();
            $em->persist($newGame);
            $em->flush();
        }

        return $this->render('game/add.html.twig',[
            'controller_name'=>'GameController',
            'form'=>$form
        ]);

    }
#[Route('/game/{id}' , name:'app_show_game')]
    public function show(GameRepository $repo, $id):Response{
        $game = $repo->find($id);

        return $this->render('game/show.html.twig',[
            'game'=>$game
        ]);
    }
}
