<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Recette;
use App\Form\RecettesType;
class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette')]
    public function index(): Response
    {
        return $this->render('recette/index.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
    }
    #[Route('/afficherRecette', name: 'afficherRecette')]
    public function afficherRecette(RecetteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('recette/afficherr.html.twig', [
            'response' => $resul,
        ]);
    }
    #[Route('/afficherRecettefront', name: 'afficherRecettefront')]
    public function afficherRecettefront(RecetteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('recette/afficher2.html.twig', [
            'response' => $resul,
        ]);
    }
    #[Route('/ajouterRecette', name: 'ajouterRecette')]
      public function ajouterRecette(Request $request, ManagerRegistry $mr): Response
      {
          
          $Nt = new Recette();
          $form = $this->createForm(RecettesType::class, $Nt);
  
          $form->handleRequest($request);
  
          if ($form->isSubmitted() && $form->isValid()) {
              
          $em=$mr->getManager();
          $em->persist($Nt);
          $em->flush();
    
        
       return $this->redirectToRoute('afficherRecette');
      }
      return $this->render('recette/ajoutr.html.twig', [
          'form' => $form->createView(),
      ]);
      }
      #[Route('/supprimerRecette/{id}', name: 'supprimerRecette')]
      public function supprimerRecette(RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
      {
          $std= $repo->find($id);
       
          if(!$std){
              return new Response('non trouve');
          }
  
          $em=$mr->getManager();
          $em->remove($std);
          $em->flush();
    
        //return new Response('c bon supp');
      return $this->redirectToRoute('afficherRecette');
      }
  
   #[Route('/modifierRecette/{id}', name: 'modifierRecette', methods: ['GET', 'POST'])]
  public function modifierRecette(Request $request, RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
  {
      $Recettes = $repo->find($id);
  
      if (!$Recettes) {
          // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
          return new Response('introuvable');
      }
  
      $form = $this->createForm(RecettesType::class, $Recettes);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          $em->flush();
  
          return $this->redirectToRoute('afficherRecette');
      }

      return $this->render('recette/ajoutr.html.twig', [
          'form' => $form->createView(),
      ]);
  } 
  #[Route('/modifierRecettefront/{id}', name: 'modifierRecettefront', methods: ['GET', 'POST'])]
  public function modifierRecettefront(Request $request, RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
  {
      $Recettes = $repo->find($id);
  
      if (!$Recettes) {
          // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
          return new Response('introuvable');
      }
  
      $form = $this->createForm(RecettesType::class, $Recettes);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          $em->flush();
  
          return $this->redirectToRoute('afficherRecettefront');
      }

      return $this->render('recette/ajoutr2.html.twig', [
          'form' => $form->createView(),
      ]);
  } 
  }
  
  

