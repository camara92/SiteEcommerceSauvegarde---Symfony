<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ChangePasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
class AccountPasswordController extends AbstractController

{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response

        
    {   $notification = null; 
        $user =$this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form ->handleRequest($request);
        //condition 
        if ($form->isSubmitted()&& $form->isValid()){
            $old_password=$form->get('old_password')->getData(); 
            
            //méthode pour comparer le mot de la base à ce mis pour vérifier 
            if($encoder->isPasswordValid($user, $old_password)){
              //dd($old_password);
              //die("Votre mot de passe est à jour.  Merci de vous diriger vers la page de connection");
              $new_password = $form->get('new_password')->getData();
             // dd($new_password);
              $password = $encoder->encodePassword($user, $new_password);
              $user->setPassword($password);
              $this->entityManager->persist($user);//on peut mettre en commentaire mais le résultat reste la même 
              $this->entityManager->flush();
              $notification = "Votre mot de passe a été mis à jour. ";
            }else{
                $notification = "Votre mot de passe actuel n'est pas le bon !";
            }
        }
        return $this->render('account/password.html.twig'
    , ['form'=>$form->createView(), 
    'notification'=>$notification
    ]);
    }
}