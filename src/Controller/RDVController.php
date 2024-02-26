<?php

namespace App\Controller;
use App\Entity\Rendezvous;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RendezvousFType;
use App\Repository\RendezvousRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
class RDVController extends AbstractController
{

    ///////////////////////////////////frontEnd///////////////////////////////



     #[Route('/index_f', name: 'index_f')]
    public function index(): Response
    {
        return $this->render('rendezvous/FrontEnd/basef.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }

    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('rendezvous/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }

    #[Route('/afficherRDVF', name: 'afficherRDVF')]
public function afficherRDVF(RendezvousRepository $repo): Response
{
    // Récupérer les rendez-vous de l'utilisateur avec l'ID 1
    $resul = $repo->findBy(['idUser' => 1]); // Utilisez 'idUser' au lieu de 'user'

    return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
        'rendezvous' => $resul,
    ]);
}



/*#[Route('/afficherRDVF', name: 'afficherRDVF')]
public function afficherRDVF(RendezvousRepository $repo): Response
{
    $resul= $repo->findAll();
    return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
        'response' => $resul,
    ]);
}*/


     #[Route('/rendez_vousF', name: 'rendez_vousF')]
    public function addFondend(Request $request, ManagerRegistry $mr,FlashBagInterface $flashBag, MailerInterface $mailer): Response
    {
    

    
    $RV = new Rendezvous();
    $form = $this->createForm(RendezvousFType::class, $RV);

    $form->handleRequest($request);



    
    if ($form->isSubmitted() && $form->isValid()) {
        
    $em=$mr->getManager();
    $em->persist($RV);
    $em->flush();
    
    $flashBag->add('success', 'Le rendez-vous a été ajouté avec succès.');
    // Récupérer l'utilisateur qui prend le rendez-vous
    $user = $this->getUser();

   

    // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
    return $this->redirectToRoute('afficherRDVF');

  

    }
    return $this->render('rendezvous/FrontEnd/rendezvF.html.twig', [
    'form' => $form->createView(),
   ]);







   }



 
   




    #[Route('/editRVF/{idR}', name: 'editRVF', methods: ['GET', 'POST'])]
    public function editF(Request $request, RendezvousRepository $repo, ManagerRegistry $mr, int $idR,FlashBagInterface $flashBag): Response
   {
    $rendezvous = $repo->find($idR);

    if (!$rendezvous) {
        // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
        return new Response('rendez vous non trouvé');
    }

    $form = $this->createForm(RendezvousFType::class, $rendezvous);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $mr->getManager();
        $em->flush();
        $flashBag->add('success', 'Le rendez-vous a été modifier avec succès.');
        return $this->redirectToRoute('afficherRDVF');
    }

    return $this->render('rendezvous/FrontEnd/editRVF.html.twig', [
        'form' => $form->createView(),
    ]);
   }


   #[Route('/rmRDVF/{idR}', name: 'rmRDVF')]
    public function rmRDVF(RendezvousRepository $repo, ManagerRegistry $mr, int $idR,FlashBagInterface $flashBag): Response
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
        $flashBag->add('success', 'Le rendez-vous a été Annuler avec succès.');
        return $this->redirectToRoute('afficherRDVF');
    }



    #[Route('/rechercherendezvousF', name: 'rechercherendezvousF')]
    public function rechercherendezvous(Request $request, RendezvousRepository $rendezvousRepository): Response
    {
        // Récupérer la date de recherche depuis la requête
        $searchDate = $request->query->get('date');
        // Récupérer l'heure de recherche depuis la requête
        $searchHeure = $request->query->get('heure');
    
        // Vérifier si une date de recherche a été soumise
        if ($searchDate || $searchHeure) {
            // Formater la date de recherche au format AAAA-MM-DD
            $searchDateFormatted = $searchDate ? date('Y-m-d', strtotime($searchDate)) : null;
    
            // Utiliser le repository pour rechercher les rendez-vous correspondant à la date ou à l'heure de recherche
            $rendezvous = $rendezvousRepository->searchByDateOrHeure($searchDateFormatted, $searchHeure);
    
            // Retourner la réponse avec les résultats de la recherche
            return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
                'rendezvous' => $rendezvous,
            ]);
        }
    
        // Si aucune date ou heure de recherche a été soumise ou si aucun rendez-vous correspondant n'a été trouvé, rediriger vers la page d'affichage des rendez-vous
        return $this->redirectToRoute('afficherRDVF');
    }
    
    #[Route('/rechercherActionF', name: 'rechercherActionF')]
  public function rechercherActionF(Request $request, RendezvousRepository $repository)
  {
      $searchTerm = $request->query->get('searchTerm');
  
      $resultats = $repository->rechercher($searchTerm);
  
      // Passer les résultats à votre vue Twig
      return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
        'rendezvous' => $resultats,
      ]);
  }


    
  #[Route('/trier2F', name: 'trier2F')]
  public function trier2F(Request $request, RendezvousRepository $rendezvousRepository): Response
  {
      // Récupérer le paramètre 'tri' de la requête GET
      $tri = $request->query->get('tri');

      // Sélectionner la méthode de tri en fonction de la valeur du paramètre 'tri'
      if ($tri === 'croissant') {
          $rendezvous = $rendezvousRepository->findAllSortedByDate();
      } elseif ($tri === 'decroissant') {
          $rendezvous = $rendezvousRepository->findAllSortedByDatedec();
      } else {
          // Gérer le cas où aucun tri n'est sélectionné (tri par défaut, par exemple)
          // Vous pouvez aussi rediriger l'utilisateur vers une page d'erreur
          $rendezvous = $rendezvousRepository->findAll();
      }

      return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
        'rendezvous' => $rendezvous,
    ]);
  }





  public function listRendezvous(RendezvousRepository $rendezvousRepository): Response
  {
      // Appel de la méthode du repository pour récupérer les rendez-vous dans les 5 prochaines heures
      $rendezvousInNextFiveHours = $rendezvousRepository->findRendezvousInNextFiveHours();

      // Passez les données à votre vue Twig
      return $this->render('rendezvous/FrontEnd/afficherRDVF.html.twig', [
          'rendezvous' => $rendezvousInNextFiveHours,
      ]);
  }

}
