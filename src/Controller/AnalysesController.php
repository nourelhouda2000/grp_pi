<?php

namespace App\Controller;

use App\Repository\AnalysesRepository;
use App\Form\AnalysesType;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Analyses;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AnalysesController extends AbstractController
{
    #[Route('/analyses', name: 'analyses')]
    public function index(): Response
    {
        return $this->render('FrontEnd/basef.html.twig', [
            'controller_name' => 'AnalysesController',
        ]);
    }
    #[Route('/fetchE', name: 'fetchE')]
    public function fetchE(AnalysesRepository $repo) : Response 
    {
        $result=$repo->findAll();
        return $this->render('BackEnd/analyses/afficher.html.twig',['response' =>$result]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(AnalysesRepository $repo) : Response 
    {
        $result=$repo->findAll();
        return $this->render('FrontEnd/analyses/afficherF.html.twig',['response' =>$result]);
    }

    #[Route('/addF', name: 'addF')]
    public function ajouterf(AnalysesRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Analyses();
   $form=$this->createForm(AnalysesType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted()&& $form->isValid())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('fetch');
       }
   return $this->render('FrontEnd/analyses/index.html.twig',['form'=>$form->createView()]);



  }
  #[Route('/ajouter', name: 'ajouter')]
    public function ajouter(AnalysesRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Analyses();
   $form=$this->createForm(AnalysesType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted() && $form->isValid())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('index');
       }
   return $this->render('BackEnd/analyses/index.html.twig',['f'=>$form->createView()]);


  }
  #[Route('/index', name: 'index')]
  public function end(): Response
  {
      return $this->render('BackEnd/baseEnd.html.twig', [
          'controller_name' => 'AnalysesController',
      ]);
  }
  #[Route('/updateE/{id}', name: 'updateE')]
  public function updateAnalyses(int $id, ManagerRegistry $mr, Request $req, AnalysesRepository $repo): Response
  {
      $s = $repo->find($id); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('Analyses not found.');
      }
  
      $form = $this->createForm(AnalysesType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('fetchE'); // Redirect to your list of students
      }
  
      return $this->render('BackEnd/analyses/index.html.twig',['f'=>$form->createView()]);

  }
#[Route('/removeE/{id}', name: 'removeE')]
public function remove(AnalysesRepository $repo, $id, ManagerRegistry $mr):Response
{
  $analyses= $repo->find($id);
  $em = $mr->getManager();
  $em->remove($analyses);
  $em->flush();

  return $this ->redirectToRoute('fetchE');
}
#[Route('/update/{id}', name: 'update')]
  public function update(int $id, ManagerRegistry $mr, Request $req, AnalysesRepository $repo): Response
  {
      $s = $repo->find($id); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('Analyses not found.');
      }
  
      $form = $this->createForm(AnalysesType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('fetch'); // Redirect to your list of students
      }
  
      return $this->render('FrontEnd/analyses/index.html.twig',['form'=>$form->createView()]);

  }
#[Route('/remove/{id}', name: 'remove')]
public function removeF(AnalysesRepository $repo, $id, ManagerRegistry $mr):Response
{
  $analyses= $repo->find($id);
  $em = $mr->getManager();
  $em->remove($analyses);
  $em->flush();

  return $this ->redirectToRoute('fetch');
}
}





