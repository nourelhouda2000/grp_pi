<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActiviteRepository;
use App\Form\ActiviteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Activite;
use Dompdf\Dompdf;
use TCPDF;






class ActiviteController extends AbstractController
{
    #[Route('/activite', name: 'activite')]
    public function index(): Response
    {
        return $this->render('FrontEnd/basef.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }
    #[Route('/index', name: 'index')]
    public function indexend(): Response
    {
        return $this->render('BackEnd/baseend.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }


    #[Route('/addF', name: 'addF')]
    public function addF(ActiviteRepository $repo,ManagerRegistry $mr,Request $req)
  {$s=new Activite();
   $form=$this->createForm(ActiviteType::class,$s);
   $form->handleRequest($req);
       if($form->isSubmitted())
       {
          $em=$mr->getManager();
          $em->persist($s);
          $em->flush();
          return $this->redirectToRoute('fetch');
       }
   return $this->render('FrontEnd/activite/index.html.twig',['f'=>$form->createView()]);


  }

  #[Route('/afficher', name: 'afficher')]
    public function afficher(ActiviteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('BackEnd/activite/afficher.html.twig', [
            'response' => $resul,
        ]);
    }



    #[Route('/add', name: 'add')]
    public function add(Request $request, ManagerRegistry $mr): Response
    {
        
        $RV = new Activite();
        $form = $this->createForm(ActiviteType::class, $RV);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $em=$mr->getManager();
        $em->persist($RV);
        $em->flush();
  
      
     return $this->redirectToRoute('afficher');
    }
    return $this->render('BackEnd/activite/rendezv.html.twig', [
        'form' => $form->createView(),
    ]);
    }

    #[Route('/rm/{id}', name: 'rm')]
    public function rm(ActiviteRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $std= $repo->find($id);
     
        if(!$std){
            return new Response('non trouve');
        }

        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('afficher');
    }

    #[Route('/editt/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActiviteRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $activite = $repo->find($id);
    
        if (!$activite) {
            // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
            return new Response('activite non trouvé');
        }
    
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('afficher');
        }
    
        return $this->render('BackEnd/activite/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/activite/tri-par-nom', name: 'app_activite_tri_par_nom', methods: ['GET'])]
    public function triParNom(ActiviteRepository $activiteRepository): Response
    {
        $activites = $activiteRepository->triParNom();
    
        return $this->render('BackEnd/activite/indexTri.html.twig', [
            'activites' => $activites,
        ]);
    }
    /*#[Route('/pdf/{id}', name: 'pdf', methods: ['GET'])]
    public function exportActivitePdfAction(int $id): Response
    {
        $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id);
    
        if (!$activite) {
            throw $this->createNotFoundException('Activité non trouvée');
        }
    
        $html = $this->renderView('activite/pdf.html.twig', [
            'activite' => $activite,
        ]);
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        // Obtenez le contenu du PDF généré
        $pdfOutput = $dompdf->output();
    
        // Spécifiez le chemin où vous souhaitez enregistrer le fichier PDF
        $pdfFilePath = 'desktop/activite.pdf';
    
        // Enregistrez le fichier PDF sur le serveur
        file_put_contents($pdfFilePath, $pdfOutput);
    
        // Créez une réponse pour rediriger l'utilisateur vers le fichier PDF téléchargé
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="activite.pdf"');
        $response->setContent(file_get_contents($pdfFilePath));
    
        return $response;
    }*/
    /*#[Route('/pdf/{id}', name: 'export_activite_pdf', methods: ['GET'])]
    public function exportActivitePdfAction(int $id): Response
    {
        $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id);

        if (!$activite) {
            throw $this->createNotFoundException('Activité non trouvée');
        }

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Ajouter une nouvelle page
        $pdf->AddPage();

        // Définir le contenu du PDF
        $html = $this->renderView('activite/pdf.html.twig', [
            'activite' => $activite,
        ]);

        // Écrire le HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Obtenir le contenu du PDF généré
        $pdfContent = $pdf->Output('activite.pdf', 'S');

        // Créer une réponse avec le contenu du PDF
        $response = new Response($pdfContent);

        // Définir les en-têtes de la réponse pour indiquer qu'il s'agit d'un fichier PDF
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="activite.pdf"');

        return $response;
    }*/
   
    //front
    #[Route('/afficherF', name: 'afficherF')]
    public function afficherF(ActiviteRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('FrontEnd/activite/afficherF.html.twig', [
            'response' => $resul,
        ]);
    }
    
    #[Route('/addFA', name: 'addFA')]
    public function addFA(Request $request, ManagerRegistry $mr): Response
    {
        
        $RV = new Activite();
        $form = $this->createForm(ActiviteType::class, $RV);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $em=$mr->getManager();
        $em->persist($RV);
        $em->flush();
  
      
     return $this->redirectToRoute('afficherF');
    }
    return $this->render('FrontEnd/activite/addFA.html.twig', [
        'form' => $form->createView(),
    ]);
    }
    #[Route('/rm/{id}', name: 'rmF')]
    public function rmF(ActiviteRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $std= $repo->find($id);
     
        if(!$std){
            return new Response('non trouve');
        }

        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('afficherF');
    }

    #[Route('/edittFA/{id}', name: 'editFA', methods: ['GET', 'POST'])]
    public function editFA(Request $request, ActiviteRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $activite = $repo->find($id);
    
        if (!$activite) {
            // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
            return new Response('activite non trouvé');
        }
    
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('afficherF');
        }
    
        return $this->render('FrontEnd/activite/editF.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/rmFA/{id}', name: 'rmFA')]
    public function rmFA(ActiviteRepository $repo, ManagerRegistry $mr, int $id): Response
    {
        $std= $repo->find($id);
     
        if(!$std){
            return new Response('non trouve');
        }

        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('afficherF');
    }
}
