<?php

namespace App\Http\Admin\Controller\DemoAccountForm;

use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class DemoAccountFormAdminController
 * @package App\Http\Admin\Controller\DemoAccountForm
 * @Route("/admin/demoaccountform/")
 */
class DemoAccountFormAdminController extends AbstractController
{
    /**
     * @Route("list", name="admin_demoaccountform_list")
     */
    public function listArticle() : Response
    {
        $demoAccountForm = $this->getDoctrine()
            ->getRepository(DemoAccountForm::class)
            ->findAll();


        return $this->render("admin/demoAccountForm/list.html.twig",[
            'demoAccountForms' => $demoAccountForm,
        ]);
    }
}