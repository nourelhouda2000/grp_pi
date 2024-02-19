<?php

namespace App\Controller;
use App\Entity\Rendezvous;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RendezvousRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Form\RendezvousType;

use App\Form\RendezvousFType;
class RendezvousController extends AbstractController
{
     #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('rendezvous/end/baseend.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }



  

   
    
    #[Route('/afficher', name: 'afficher')]
    public function afficher(RendezvousRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('rendezvous/end/afficher.html.twig', [
            'response' => $resul,
        ]);
    }


    #[Route('/rendez_vous', name: 'rendez_vous')]
    public function add(Request $request, ManagerRegistry $mr): Response
    {
        

        
        $RV = new Rendezvous();
        $form = $this->createForm(RendezvousType::class, $RV);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $em=$mr->getManager();
        $em->persist($RV);
        $em->flush();
  
      
     return $this->redirectToRoute('afficher');
    }
    return $this->render('rendezvous/end/rendezv.html.twig', [
        'form' => $form->createView(),
    ]);
    }


    #[Route('/rm/{idR}', name: 'rm')]
    public function rm(RendezvousRepository $repo, ManagerRegistry $mr, int $idR): Response
    {
        $rendezvous = $repo->find($idR);
        
        if (!$rendezvous) {
            return new Response('Rendez-vous non trouvé');
        }

        $entityManager = $mr->getManager();

        // Vérifier s'il existe un rapport associé au rendez-vous
        $rapport = $rendezvous->getIdRapport();
        if ($rapport) {
            // S'il existe un rapport, le supprimer également
            $entityManager->remove($rapport);
        }

        // Ensuite, supprimer le rendez-vous
        $entityManager->remove($rendezvous);
        $entityManager->flush();

        return $this->redirectToRoute('afficher');
    }

    #[Route('/editRV/{idR}', name: 'editRV', methods: ['GET', 'POST'])]
    public function editRV(Request $request, RendezvousRepository $repo, ManagerRegistry $mr, int $idR): Response
   {
     $rendezvous = $repo->find($idR);

    if (!$rendezvous) {
        // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
        return new Response('rendez vous non trouvé');
    }

    $form = $this->createForm(RendezvousType::class, $rendezvous);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $mr->getManager();
        $em->flush();

        return $this->redirectToRoute('afficher');
    }

    return $this->render('rendezvous/end/editRV.html.twig', [
        'form' => $form->createView(),
    ]);
}







   
}
