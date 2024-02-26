<?php

namespace App\Controller;
use App\Entity\Rendezvous;
use App\Entity\Rapport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RapportRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;      
use App\Form\RapportType;
use App\Repository\RendezvousRepository;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
class RapportFDController extends AbstractController
{
    

    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('rapport/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }


#[Route('/afficherrapportFD', name: 'afficherrapportFD')]
    public function afficherrapportFD(RapportRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('rapport/FrontEnd/afficherrapportFD.html.twig', [
            'response' => $resul,
        ]);
    }

    








    #[Route('/addrapporttFD', name: 'addrapporttFD')]
    public function addrapportFD(Request $request, ManagerRegistry $mr, RapportRepository $rapportRepository, RendezvousRepository $rendezvousRepository,FlashBagInterface $flashBag): Response
{
    $rapport = new Rapport();
    $form = $this->createForm(RapportType::class, $rapport);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si un rapport avec cet ID de rendez-vous existe déjà
        $existingRapport = $rapportRepository->findOneBy(['idR' => $rapport->getIdR()]);
        

        $idRendezvous = $rapport->getIdR();
        $rendezvous = $rendezvousRepository->find($idRendezvous);

        if (!$rendezvous) {
            // Gérer le cas où aucun rendez-vous correspondant à l'ID n'est trouvé
            throw $this->createNotFoundException('Aucun rendez-vous trouvé pour l\'ID donné.');
        }
        $rapport->setIdR($rendezvous);
        if ($existingRapport) {
            // Si un rapport avec cet ID de rendez-vous existe déjà, ajouter une erreur au formulaire
            $form->get('idR')->addError(new FormError('Cet ID de rendez-vous est déjà utilisé.'));
        } else {
            // Sinon, enregistrer le nouveau rapport dans la base de données
            $entityManager = $mr->getManager();
            $entityManager->persist($rapport);
            $entityManager->flush();
            $rendezvous->setIdRapport($rapport);
            $entityManager->flush();
            $flashBag->add('success', 'Rapport a été ajouté avec succès.');
            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('afficherRDVD');
        }
    }

    return $this->render('rapport/FrontEnd/addrapportDF.html.twig', [
        'form' => $form->createView(),
    ]);
}









#[Route('/editrpD/{idRapport}', name: 'editrpD', methods: ['GET', 'POST'])]
    public function editRpD(Request $request, RapportRepository $rapportRepository, ManagerRegistry $mr, int $idRapport,FlashBagInterface $flashBag): Response
    {
        $rp = $rapportRepository->find($idRapport);
    
        if (!$rp) {
            // Gérez le cas où le rapport n'est pas trouvé, par exemple, redirigez vers une page d'erreur
            return new Response('Rapport non trouvé');
        }
    
        $form = $this->createForm(RapportType::class, $rp);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie s'il existe un rapport avec cet ID de rendez-vous
            $existingRapport = $rapportRepository->findOneBy(['idR' => $rp->getIdR()]);
    
            if ($existingRapport && $existingRapport->getIdRapport() !== $rp->getIdRapport()) {
                // Si un rapport avec cet ID de rendez-vous existe déjà et n'est pas celui actuellement édité,
                // ajoutez une erreur au formulaire
                $form->get('idR')->addError(new FormError('Un rapport avec cet ID de rendez-vous existe déjà.'));
            } else {
                // Sinon, enregistrez les modifications dans la base de données
                $entityManager = $mr->getManager();
                $entityManager->flush();
                $flashBag->add('success', 'Rapport a été modifier avec succès.');
                return $this->redirectToRoute('afficherRDVD');
            }
        }
    
        return $this->render('rapport/FrontEnd/editrapportFD.html.twig', [
            'form' => $form->createView(),
        ]);
    }







}
