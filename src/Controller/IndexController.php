<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('reclamations/end/baseend.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    public function votreAction(Request $request)
{
    // Créez le formulaire en utilisant le formulaire type que vous avez créé
    $form = $this->createForm(ReclamtionsType::class);

    // Traitez le formulaire si nécessaire

    // Passez la variable form au rendu du template
    return $this->render('reclamations/end/baseend.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
