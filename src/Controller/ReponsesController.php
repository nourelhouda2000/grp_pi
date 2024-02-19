<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReponsesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReponsesType;
use App\Entity\Reponses;
use Symfony\Component\Validator\Validator\ValidatorInterface;
class ReponsesController extends AbstractController

{
    #[Route('/reponses', name: 'app_reponses')]
    public function index(): Response
    {
        return $this->render('reponses/index.html.twig', [
            'controller_name' => 'ReponsesController',
        ]);
    }
    #[Route('/repaff', name: 'repaff')]
    public function repaff(ReponsesRepository $repo): Response
    {
        $resul= $repo->findAll();
        return $this->render('reponses/afficherep.html.twig', [
            'response' => $resul,
        ]);
    }
    #[Route('/addrep', name: 'addrep')]
   
  
    public function addrep(Request $request, ManagerRegistry $mr, ValidatorInterface $validator): Response
    {
        
        $Rc = new Reponses();
     
        $form = $this->createForm(ReponsesType::class, $Rc);
  
        $form->handleRequest($request);
  
        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($Rc);
    
            if(count($errors) > 0){
                $errorsString = (string)$errors;
                
                return new Response($errorsString);
          }
            
        $em=$mr->getManager();
        $em->persist($Rc);
        $em->flush();
  
      
     return $this->redirectToRoute('repaff');
    }
    return $this->render('reponses/rendezvrep.html.twig', [
        'form' => $form->createView(),
    ]);
  
    }

    #[Route('/rmrep/{idrep}', name: 'rmrep')]
    public function rm(ReponsesRepository $repo, ManagerRegistry $mr, int $idrep): Response
    {
        $std= $repo->find($idrep);
     
        if(!$std){
            return new Response('non trouve');
        }
  
        $em=$mr->getManager();
        $em->remove($std);
        $em->flush();
  
      //return new Response('c bon supp');
    return $this->redirectToRoute('repaff');
    }
    #[Route('Responses/editRP/{idrep}', name: 'editRP', methods: ['GET', 'POST'])]
public function editRP(Request $request, ReponsesRepository $repo, ManagerRegistry $mr, int $idrep): Response
{
    $s = $repo->find($idrep);

    if (!$s) {
        // Gérez le cas où l'étudiant n'est pas trouvé, par exemple, redirigez vers une page d'erreur
        return new Response('reponses non trouvé');
    }

    $form = $this->createForm(ReponsesType::class, $s);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $mr->getManager();
        $em->flush();

        return $this->redirectToRoute('repaff');
    }
    return $this->render('reponses/editrp.html.twig', [
        'form' => $form->createView(),

      
    ]);
}
#[Route('/repaff2', name: 'repaff2')]
public function repaff2(ReponsesRepository $repo): Response
{
    $resul= $repo->findAll();
    return $this->render('reponses/afficherback.html.twig', [
        'response' => $resul,
    ]);
}

}
