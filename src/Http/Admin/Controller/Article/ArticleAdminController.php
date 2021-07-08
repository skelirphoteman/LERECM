<?php

namespace App\Http\Admin\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Domain\Article\Entity\Article;
use App\Http\Admin\Form\AddArticleType;
use App\Infrastructure\Article\ArticleService;
/**
 * Class ArticleAdminController
 * @package App\Http\Admin\Controller\Article
 * @Route("/admin/article/")
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route("add/{article}", name="admin_article_add")
     */
    public function addArticle(Article $article = null, Request $request, ArticleService $articleService) : Response
    {
        if($article == null)
        {
            $article = new Article();
            $article->setState(0);
            $article->setCreatedAt(new \DateTime('now'));
        }

        $formArticle = $this->createForm(AddArticleType::class, $article);

        $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article = $formArticle->getData();

            $responseArticle = $articleService->addArticle($article);

            if(!$responseArticle){
                $this->addFlash('success', 'Les informations de l\'article ont bien été enregistrées.');
                return $this->redirectToRoute('admin_index');
            }else{
                $this->addFlash('danger', $responseArticle);
            }
        }
        return $this->render('admin/article/add.html.twig', [
            'form_article' => $formArticle->createView(),
        ]);
    }

    /**
     * @Route("list", name="admin_article_list")
     */
    public function listArticle() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();


        return $this->render("admin/article/list.html.twig",[
            'articles' => $articles,
        ]);
    }
}