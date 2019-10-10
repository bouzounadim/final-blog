<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\CommentLike;



use Symfony\Component\Security\Core\Security;
use App\Repository\ArticleRepository;
use App\Repository\CommentLikeRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Form\CommentType;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $rep)
    {    
        $articles=$rep->findall();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    //upate et create same time
      /**
     * @route("/article/new",name="articlecreate")
     * @route("article/{id}/edit",name="articleedit")
     */
    public function articleform(Article  $article=null , Request $request ,ObjectManager $manager ,Security $security){
        //parameter conevrter le fair de mettre la classe comme instance u lui de id symfony va svoir quell est.
        $user = $security->getUser();     
        if(!$article){
        $article =new Article();
    }
    //we can creat form from cli make:form 
    //then import the fromRep and then $form=$this->createForm(Articlefrom::classe,$article)
    //and we delete all the createFormbuilder
      $form =$this->CreateFormBuilder($article)
                  ->add('title')
                  ->add('content')
                  ->add('image')
                  ->add('category',EntityType::class, [
                      'class'=> Category::class ,
                      'choice_label'=>'title'
                  ])
                  ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setUser($user);
                $article->setCreatedAt(new \Datetime());
            }
           
            $manager->persist($article);
            $manager->flush();
            return  $this->redirectToRoute('articleval',['id'=>$article->getId()]);
        }
        return $this->render('article/create.html.twig',[
            'articleform'=>$form->createView(),
            'editmod'=>$article->getId()!==null
            //editmod pour changer label de button soit edit ou save  selon l'action
        ]);
          }

    /**
     * @route("/article/{id}", name="articleval")
     */
    public function getOneArticle(Article $article ,Request $request ,ObjectManager $manager,Security $security)
    {    
        $comment= new Comment();
        $user = $security->getUser();     
        $useername=$user->getUsername();
        $fomr=$this->createForm(CommentType::class,$comment);
         $fomr->handleRequest($request);
         if($fomr->isSubmitted() && $fomr->isValid()){
             $comment->setAuthor($useername);
              $comment->setCreatedAt(new \Datetime());
              $comment->setArticle($article);
              $comment->setUser($user);
               $manager->persist($comment);
               $manager->flush();
               return  $this->redirectToRoute('articleval',['id'=>$article->getId()]);

            }
        return $this->render('article/onearticle.html.twig', [
            'article' => $article,
            'commform'=>$fomr->createview()
        ]);
    }
  

/**
     * @route("/article/delete/{id}", name="articledelete")
     */
    public function DeleteArticle(Article $article ,Request $request ,ObjectManager $manager,ArticleRepository $rep)
    {    
        
         $manager->remove($article);
         $manager->flush();
        return $this->render('profil/index.html.twig', []);
    }
  


/**
     * @route("/comment/delete/{id}", name="commentdelete")
     */
    public function DeleteComment( Comment $comment ,Request $request ,ObjectManager $manager,ArticleRepository $rep)
    {     $comm=$comment->getArticle();
         $manager->remove($comment);
         $manager->flush();
         return  $this->redirectToRoute('articleval',['id'=> $comm->getId()]);

    
        }
  /**
   *@route("/article/comment/{id}/like",name="comment_like");
   */

     public function Like(Comment $comment,ObjectManager $man , CommentLikeRepository  $lik):Response
    
     {
       $user=$this->getUser();
       if (!$user) {
           
        return $this->json(['code'=>403,'message'=>'no authtnifié'],403);
    }

    if ($comment->isliked($user))
    {
        $like=$lik->findOneBy([
            'comment' =>$comment,
            'user'=>$user

        ]);
        $man->remove($like);
        $man->flush();
        return $this->json(['code'=>403,'message'=>'Supprmié','likes'=>$lik->count(['comment'=>$comment])],200);
    }

    $like=new CommentLike();
    $like->setUser($user)
        ->setComment($comment);
    $man->persist($like);
    $man->flush();
    return $this->json(['code'=>200,'like bien ajouté '=>'nikcel','likes'=>$lik->count(['comment'=>$comment])],200);

    }


}
