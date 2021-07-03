<?php

namespace App\Http\Admin\Controller\UserSupport;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\UserSupport\Entity\SupportTicket;
use App\Domain\UserSupport\Entity\SupportTicketMessage;
use App\Http\Admin\Form\AddSupportTicketMessageType;
use App\Infrastructure\UserSupport\SupportTicketService;

/**
 *@Route("admin/support/ticket")
 */
class SupportTicketAdminController extends AbstractController
{

    /**
     * @Route("list", name="admin_support_ticket_list")
     */
    public function listSupportTicket() : Response
    {
        $supportTickets = $this->getDoctrine()
            ->getRepository(SupportTicket::class)
            ->findAll(["state" => 0, "state" => 1], ["createdAt" => "DESC"]);

        return $this->render("admin/support/user/list.html.twig", [
            'supportTickets' => $supportTickets
        ]);
    }

    /**
     * @Route("edit/{ticket}", name="admin_support_ticket_edit")
     */
    public function editListSupportTicket(SupportTicket $ticket = null, Request $request, SupportTicketService $supportTicketService) : Response
    {
        if($ticket == null){
            throw $this->createNotFoundException('Aucun utilisateur trouvé');
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

        return $this->render('admin/support/user/edit.html.twig', [
            'support_ticket' => $ticket,
            'form_support_ticket_message' => $formSupportTicketMessage->createView(),
        ]);
    }

}