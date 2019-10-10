<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request , ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {
        $user =new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
          $hash =$encoder->encodePassword($user,$user->getPassword());
          $user->setPassword($hash);
             $manager->persist($user);
             $manager->flush();
             return $this->redirectToRoute('secure_login');
        }

        return $this->render('security/register.html.twig', [
            'controller_name' => 'SecurityController',
            'form'=>$form->createView()
        ]);
    }

      /**
     * @Route("/login", name="secure_login")
     */ 
     public function Login()
    {
        return $this->render('security/Login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

     /**
     * @Route("/logout", name="secure_logout")
     */ 
    public function Logout()
    {
        
    }
}
