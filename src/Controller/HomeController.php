<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

class HomeController extends AbstractController
{
    /**Page d'accueil
     * 
     * @return Response
     */

    // #[Route('/home', name: 'app_home')]

    public function index(): Response
    {
        //Entity Manager de symfony
        $em = $this->getDoctrine()->getManager();
        //Tous les articles en BDD
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
