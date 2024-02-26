<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options; // N'oubliez pas cette ligne

use App\Entity\Rendezvous;

class PDFRendezVousController extends AbstractController
{
    #[Route('/PDFRendezVous', name: 'PDFRendezVous')]
    public function index(): Response
    {
        return $this->render('PDFRendezVous/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }

    #[Route('/generate_pdfRDV/{idR}', name: 'generate_pdfRDV')]
    public function generatePdf($idR): Response
    {
        // Récupérer la rendezvous à partir de l'identifiant
        $rendezvous = $this->getDoctrine()->getRepository(Rendezvous::class)->find($idR);
    
        if (!$rendezvous) {
            throw $this->createNotFoundException('La rendezvous n\'existe pas');
        }
    
        // Générer le contenu HTML de 
        $html = $this->renderView('rendezvous/end/pdfRDV.html.twig', ['rendezvous' => $rendezvous]);
    
        // Générer le fichier PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();
    
        // Retourner le fichier PDF en réponse
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $filename = strtolower(str_replace(' ', '-', $rendezvous->getDateR())) . '.pdf'; // Remplacez "getTitreR()" par la méthode qui vous donne le titre 
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");
    
        return $response;
    }
}
