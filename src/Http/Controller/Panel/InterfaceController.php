<?php

namespace App\Http\Controller\Panel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Article\Entity\Article;
use App\Domain\Task\Entity\Task;

/**
 * Class InterfaceController
 * @package App\Http\Controller\Panel
 * @Route("/app")
 */
class InterfaceController extends AbstractController
{
    /**
     * @Route("", name="app_index")
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy([
                'state' => 2,
                ], [
                    "created_at" => "desc"
            ], 2);

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'state' => 0,
            ], [
                "end_at" => "asc"
            ], 5);

        return $this->render('app/panel/Index.html.twig', [
            'articles' => $articles,
            'tasks' => $tasks
        ]);
    }
}