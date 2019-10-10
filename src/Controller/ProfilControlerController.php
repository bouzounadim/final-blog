<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ProfilRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Form\ProfilFormType;

class ProfilControlerController extends AbstractController
{

      /**
     * @Route("/profil/edit", name="profil_edit")
     */
    public function editprofile(Profil  $pro=null,Security $security,Request $request , ObjectManager $manager)
    {       
                   
        $user = $security->getUser(); 
        $pro=$user->getProfil();
        if(!$pro)
        {
            $pro =new Profil();
        }  
        
        $form=$this->createForm(ProfilFormType::class,$pro);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$pro->getId())
            {
                $pro->setUser($user );
            }
             $manager->persist($pro);
             $manager->flush();
             return $this->redirectToRoute('profil_controler');
        }
    
        return $this->render('profil/edit.html.twig', [
            'controller_name' => 'ProfilControlerController',
            'form'=>$form->createView(),
            'pro'=>$pro
        ]);
    }
    /**
     * @Route("/profil", name="profil_controler")
     */
    public function index(UserInterface $user,ProfilRepository $rep,ArticleRepository $repp ,CommentRepository  $reppp)
    {     $pro=new Profil();
      
                $user = $this->getUser();
                $articles=$repp->findby([
                    'user'=>$user->getId()
                ]);
                $commnets=$reppp->findby([
                    'user'=>$user->getId()
                ]);
        $pro=$user->getProfil();
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilControlerController',
            'arti'=>$articles,
            'comm'=>$commnets,
            'pro'=>$pro

        ]);
    }
    
}
