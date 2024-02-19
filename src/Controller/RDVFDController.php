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
class RDVFDController extends AbstractController
{
    
    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('rendezvous/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }
    

#[Route('/afficherRDVD', name: 'afficherRDVD')]
public function afficherRDVFD(RendezvousRepository $repo): Response
{
    $resul= $repo->findAll();
    return $this->render('rendezvous/FrontEnd/afficherRDVFD.html.twig', [
        'response' => $resul,
    ]);
}





   
}
