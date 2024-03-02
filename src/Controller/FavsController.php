<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavsController extends AbstractController
{
    #[Route('/favorites', name: 'favorites_index')]
    public function index(SessionInterface $session, RecetteRepository $repo): Response
    {
        $favorites = $session->get("favorites", []);
        $recette = $repo->findBy(['id' => array_keys($favorites)]);

        return $this->render('recette/favsfrontrecette.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/addfa/{id}', name: 'addfa')]
    public function addfa(int $id, RecetteRepository $recetteRepository, SessionInterface $session): Response
    {
        $recette = $recetteRepository->find($id);

        if (!$recette) {
            throw $this->createNotFoundException('Recette not found for id ' . $id);
        }

        $favorites = $session->get("favorites", []);
        $favorites[$recette->getId()] = $recette;
        $session->set("favorites", $favorites);

        return $this->redirectToRoute("afficherRecettefront");
    }

    #[Route('/favorites/remove/{id}', name: 'favorites_remove')]
    public function removeFromFavorites(Recette $recette, SessionInterface $session): Response
    {
        $favorites = $session->get("favorites", []);

        if (isset($favorites[$recette->getId()])) {
            unset($favorites[$recette->getId()]);
            $session->set("favorites", $favorites);
        }

        return $this->redirectToRoute("favorites_index");
    }

    #[Route('/favorites/clear', name: 'favorites_clear')]
    public function clearFavorites(SessionInterface $session): Response
    {
        $session->remove("favorites");
        return $this->redirectToRoute("favorites_index");
    }
}
