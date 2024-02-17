<?php

namespace App\Controller;

use App\Repository\SanteRepository;
use App\Form\SanteType;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Sante;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SantéController extends AbstractController
{
    #[Route('/sante', name: 'sante')]
    public function index(): Response
    {
        return $this->render('BackEnd/santé/index.html.twig', [
            'controller_name' => 'SantéController',
        ]);
    }
    #[Route('/fetchS', name: 'fetchS')]
    public function fetchS(SanteRepository $repo) : Response 
    {
        $result=$repo->findAll();
        return $this->render('BackEnd/santé/afficher.html.twig',['response' =>$result]);
    }
    
    #[Route('/fetchFS', name: 'fetchFS')]
    public function fetch(SanteRepository $repo) : Response 
    {
        $result=$repo->findAll();
        return $this->render('FrontEnd/santé/afficherF.html.twig',['response' =>$result]);
    }

    #[Route('/addFS', name: 'addFS')]
    public function ajouterf(SanteRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Sante();
   $form=$this->createForm(SanteType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted()&& $form->isValid())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('fetchFS');
       }
   return $this->render('FrontEnd/santé/index.html.twig',['form'=>$form->createView()]);



  }
  #[Route('/ajouterFS', name: 'ajouterFS')]
    public function ajouter(SanteRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Sante();
   $form=$this->createForm(SanteType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted() && $form->isValid())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('indexS');
       }
   return $this->render('BackEnd/santé/index.html.twig',['f'=>$form->createView()]);


  }
  #[Route('/indexS', name: 'indexS')]
  public function end(): Response
  {
      return $this->render('BackEnd/baseEnd.html.twig', [
          'controller_name' => 'SanteController',
      ]);
  }
  #[Route('/updateES/{id}', name: 'updateES')]
  public function updateSante(int $id, ManagerRegistry $mr, Request $req, SanteRepository $repo): Response
  {
      $s = $repo->find($id); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('Sante not found.');
      }
  
      $form = $this->createForm(SanteType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('fetchS'); // Redirect to your list of students
      }
  
      return $this->render('BackEnd/santé/index.html.twig',['f'=>$form->createView()]);

  }
#[Route('/removeES/{id}', name: 'removeES')]
public function remove(SanteRepository $repo, $id, ManagerRegistry $mr):Response
{
  $sante= $repo->find($id);
  $em = $mr->getManager();
  $em->remove($sante);
  $em->flush();

  return $this ->redirectToRoute('fetchS');
}
#[Route('/updateS/{id}', name: 'updateS')]
  public function update(int $id, ManagerRegistry $mr, Request $req, SanteRepository $repo): Response
  {
      $s = $repo->find($id); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('Sante not found.');
      }
  
      $form = $this->createForm(SanteType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('fetchFS'); // Redirect to your list of students
      }
  
      return $this->render('FrontEnd/santé/index.html.twig',['form'=>$form->createView()]);

  }
#[Route('/removeS/{id}', name: 'removeS')]
public function removeF(SanteRepository $repo, $id, ManagerRegistry $mr):Response
{
  $sante= $repo->find($id);
  $em = $mr->getManager();
  $em->remove($sante);
  $em->flush();

  return $this ->redirectToRoute('fetchFS');
}
}








