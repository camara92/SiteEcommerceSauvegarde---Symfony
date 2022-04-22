<?php

namespace App\Controller;
//use App\Controller\RegisterController;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
        // Au choix dans ton constructeur ou dans chaque méthode :
       // public function __construct(private ManagerRegistry $doctrine) {}
        
   
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
     /**
     * @Route("/inscription", name="register")
     */

    public function index(Request $request, UserPasswordEncoderInterface $encoder) : Response
    {  
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
       
       $user=$form->getData();
       $password = $encoder->encodePassword($user, $user->getPassword());
       $user->setPassword($password);
      // $doctrine=$this->getDoctrine()->getManager();
       //$doctrine->persist($user);
        // $doctrine->flush(); //enregistrement dans la database
       //dd($password);
      $this->entityManager->persist($user);
      $this->entityManager->flush();
     }
      return $this->render('register/index.html.twig',
    [
        'form'=> $form->createView()
    ]);
    
    }
}