<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Nutritions;
use App\Repository\NutritionsRepository;
use App\Form\NutritionsType;
use Symfony\Component\HttpFoundation\Request;

class NutritionsController extends AbstractController
{
    #[Route('/nutritions', name: 'app_nutritions')]
    
        public function index(): Response
        {
           return $this->render('nutritions/Frontend/basef.html.twig', [
        'controller_name' => 'NutritionsController',
    ]);

    
}
#[Route('/addF', name: 'addF')]
    public function addF(NutritionsRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Nutritions();
   $form=$this->createForm(NutritionsType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('fetch');
       }
   return $this->render('nutritions/Frontend/add.html.twig',['f'=>$form->createView()]);


  }
  #[Route('/afficher', name: 'afficher')]
  public function afficher(NutritionsRepository $repo): Response
  {
      $resul= $repo->findAll();
      return $this->render('nutritions/afficher.html.twig', [
          'response' => $resul,
      ]);
  }
  #[Route('/afficherfront', name: 'afficherfront')]
  public function afficherfront(NutritionsRepository $repo): Response
  {
      $resul= $repo->findAll();
      return $this->render('nutritions/afficher2.html.twig', [
          'response' => $resul,
      ]);
  }
  #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(Request $request, ManagerRegistry $mr): Response
    {
        
        $Nt = new Nutritions();
        $form = $this->createForm(NutritionsType::class, $Nt);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $em=$mr->getManager();
        $em->persist($Nt);
        $em->flush();
  
      
     return $this->redirectToRoute('afficher');
    }
    return $this->render('nutritions/ajout.html.twig', [
        'form' => $form->createView(),
    ]);
    }
    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(NutritionsRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $std= $repo->find($id);
     
        if(!$std){
            return new Response('non trouve');
        }

        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('afficher');
    }
    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(int $id, ManagerRegistry $mr, Request $req, NutritionsRepository $repo): Response
    {
        $s = $repo->find($id); // Find the student to update
    
        if (!$s) {
            throw $this->createNotFoundException('nutritions not found.');
        }
    
        $form = $this->createForm(NutritionsType::class, $s); // Use the found student for the form
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            // You don't need to persist an existing entity, just flush
            $em->flush();
    
            return $this->redirectToRoute('afficher'); // Redirect to your list of students
        }
    
        return $this->render('nutritions/ajout.html.twig',['form'=>$form->createView()]);
  
    }
}
