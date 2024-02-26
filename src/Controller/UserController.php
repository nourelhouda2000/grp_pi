<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class UserController extends AbstractController
{


    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/end/baseend.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);


      
    }
   



    #[Route('/afficheruser', name: 'afficheruser')]
    public function afficheruser(UserRepository $repo): Response
    {
        $resul= $repo->findAll();
   
        return $this->render('user\end\afficherUser.html.twig', [
            'response' => $resul,
        ]);
    }


    #[Route('/ajouteruser', name: 'ajouteruser')]
    public function adduser(Request $request, ManagerRegistry $mr): Response
    {
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $em=$mr->getManager();
        $em->persist($user);
        $em->flush();
  
        return $this->redirectToRoute('afficheruser');
     
    }
    return $this->render('user/end/adduser.html.twig', [
        'form' => $form->createView(),
    ]);
    }




   


    #[Route('/rmuser/{idUser}', name: 'rmuser')]
    public function rmuser(UserRepository $repo, ManagerRegistry $mr, int $idUser): Response
    {
        $std= $repo->find($idUser);
     
        if(!$std){
            return new Response('non trouve');
        }

        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('afficheruser');
    }



    #[Route('/edituser/{idUser}', name: 'edituser', methods: ['GET', 'POST'])]
    public function edituser(Request $request, UserRepository $repo, ManagerRegistry $mr, int $idUser): Response
    {
        $user = $repo->find($idUser);
    
        if (!$user) {
            // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
            return new Response('rendez vous non trouvé');
        }
    
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('afficheruser');
        }
    
        return $this->render('user/end/edituser.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    

}
