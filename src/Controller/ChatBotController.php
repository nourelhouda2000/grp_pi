<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatBotController extends AbstractController
{
    #[Route('/chatbot', name: 'app_chat_bot')]
    public function index(): Response
    {
        return $this->render('chat_bot/index.html.twig', [
            'controller_name' => 'ChatBotController',
        ]);
    }
}
