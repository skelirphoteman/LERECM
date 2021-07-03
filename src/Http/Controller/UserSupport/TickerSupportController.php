<?php

namespace App\Http\Controller\UserSupport;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Http\Form\AddSupportTicketType;
use App\Domain\UserSupport\Entity\SupportTicket;
use App\Domain\UserSupport\Entity\SupportTicketMessage;
use App\Infrastructure\Security\AccessService;
use App\Infrastructure\UserSupport\SupportTicketService;
use App\Http\Form\AddSupportTicketMessageType;


/**
 * Class TickerSupportController
 * @package App\Http\Controller\UserSupport
 * @Route("app/support/ticket/")
 */
class TickerSupportController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }


    /**
     * @Route("add", name="app_support_ticket_add")
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

    /**
     * @Route("edit/{ticket}", name="app_support_ticket_edit")
     */
    public function editSupportTicket(SupportTicket $ticket = null, Request $request, SupportTicketService $supportTicketService) : Response
    {
        if($ticket == null){
            throw $this->createNotFoundException('Aucun ticket trouvé');
        }

        $supportTicketMessage = new SupportTicketMessage();
        $supportTicketMessage->setCreatedBy($this->getUser());
        $supportTicketMessage->setSupportTicket($ticket);

        $formSupportTicketMessage = $this->createForm(AddSupportTicketMessageType::class, $supportTicketMessage);


        $formSupportTicketMessage->handleRequest($request);

        if ($formSupportTicketMessage->isSubmitted() && $formSupportTicketMessage->isValid()) {
            $supportTicketMessage = $formSupportTicketMessage->getData();


            $responseSupportTicketMessage = $supportTicketService->createSupportTicketMessage($supportTicketMessage);
            if ($responseSupportTicketMessage) {
                $this->addFlash('danger', $responseSupportTicketMessage);
            } else {
                $this->addFlash('success', "Ticket bien créer.");
            }
        }

        return $this->render('app/support/edit.html.twig', [
            'support_ticket' => $ticket,
            'form_support_ticket_message' => $formSupportTicketMessage->createView(),
        ]);
    }
}