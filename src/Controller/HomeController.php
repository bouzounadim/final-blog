<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface ;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $rep,PaginatorInterface $pag , Request $request)
    {
        $articles=$pag->paginate($rep->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        9 /*limit per page*/
    );
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
  
}
