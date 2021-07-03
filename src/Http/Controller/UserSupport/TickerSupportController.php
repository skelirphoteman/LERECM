<?php

namespace App\Http\Controller\UserSupport;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Http\Form\AddSupportTicketType;
use App\Domain\UserSupport\Entity\SupportTicket;
use App\Infrastructure\Security\AccessService;
use App\Infrastructure\UserSupport\SupportTicketService;


/**
 * Class TickerSupportController
 * @package App\Http\Controller\UserSupport
 * @Route("app/ticket/")
 */
class TickerSupportController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }


    /**
     * @Route("add", name="app_ticket_add")
     */
    public function addTicket(Request $request, SupportTicketService $supportTicketService): Response
    {
        $supportTicket = new SupportTicket();
        $supportTicket->setCreatedBy($this->getUser());
        $supportTicket->setCompany($this->getUser()->getCompany());
        $supportTicket->setState(0);
        $formSupportTicket = $this->createForm(AddSupportTicketType::class, $supportTicket);

        $formSupportTicket->handleRequest($request);

        if ($formSupportTicket->isSubmitted() && $formSupportTicket->isValid()) {
            $supportTicket = $formSupportTicket->getData();


            $responseSupportTicket = $supportTicketService->createSupportTicket($supportTicket, $this->getUser());
            if ($responseSupportTicket) {
                $this->addFlash('danger', $responseSupportTicket);
            } else {
                $this->addFlash('success', "Ticket bien créer.");
            }
        }

        return $this->render("app/support/add.html.twig", [
            "form_support_ticket" => $formSupportTicket->createView()
        ]);
    }
}