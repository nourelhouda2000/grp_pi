<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\ExerciceType;
use App\Repository\ExerciceRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Exercice;
use Symfony\Component\HttpFoundation\Request;

class ExerciceController extends AbstractController
{
    #[Route('/exercice', name: 'exercice')]
    public function index(): Response
    {
        return $this->render('FrontEnd/basef.html.twig', [
            'controller_name' => 'ExerciceController',
        ]);
    }
    #[Route('/indexx', name: 'indexx')]
    public function indexend(): Response
    {
        return $this->render('BackEnd/baseend.html.twig', [
            'controller_name' => 'ExerciceController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetch(ExerciceRepository $repo) : Response 
    {
        $result=$repo->findAll();
        return $this->render('BackEnd/exercice/afficher.html.twig',['response' =>$result]);
    }

    #[Route('/addFE', name: 'addFE')]
      public function addF(ExerciceRepository $repo,ManagerRegistry $mr,Request $req)
    {$s=new Exercice();
     $form=$this->createForm(ExerciceType::class,$s);
     $form->handleRequest($req);
         if($form->isSubmitted())
         {
            $em=$mr->getManager();
            $em->persist($s);
            $em->flush();
            return $this->redirectToRoute('fetch');
         }
     return $this->render('BackEnd/exercice/add.html.twig',['form'=>$form->createView()]);


    }
    #[Route('/update/{id}', name: 'update')]
    public function updateEcole(int $id, ManagerRegistry $mr, Request $req, ExerciceRepository $repo): Response
    {
        $s = $repo->find($id); 
    
        if (!$s) {
            throw $this->createNotFoundException('Exercice not found.');
        }
    
        $form = $this->createForm(ExerciceType::class, $s); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            // You don't need to persist an existing entity, just flush
            $em->flush();
    
            return $this->redirectToRoute('fetch'); // Redirect to your list of students
        }
    
        return $this->render('BackEnd/exercice/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
public function remove(ExerciceRepository $repo, $id, ManagerRegistry $mr):Response
{
    $exercice= $repo->find($id);
    $em = $mr->getManager();
    $em->remove($exercice);
    $em->flush();

    return $this ->redirectToRoute('fetch');
}


//partie front
#[Route('/afficherFE', name: 'afficherFE')]
    public function afficherFE(ExerciceRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('FrontEnd/exercice/afficherFE.html.twig', [
            'response' => $resul,
        ]);
    }
    #[Route('/AddFEX', name: 'AddFEX')]
    public function AddFEX(ExerciceRepository $repo,ManagerRegistry $mr,Request $req)
   {$s=new Exercice();
   $form=$this->createForm(ExerciceType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('afficherFE');
       }
   return $this->render('FrontEnd/exercice/AddFE.html.twig',['form'=>$form->createView()]);


  }


  #[Route('/removeFE/{id}', name: 'removeFE')]
public function removeFE(ExerciceRepository $repo, $id, ManagerRegistry $mr):Response
{
    $exercice= $repo->find($id);
    $em = $mr->getManager();
    $em->remove($exercice);
    $em->flush();

    return $this ->redirectToRoute('afficherFE');
}
#[Route('/updateFE/{id}', name: 'updateFE')]
    public function updateFE(int $id, ManagerRegistry $mr, Request $req, ExerciceRepository $repo): Response
    {
        $s = $repo->find($id); 
    
        if (!$s) {
            throw $this->createNotFoundException('Exercice not found.');
        }
    
        $form = $this->createForm(ExerciceType::class, $s); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            // You don't need to persist an existing entity, just flush
            $em->flush();
    
            return $this->redirectToRoute('afficherFE'); // Redirect to your list of students
        }
    
        return $this->render('FrontEnd/exercice/editFE.html.twig', [
            'form' => $form->createView()
        ]);
    }


#[Route('/statistiques_niveau', name :'statistiques_niveau')]
public function statistiquesNiveau(): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $facileCount = $entityManager->createQuery(
        'SELECT COUNT(e)
        FROM App\Entity\Exercice e
        WHERE e.niveau = :niveau'
    )->setParameter('niveau', 'facile')
    ->getSingleScalarResult();

    $modereCount = $entityManager->createQuery(
        'SELECT COUNT(e)
        FROM App\Entity\Exercice e
        WHERE e.niveau = :niveau'
    )->setParameter('niveau', 'modéré')
    ->getSingleScalarResult();

    $difficileCount = $entityManager->createQuery(
        'SELECT COUNT(e)
        FROM App\Entity\Exercice e
        WHERE e.niveau = :niveau'
    )->setParameter('niveau', 'difficile')
    ->getSingleScalarResult();

    return $this->render('BackEnd/exercice/statistiques_niveau.html.twig', [
        'facileCount' => $facileCount,
        'modereCount' => $modereCount,
        'difficileCount' => $difficileCount,
    ]);
}
  
}


