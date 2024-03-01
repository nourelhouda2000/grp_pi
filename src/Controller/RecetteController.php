<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Recette;
use App\Form\RecettesType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette')]
    public function index(): Response
    {
        return $this->render('recette/index.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
    }
    #[Route('/afficherRecette', name: 'afficherRecette')]
    public function afficherRecette(RecetteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('recette/afficherr.html.twig', [
            'response' => $resul,
        ]);
    }
    #[Route('/afficherRecettefront', name: 'afficherRecettefront')]
    public function afficherRecettefront(RecetteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('recette/afficher2.html.twig', [
            'response' => $resul,
        ]);
    }
 /*    #[Route('/ajouterRecette', name: 'ajouterRecette')]
    public function ajouterRecette(Request $request, ManagerRegistry $mr): Response
    {
        $Nt = new Recette();
        $form = $this->createForm(RecettesType::class, $Nt);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $imageFileName = $form->get('imageFileName')->getData();  // Updated field name
    
            if ($imageFileName) {
                $originalFilename = pathinfo($imageFileName->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFileName->guessExtension();
    
                try {
                    $imageFileName->move(
                        $this->getParameter('kernel.project_dir') . '/public/images',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something goes wrong during file upload
                    throw new \Exception('Error uploading the image');
                }
    
                // Save the image filename to the database
                $Nt->setImageFileName($newFilename);
            }
    
            $em = $mr->getManager();
            $em->persist($Nt);
            $em->flush();
    
            return $this->redirectToRoute('afficherRecette');
        }
    
        return $this->render('recette/ajoutr.html.twig', [
            'form' => $form->createView(),
        ]);
    } */
    #[Route('/ajouterRecette', name: 'ajouterRecette')]
      public function ajouterRecette(Request $request, ManagerRegistry $mr): Response
      {
          
          $Nt = new Recette();
          $form = $this->createForm(RecettesType::class, $Nt);
  
          $form->handleRequest($request);
  
          if ($form->isSubmitted() && $form->isValid()) {
              
          $em=$mr->getManager();
          $em->persist($Nt);
          $em->flush();
    
        
       return $this->redirectToRoute('afficherRecette');
      }
      return $this->render('recette/ajoutr.html.twig', [
          'form' => $form->createView(),
      ]);
      }
      #[Route('/supprimerRecette/{id}', name: 'supprimerRecette')]
      public function supprimerRecette(RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
      {
          $std= $repo->find($id);
       
          if(!$std){
              return new Response('non trouve');
          }
  
          $em=$mr->getManager();
          $em->remove($std);
          $em->flush();
    
        //return new Response('c bon supp');
      return $this->redirectToRoute('afficherRecette');
      }
  
   #[Route('/modifierRecette/{id}', name: 'modifierRecette', methods: ['GET', 'POST'])]
  public function modifierRecette(Request $request, RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
  {
      $Recettes = $repo->find($id);
  
      if (!$Recettes) {
          // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
          return new Response('introuvable');
      }
  
      $form = $this->createForm(RecettesType::class, $Recettes);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          $em->flush();
  
          return $this->redirectToRoute('afficherRecette');
      }

      return $this->render('recette/ajoutr.html.twig', [
          'form' => $form->createView(),
      ]);
  } 
 /* #[Route('/modifierRecettefront/{id}', name: 'modifierRecettefront', methods: ['GET', 'POST'])]
  public function modifierRecettefront(Request $request, RecetteRepository $repo, ManagerRegistry $mr, int $id): Response
  {
      $Recettes = $repo->find($id);
  
      if (!$Recettes) {
          // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
          return new Response('introuvable');
      }
  
      $form = $this->createForm(RecettesType::class, $Recettes);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
      
          $em = $mr->getManager();
          $em->flush();
  
          return $this->redirectToRoute('afficherRecettefront');
      }

      return $this->render('recette/ajoutr2.html.twig', [
          'form' => $form->createView(),
      ]);
  } */
  private $flashBag;

  public function __construct(FlashBagInterface $flashBag)
  {
      $this->flashBag = $flashBag;
  }
  #[Route('/modifierRecettefront/{id}', name: 'modifierRecettefront', methods: ['GET', 'POST'])]
  public function modifierRecettefront(Request $request, RecetteRepository $repo, ManagerRegistry $mr, MailerInterface $mailer, int $id): Response
  {
      $Recette= $repo->find($id);

      if (!$Recette) {
          return new Response('introuvable');
      }

      $form = $this->createForm(RecettesType::class, $Recette);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $em = $mr->getManager();
          $em->flush();
          $this->flashBag->add('success', 'recette a été changé avec succès.'); // Utilisation du flashBag ici

          // Send email notification to admin
          $this->sendNotificationEmail($mailer, $Recette);

          return $this->redirectToRoute('afficherRecettefront');
      }

      return $this->render('recette/ajoutr2.html.twig', [
          'form' => $form->createView(),
      ]);
  }

  private function sendNotificationEmail(MailerInterface $mailer, Recette $recette)
  {
      $email = (new Email())
          ->from('your_email@example.com')  // Replace with your email
          ->to('samarchouikhi93@gmail.com')  // Admin's email address
          ->subject('Modification Notification')
          ->html('The recipe with ID ' . $recette->getId() . ' has been modified.');

      $mailer->send($email);
  }


  
  #[Route('/rechercherAction', name: 'rechercherAction')]
  public function rechercherAction(Request $request, RecetteRepository $repository)
  {
      $searchTerm = $request->query->get('searchTerm');
  
      $resultats = $repository->rechercher($searchTerm);
  
      // Passer les résultats à votre vue Twig
      return $this->render('recette/afficherr.html.twig', [
        'response' => $resultats,
      ]);
  }
  public function findAllSortedByDate()
{
    return $this->createQueryBuilder('r')
        ->orderBy('r.calories', 'ASC') 
        ->getQuery()
        ->getResult();
}
public function findAllSortedByDatedec()
{
    return $this->createQueryBuilder('r')
        ->orderBy('r.calories', 'DESC') 
        ->getQuery()
        ->getResult();
}
#[Route('/filtrage', name: 'filtrage', methods: ['GET'])]
public function filtrage(RecetteRepository $repo, Request $request): Response
{
    $nom = $request->query->get('nom');
    $ingredient = $request->query->get('ingredient');
    $category = $request->query->get('category');

    $filteredProducts = $repo->findByFilters(["nom" => $nom, "ingredient" => $ingredient, "category" => $category]);

    dump($filteredProducts); // Debugging statement

    return $this->render('recette/afficherr.html.twig', [
        'response' => $filteredProducts,
    ]);
}

  }
  
  

