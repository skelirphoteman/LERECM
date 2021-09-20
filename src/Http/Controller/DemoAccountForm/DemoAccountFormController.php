<?php

namespace App\Http\Controller\DemoAccountForm;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DemoAccountFormController extends AbstractController
{
    /**
     * @Route("demoAccountForm", name="demoaccountform")
     */
    public function addDemoAccountForm() : Response
    {
        return $this->render('core/demoAccountForm/add.html.twig');
    }

}