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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Form\RendezvousType;

use App\Form\RendezvousFType;
class RendezvousController extends AbstractController
{



    ///end///

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
            'rendezvous' => $resul
        ]);
    }





  

    #[Route('/rendez_vous', name: 'rendez_vous')]
    public function add(Request $request, ManagerRegistry $mr, FlashBagInterface $flashBag): Response
    {
        $RV = new Rendezvous();
        $form = $this->createForm(RendezvousType::class, $RV);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($RV);
            $em->flush();
    
            // Ajouter un message flash pour la notification
            $flashBag->add('success', 'Le rendez-vous a été ajouté avec succès.');
    
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
    public function editRV(Request $request, RendezvousRepository $repo, ManagerRegistry $mr, int $idR,FlashBagInterface $flashBag): Response
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
        $flashBag->add('success', 'Le rendez-vous a été modifier avec succès.');
        return $this->redirectToRoute('afficher');
    }

    return $this->render('rendezvous/end/editRV.html.twig', [
        'form' => $form->createView(),
    ]);
}




/*#[Route('/rechercherendezvous', name: 'rechercherendezvous')]
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
        return $this->render('rendezvous/end/afficher.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

    // Si aucune date ou heure de recherche a été soumise ou si aucun rendez-vous correspondant n'a été trouvé, rediriger vers la page d'affichage des rendez-vous
    return $this->redirectToRoute('afficher');
}

*/


  #[Route('/trier2', name: 'trier2')]
  public function trier2(Request $request, RendezvousRepository $rendezvousRepository): Response
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

      return $this->render('rendezvous/end/afficher.html.twig', [
        'rendezvous' => $rendezvous,
    ]);
  }


  #[Route('/rechercherAction', name: 'rechercherAction')]
  public function rechercherAction(Request $request, RendezvousRepository $repository)
  {
      $searchTerm = $request->query->get('searchTerm');
  
      $resultats = $repository->rechercher($searchTerm);
  
      // Passer les résultats à votre vue Twig
      return $this->render('rendezvous/end/afficher.html.twig', [
        'rendezvous' => $resultats,
      ]);
  }





  #[Route('/rendez-vous-statistiques', name: 'rendez-vous-statistiques')]
  public function statistiquesRendezvous(): Response
{
    // Récupérer le repository des rendez-vous
    $repository = $this->getDoctrine()->getRepository(RendezVous::class);

    // Récupérer tous les rendez-vous
    $rendezVous = $repository->findAll();

    // Initialiser un tableau pour stocker le nombre de rendez-vous par mois
    $rendezVousParMois = [];

    // Parcourir tous les rendez-vous pour les regrouper par mois
    foreach ($rendezVous as $rdv) {
        // Convertir la dateR en un objet DateTime
        $date = \DateTime::createFromFormat('Y-m-d', $rdv->getDateR());

        // Obtenez le mois à partir de la date du rendez-vous
        $mois = $date->format('F');

        // Incrémentez le compteur pour ce mois
        if (!isset($rendezVousParMois[$mois])) {
            $rendezVousParMois[$mois] = 1;
        } else {
            $rendezVousParMois[$mois]++;
        }
    }

    // Passer les données à la vue Twig pour affichage
    return $this->render('rendezvous/end/statistiquesRendezvous.html.twig', [
        'rendezVousParMois' => $rendezVousParMois,
    ]);
}


















/*
  /**
      @Route("/chatbot", name="chatbot_handle_request", methods={"POST"})
    
    public function handleRequest(Request $request): Response
    {
        // Récupérer les données envoyées par l'assistant virtuel
        $requestData = json_decode($request->getContent(), true);

        // Traiter les données et générer une réponse
        $responseText = $this->processChatbotRequest($requestData);

        // Retourner une réponse JSON à l'assistant virtuel
        $response = [
            'text' => $responseText
        ];

        return $this->json($response);
    }

    // Fonction de traitement des requêtes du chatbot
    private function processChatbotRequest(array $requestData): string
    {
        // Analyser la requête et générer une réponse appropriée
        $intent = $requestData['intent'];
        $message = $requestData['message'];

        // Logique de traitement des intentions et des messages du chatbot
        // Par exemple, vous pouvez utiliser une bibliothèque NLP pour analyser le message et générer une réponse appropriée

        // Exemple simple : répondre avec un message par défaut
        return "Bonjour! Je suis un assistant virtuel. Comment puis-je vous aider?";
    }
  */ 
}
