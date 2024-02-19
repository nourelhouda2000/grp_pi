<?php

namespace App\Controller;
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


class RapportController extends AbstractController
{


    //////////////end///////////
    
    #[Route('/index', name: 'app_index')]
    public function indexE(): Response
    {
        return $this->render('rapport/end/baseend.html.twig', [
            'controller_name' => 'IndexController',
        ]);
       
    }
    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('rapport/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }
    #[Route('/afficherrapport', name: 'afficherrapport')]
    public function afficherrapport(RapportRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('rapport/end/afficherrapport.html.twig', [
            'response' => $resul,
        ]);
    }




    








    #[Route('/addrapportt', name: 'addrapportt')]
    public function addrapport(Request $request, ManagerRegistry $mr, RapportRepository $rapportRepository, RendezvousRepository $rendezvousRepository): Response
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
            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('afficherrapport');
        }
    }

    return $this->render('rapport/end/addrapport.html.twig', [
        'form' => $form->createView(),
    ]);
}












    #[Route('/rmrp/{idRapport}', name: 'rmrp')]
    public function rmF(RapportRepository $repo, ManagerRegistry $mr, int $idRapport): Response
    {
        $rapport = $repo->find($idRapport);
         
        if (!$rapport) {
            return new Response('Rapport non trouvé');
        }
    
        // Obtenez le rendez-vous associé au rapport
        $rendezvous = $rapport->getIdR();
    
        if ($rendezvous) {
            // Supprimez le rendez-vous associé au rapport
            $rapport->setIdR(null);
            $em = $mr->getManager();
            $em->remove($rendezvous);
        }
    
        // Supprimez le rapport lui-même
        $em->remove($rapport);
        $em->flush();
      
        return $this->redirectToRoute('afficherrapport');
    }


    #[Route('/editrp/{idRapport}', name: 'editrp', methods: ['GET', 'POST'])]
    public function editRp(Request $request, RapportRepository $rapportRepository, ManagerRegistry $mr, int $idRapport): Response
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
    
                return $this->redirectToRoute('afficherrapport');
            }
        }
    
        return $this->render('rapport/end/editrapport.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
