<?php

namespace App\Controller;
use App\Repository\ReclamationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\ReclamtionsType;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reclamations;

use Symfony\Component\Validator\Validator\ValidatorInterface;




use Symfony\Component\HttpFoundation\Request;

class ReclamationsController extends AbstractController
{
    #[Route('/reclamations', name: 'reclamations')]
    public function index(): Response
    {
       return $this->render('reclamations/FrontEnd/basef.html.twig', [
    'controller_name' => 'ReclamationsController',
]);

    }
    #[Route('/addF', name: 'addF')]
    public function addF(ReclamationsRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Reclamations();
   $form=$this->createForm(ReclamtionsType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('fetch');
       }
   return $this->render('reclamations/FrontEnd/add.html.twig',['f'=>$form->createView()]);


  }

  #[Route('/afficher', name: 'afficher')]
  public function afficher(ReclamationsRepository $repo): Response
  {
      $resul= $repo->findAll();
      return $this->render('reclamations/afficher.html.twig', [
          'response' => $resul,
      ]);
  }
  #[Route('/afficherE', name: 'afficherE')]
  public function afficherE(ReclamationsRepository $repo): Response
  {
      $resul= $repo->findAll();
      return $this->render('reclamations/FrontEnd/afficher2.html.twig', [
          'response' => $resul,
      ]);
  }
  #[Route('/add', name: 'add')]
  public function add(Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
  {
      
      $Rc = new Reclamations();
      $form = $this->createForm(ReclamtionsType::class, $Rc);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $errors = $validator->validate($Rc);
    
        if(count($errors) > 0){
            $errorsString = (string)$errors;
            
            return new Response($errorsString);
      }
          
      $em=$mr->getManager();
      $em->persist($Rc);
      $em->flush();

    
   return $this->redirectToRoute('afficher');
  }
  return $this->render('reclamations/rendezv.html.twig', [
      'form' => $form->createView(),
  ]);

  }
  #[Route('/addE', name: 'addE')]
  public function addE(Request $request, ManagerRegistry $mr): Response
  {
      
      $Rc = new Reclamations();
      $form = $this->createForm(ReclamtionsType::class, $Rc);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          
      $em=$mr->getManager();
      $em->persist($Rc);
      $em->flush();

    
   return $this->redirectToRoute('afficherE');
  }
  return $this->render('reclamations/edit2.html.twig', [
      'form' => $form->createView(),
  ]);

  }
  #[Route('/rm/{idrec}', name: 'rm')]
  public function rm(ReclamationsRepository $repo, ManagerRegistry $mr, int $idrec): Response
  {
      $std= $repo->find($idrec);
   
      if(!$std){
          return new Response('non trouve');
      }

      $em=$mr->getManager();
      $em->remove($std);
      $em->flush();

    //return new Response('c bon supp');
  return $this->redirectToRoute('afficher');
  }
  #[Route('/editRV/{idrec}', name: 'editRV')]
  public function editRV(int $idrec, ManagerRegistry $mr, Request $req, ReclamationsRepository $repo): Response
  {
      $s = $repo->find($idrec); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('reclamations not found.');
      }
  
      $form = $this->createForm(ReclamtionsType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('afficher'); // Redirect to your list of students
      }
  
      return $this->render('reclamations/rendezv.html.twig',['form'=>$form->createView()]);

  }
  #[Route('/editRV2/{idrec}', name: 'editRV2')]
  public function editRV2(int $idrec, ManagerRegistry $mr, Request $req, ReclamationsRepository $repo): Response
  {
      $s = $repo->find($idrec); // Find the student to update
  
      if (!$s) {
          throw $this->createNotFoundException('reclamations not found.');
      }
  
      $form = $this->createForm(ReclamtionsType::class, $s); // Use the found student for the form
  
      $form->handleRequest($req);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          // You don't need to persist an existing entity, just flush
          $em->flush();
  
          return $this->redirectToRoute('afficherE'); // Redirect to your list of students
      }
  
      return $this->render('reclamations/edit2.html.twig',['form'=>$form->createView()]);

  }
  #[Route('/rm2/{idrec}', name: 'rm2')]
  public function rm2(ReclamationsRepository $repo, ManagerRegistry $mr, int $idrec): Response
  {
      $std= $repo->find($idrec);
   
      if(!$std){
          return new Response('non trouve');
      }

      $em=$mr->getManager();
      $em->remove($std);
      $em->flush();

    //return new Response('c bon supp');
  return $this->redirectToRoute('afficherE');
  }

}



 



