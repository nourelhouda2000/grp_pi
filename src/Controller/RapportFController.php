<?php

namespace App\Controller;
use App\Entity\Rendezvous;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RapportRepository;
class RapportFController extends AbstractController
{
    #[Route('/index_f', name: 'app_rendezvous')]
    public function index(): Response
    {
        return $this->render('rapport/FrontEnd/basef.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }
    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('rapport/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }

/*
    #[Route('/afficherrapportF', name: 'afficherrapportF')]
    public function afficherrapportF(RapportRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('rapport/FrontEnd/afficherrapportF.html.twig', [
            'response' => $resul,
        ]);
    }

    */
    #[Route('/afficherrapportF/{idR}', name: 'afficherrapportF', methods: ['GET', 'POST'])]
    public function afficherrapportF(int $idR, RapportRepository $rapportRepository)
{
    // Récupérer le rendez-vous
    $rendezvous = $this->getDoctrine()->getRepository(Rendezvous::class)->find($idR);
    
    // Vérifier si le rendez-vous existe
    if (!$rendezvous) {
        throw $this->createNotFoundException('Rendez-vous non trouvé');
    }
    
    // Récupérer le rapport associé au rendez-vous
    $rapport = $rapportRepository->findOneBy(['idR' => $rendezvous]);
    
    // Vérifier si un rapport existe pour ce rendez-vous
    if (!$rapport) {
        return $this->render('rapport/FrontEnd/afficherrapportF.html.twig', [
            'response' => $rapport,
        ]);
    }
    
    // Passer le rapport à votre template Twig pour l'afficher
    return $this->render('rapport/FrontEnd/afficherrapportF.html.twig', [
        'response' => $rapport,
    ]);
}

}
