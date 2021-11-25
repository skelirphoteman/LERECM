<?php

namespace App\Http\Admin\Controller\AntiSpamMailer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Domain\AntiSpamMailer\Entity\AntiSpamMailer;
/**
 * Class ArticleAdminController
 * @package App\Http\Admin\Controller\Article
 * @Route("/admin/spam/")
 */
class AntiSpamMailerController extends AbstractController
{
    /**
     * @Route("mailer/add", name="admin_spam_mailer_add")
     */
    public function addSpamMailer() : Response
    {


        return $this->render('admin/spam/mailer/add.html.twig');
    }
}