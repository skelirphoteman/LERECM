<?php

namespace App\Http\Controller\DemoAccountForm;


use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use App\Http\Form\AddDemoAccountFormType;
use App\Infrastructure\Form\DemoAccountFormService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function addDemoAccountForm(Request $request, DemoAccountFormService $demoAccountFormService) : Response
    {
        $demoAccountForm = new DemoAccountForm();

        $formDemoAccountForm = $this->createForm(AddDemoAccountFormType::class, $demoAccountForm);

        $formDemoAccountForm->handleRequest($request);

        if($formDemoAccountForm->isSubmitted() && $formDemoAccountForm->isValid()){
            $demoAccountForm = $formDemoAccountForm->getData();


            $serviceResponse = $demoAccountFormService->addDemoAccountForm($demoAccountForm, $_SERVER['REMOTE_ADDR']);

            die($serviceResponse);

        }

        return $this->render('core/demoAccountForm/add.html.twig', [
            'form_demoaccountform' => $formDemoAccountForm->createView(),
        ]);
    }

}