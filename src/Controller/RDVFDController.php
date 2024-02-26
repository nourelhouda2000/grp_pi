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
        $rendezvous = $repo->findAll();
        return $this->render('rendezvous/FrontEnd/afficherRDVFD.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }


    #[Route('/rechercherendezvousFD', name: 'rechercherendezvousFD')]
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
            return $this->render('rendezvous/FrontEnd/afficherRDVFD.html.twig', [
                'rendezvous' => $rendezvous,
            ]);
        }

        // Si aucune date ou heure de recherche a été soumise ou si aucun rendez-vous correspondant n'a été trouvé, rediriger vers la page d'affichage des rendez-vous
        return $this->redirectToRoute('afficherRDVD');
    }


    #[Route('/rechercherActionFD', name: 'rechercherActionFD')]
  public function rechercherActionFD(Request $request, RendezvousRepository $repository)
  {
      $searchTerm = $request->query->get('searchTerm');
  
      $resultats = $repository->rechercher($searchTerm);
  
      // Passer les résultats à votre vue Twig
      return $this->render('rendezvous/FrontEnd/afficherRDVFD.html.twig', [
        'rendezvous' => $resultats,
      ]);
  }

    
  #[Route('/trier2FD', name: 'trier2FD')]
  public function trier2FD(Request $request, RendezvousRepository $rendezvousRepository): Response
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

      return $this->render('rendezvous/FrontEnd/afficherRDVFD.html.twig', [
        'rendezvous' => $rendezvous,
    ]);
  }
   
}
