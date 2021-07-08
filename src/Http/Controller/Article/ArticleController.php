<?php

namespace App\Http\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Domain\Article\Entity\Article;
/**
 * Class ArticleController
 * @package App\Http\Controller\Article
 * @Route("/app/article/")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route("view/{article}", name="app_article_view")
     */
    public function viewArticle(Article $article = null) : Response
    {
        if($article == null){
            throw $this->createNotFoundException('Aucun article trouvé');
        }

        if($article->getState() != 2)
        {
            $this->addFlash("danger", "Vous n'avez pas accès à cet article.");
            return $this->redirectToRoute("app_index");
        }

        return $this->render('app/article/view.html.twig' , [
            'article' => $article,
        ]);
    }

    /**
     * @Route("list", name="app_article_list")
     */
    public function listArticle() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findby(['state' => 2], ['created_at' => 'desc']);


        return $this->render("app/article/list.html.twig",[
            'articles' => $articles,
        ]);
    }
}