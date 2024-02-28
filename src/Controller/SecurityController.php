<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginFormType;
use Psr\Log\LoggerInterface; // Import LoggerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\VarDumper\VarDumper;


class SecurityController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserLoginFormType::class);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('mdp')->getData();

                        // Retrieve the user based on the email
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            // Authentication check
            if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
                // Log authentication failure
                
                $this->logger->info('Authentication check', ['email' => $email, 'password' => $password]);

                // Dump variables using VarDumper
                dump($email, $password);

                throw new CustomUserMessageAuthenticationException('Email or password is incorrect.');
            }

            // If authentication is successful, redirect based on the user's role
            return $this->handleUserRoleRedirect($user);

        }

        return $this->render('user/FrontEnd/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function handleUserRoleRedirect(User $user): Response
    {
        $role = $user->getRole();

        switch ($role) {
            case 0:
                return $this->redirectToRoute('index');
            case 1:
                return $this->redirectToRoute('index_fD');
            case 2:
                return $this->redirectToRoute('index_f');
            default:
                return $this->redirectToRoute('index_fHome');
        }
    }
}




