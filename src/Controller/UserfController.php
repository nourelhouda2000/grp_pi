<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent; // Add this import
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; // Add this import
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserfController extends AbstractController
{
    
    
    

    #[Route('/index_fD', name: 'index_fD')]
    public function indexFD(): Response
    {
        return $this->render('user/FrontEnd/basefD.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }
    
    
    
    private $tokenStorage;
    private $eventDispatcher;

    private $userRepository;
    private $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenStorageInterface $tokenStorage, // Ajoutez ceci
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage; // Initialisez $tokenStorage
        $this->eventDispatcher = $eventDispatcher;
    }

   
    
    

    #[Route('/index_fHome', name: 'index_fHome')]
    public function indexHome(): Response
    {
        return $this->render('user/FrontEnd/basefHome.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/index_f', name: 'index_f')]
    public function index(): Response
    {
        return $this->render('user/FrontEnd/basef.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/creercompte', name: 'creercompte')]
    public function adduserF(Request $request, ManagerRegistry $mr, Security $security): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'email existe déjà dans la base de données
            $existingUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($user->getRole() === 0) {
                // Si le rôle est 0, redirigez l'utilisateur vers la page d'accueil
                return $this->redirectToRoute('index');

            } elseif ($user->getRole() === 1 ) {
                // Si le rôle est 1 ou 2, redirigez l'utilisateur vers la page index_f
                return $this->redirectToRoute('index_fD');
              
            } elseif ($user->getRole() === 2) {
                // Si le rôle est 1 ou 2, redirigez l'utilisateur vers la page index_f
                return $this->redirectToRoute('index_f');
            }
            if ($existingUser) {
                // Afficher un message d'erreur dans le formulaire de création de compte
                $form->get('email')->addError(new FormError('Cet email est déjà utilisé. Veuillez en choisir un autre.'));
                return $this->render('user/FrontEnd/creercompte.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
            // Si l'email n'existe pas déjà, enregistrer le nouvel utilisateur
            $em = $mr->getManager();
            $em->persist($user);
            $em->flush();
    
            // Authentifier l'utilisateur après la création du compte
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
    
            // Déclencher l'événement de connexion manuellement
            $loginEvent = new InteractiveLoginEvent($request, $token);
            $this->eventDispatcher->dispatch($loginEvent);
    
            // Redirection en fonction du rôle de l'utilisateur
            switch ($user->getRole()) {
                case 0:
                    return $this->redirectToRoute('index');
                case 1:
                    return $this->redirectToRoute('index_fD');
                case 2:
                    return $this->redirectToRoute('index_f');
                default:
                    // Redirection par défaut si le rôle n'est pas géré
                    return $this->redirectToRoute('default_route');
            }
        }
    
        return $this->render('user/FrontEnd/creercompte.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
    
    #[Route('/login', name: 'login')]
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Créer le formulaire de connexion
        $form = $this->createForm(UserType::class);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();
    
            // Rechercher l'utilisateur par son email
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            $user = $security->getUser();

            if ($user) {
                // Redirection en fonction du rôle de l'utilisateur
                switch ($user->getRole()) {
                    case 0:
                        return $this->redirectToRoute('index');
                    case 1:
                        return $this->redirectToRoute('index_f');
                    case 2:
                        return $this->redirectToRoute('index_f');
                    default:
                        // Redirection par défaut si le rôle n'est pas géré
                        return $this->redirectToRoute('default_route');
                }
            } else {
            if (!$user) {
                // Utilisateur non trouvé, afficher un message d'erreur
                $this->addFlash('error', 'Adresse e-mail incorrecte');
                return $this->redirectToRoute('login');
            }
    
            // Vérifier si le mot de passe est correct
            if (!$passwordEncoder->isPasswordValid($user, $data['mdp'])) {
                // Mot de passe incorrect, afficher un message d'erreur
                $this->addFlash('error', 'Mot de passe incorrect');
                return $this->redirectToRoute('login');
            }
        }
            // Authentifier l'utilisateur
            // (vous pouvez utiliser le service Symfony Security pour cela)
            // Exemple : $this->get('security.token_storage')->setToken(...);
            // Rediriger l'utilisateur après la connexion réussie
            return $this->redirectToRoute('index_fHome');
        }
    
        // Afficher le formulaire de connexion dans le template Twig
        return $this->render('user/FrontEnd/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    
}
