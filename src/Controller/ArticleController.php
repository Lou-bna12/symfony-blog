<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article; 

class ArticleController extends AbstractController
{
    /**
     * Visualisation d'un article
     * @param int $id Id de l'article
     * @return Response
     */
     

    //#[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        //Entity Manager de symfony
        $em = $this->getDoctrine()->getManager();
        //On récupère tous les articles qui à l'id passé en l'url
        $articles = $em->getRepository(Article::class)->findby(['id'=>$id]);

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * modifier / ajouter un article
     * 
     */ 
    public function edit(Request $request, int $id=null): Response 
    {
        $em = $this->getDoctrine()->getManager();

        if($id){
            $mode = 'update';
            $article = $em->getRepository(Article::class)->findBy ([ 'id' => $id ]);
    }
    else{
        $mode = 'new';
        $article = new Article();
    }
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $this->saveArticle($article, $mode);
        

        return $this->redirectToRoute('article_edite', array('id' => $article->getId()));
    }
    $parameters = array(
        'form' => $form,
        'mode' => $mode,
        'article' => $article
    );
    return $this->render('article/edit.html.twig', $parameters);
    }
    /**
     * Supprimer un article
     * 
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->findBy(['id' => $id]) [0];

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }   

     
    /**
     * Completer un article avant de l'enregistrer en BDD
     * 
     * @param Article $article
     * @param string $mode
     * @return Article
     */

    private function completeArticleBeforeSave(Article $article, string $mode): Article
    {
        if($article->getIsPublished()){
            $article->setPublishedAt(new \DateTime());
        }
        $article->setAuthor($this->getUser());

        return $article;
    }
    
    /**
     * Enregistrer un article en BDD
     * 
     * @param Article $article
     * @param string $mode
     */
    private function saveArticle(Article $article, string $mode){
        $article = $this->completeArticleBeforeSave($article, $mode);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

   }

}