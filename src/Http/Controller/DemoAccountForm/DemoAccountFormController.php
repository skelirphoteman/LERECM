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

            $demoAccountForm->setUserIp($_SERVER['REMOTE_ADDR']);

            $serviceResponse = $demoAccountFormService->addDemoAccountForm($demoAccountForm);

            if($serviceResponse){
                $this->addFlash('danger', $serviceResponse);
            }
            else{
                $this->addFlash('success', "Votre demande a bien été envoyé.");
                return $this->redirectToRoute("core");
            }

        }

        return $this->render('core/demoAccountForm/add.html.twig', [
            'form_demoaccountform' => $formDemoAccountForm->createView(),
        ]);
    }
}