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
use Knp\Snappy\Pdf;




//use MercurySeries\FlashyBundle\FlashyNotifier;
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
      $resul= $repo->findAll(); // reccuperer
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
        $em->persist($Nt);//preparation
        $em->flush();// l'executions
        //$flashy->sucess('Nnutrition ajouter aves succes','http://your-awesome-link.com');
  
      
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
        //$flashy->success('Nutritions supprimer avec succes','http://your-awesome-link.com');
        return $this->redirectToRoute('afficher');
       
  
      //return new Response('c bon supp');
   
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
           // $flashy->sucess('Nnutrition Modifier aves succes','http://your-awesome-link.com');
    
            return $this->redirectToRoute('afficher'); // Redirect to your list of students
        }
    
        return $this->render('nutritions/ajout.html.twig',['form'=>$form->createView()]);
  
    }
    #[Route('/trier2', name: 'trier2')]
  public function trier2(Request $request, NutritionsRepository $repo): Response
  {
      // Récupérer le paramètre 'tri' de la requête GET
      $tri = $request->query->get('tri');
      
      // Vérifier si le paramètre 'tri' est défini et valide
      if ($tri === 'tri-croissant') {
          // Si le paramètre est 'tri-croissant', trier par date croissante
          $reclamations = $repo->findAllSortedByCaloriesAsc();
      } elseif ($tri === 'tri-decroissant') {
          // Si le paramètre est 'tri-decroissant', trier par date décroissante
          $reclamations = $repo->findAllSortedByCaloriesDesc();
      } else {
          // Si aucun tri n'est spécifié ou si le tri spécifié n'est pas valide,
          // afficher toutes les réclamations sans tri spécifique
          $reclamations = $repo->findAll();
      }
      
      // Rendre le template avec les réclamations triées ou non triées
      return $this->render('nutritions/afficher.html.twig', [
          'response' => $reclamations,
      ]);
  }
  #[Route('/nutritions/pdf/{id}', name: 'nutritions_pdf')]
public function generatePdf(Pdf $snappy, NutritionsRepository $repository, int $id): Response
{
    $nutritions = $repository->find($id);
    
    if (!$nutritions) {
        throw $this->createNotFoundException('Nutritions does not exist');
    }
    
    $html = $this->renderView('nutritions/pdf.html.twig', [
        'nutritions' => $nutritions
    ]);

    $pdfContent = $snappy->getOutputFromHtml($html);

    // Replace any characters in the plan name that are not valid for a filename
    $safePlanName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $nutritions->getCalories());
    $filename = sprintf('nutritions-%s.pdf', $safePlanName);

    return new Response(
        $pdfContent,
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ]
    );
}

}
