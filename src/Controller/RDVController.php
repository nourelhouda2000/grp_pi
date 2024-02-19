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
        'response' => $resul,
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
    public function addFondend(Request $request, ManagerRegistry $mr, MailerInterface $mailer): Response
    {
    

    
    $RV = new Rendezvous();
    $form = $this->createForm(RendezvousFType::class, $RV);

    $form->handleRequest($request);



    
    if ($form->isSubmitted() && $form->isValid()) {
        
    $em=$mr->getManager();
    $em->persist($RV);
    $em->flush();
    

    // Récupérer l'utilisateur qui prend le rendez-vous
    $user = $this->getUser();

    // Envoyer un e-mail de confirmation à l'utilisateur
    if ($user instanceof User && $user->getEmail()) {
        $email = (new Email())
            ->from('nourelhoudaayari07@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmation de rendez-vous')
            ->html('<p>Votre rendez-vous a été enregistré avec succès.</p>');

        $mailer->send($email);
    }

    // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
    return $this->redirectToRoute('afficherRDVF');

  

    }
    return $this->render('rendezvous/FrontEnd/rendezvF.html.twig', [
    'form' => $form->createView(),
   ]);







   }



 
   




    #[Route('/editRVF/{idR}', name: 'editRVF', methods: ['GET', 'POST'])]
    public function editF(Request $request, RendezvousRepository $repo, ManagerRegistry $mr, int $idR): Response
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

        return $this->redirectToRoute('afficherRDVF');
    }

    return $this->render('rendezvous/FrontEnd/editRVF.html.twig', [
        'form' => $form->createView(),
    ]);
   }


   #[Route('/rmRDVF/{idR}', name: 'rmRDVF')]
    public function rmRDVF(RendezvousRepository $repo, ManagerRegistry $mr, int $idR): Response
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

        return $this->redirectToRoute('afficherRDVF');
    }

}
