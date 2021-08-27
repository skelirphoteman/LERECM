<?php

namespace App\Http\Controller\Document;

use App\Domain\Documents\Entity\Invoice;
use App\Domain\Client\Entity\Client;
use App\Domain\UserClient\Entity\UserClient;
use App\Http\Form\AddClientType;
use App\Http\Form\AddInvoiceType;
use App\Http\Form\EditInvoiceType;
use App\Infrastructure\Client\ClientService;
use App\Infrastructure\User\UserClientService;
use App\Infrastructure\Document\InvoiceService;
use App\Infrastructure\Security\AccessService;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Class InvoiceController
 * @package App\Http\Controller\Document
 * @Route("/app/document/invoice")
 */
class InvoiceController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }
    
    /**
     * @Route("/add/{id}", name="app_document_invoice_add")
     */
    public function addInvoice(Client $id = null, 
                               Request $request, 
                               InvoiceService $invoiceService) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $this->accessService->companyClientAccess($client);

        $invoice = new Invoice();
        $invoice->setClient($client);

        $formInvoice = $this->createForm(AddInvoiceType::class, $invoice, ['client_id' => $client->getId()]);

        $formInvoice->handleRequest($request);

        if ($formInvoice->isSubmitted() && $formInvoice->isValid()) {
            $invoiceFile = $formInvoice->get('filename')->getData();
            $invoice = $formInvoice->getData();

            if ($invoiceFile) {
                $InvoiceFileName = $invoiceService->addInvoice($invoiceFile, $invoice, $this->getUser());
                if($InvoiceFileName)
                {
                    $this->addFlash('danger', $InvoiceFileName);
                }else
                {
                    $this->addFlash('success', "Facture bien enregistré.");
                    return $this->redirectToRoute("app_client_edit", ["id" => $client->getId()]);
                }
            }

        }

        return $this->render('app/client/document/invoice/add.html.twig', [
            'form_invoice' => $formInvoice->createView(),
            'client' => $client
        ]);
    }

    /**
     * @Route("/edit/{invoice}", name="app_document_invoice_edit")
     */
    public function editInvoice(Invoice $invoice = null,
                               Request $request,
                               InvoiceService $invoiceService) : Response
    {
        if(!$invoice){ // check if invoice exist
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->accessService->companyClientAccess($invoice->getClient()); // if is client's company

        $formInvoice = $this->createForm(EditInvoiceType::class, $invoice, ['client_id' => $invoice->getClient()->getId()]); // create form

        $formInvoice->handleRequest($request);

        if ($formInvoice->isSubmitted() && $formInvoice->isValid()) {
            $invoiceFile = $formInvoice->get('filename')->getData();
            $invoice = $formInvoice->getData();

            $InvoiceFileName = $invoiceService->editInvoice($invoice, $this->getUser(), $invoiceFile);
            if($InvoiceFileName)
            {
                $this->addFlash('danger', $InvoiceFileName);
            }else
            {
                $this->addFlash('success', "Facture bien enregistré.");
                return $this->redirectToRoute("app_client_edit", ["id" => $invoice->getClient()->getId()]);
            }


        }

        return $this->render('app/client/document/invoice/edit.html.twig', [
            'form_invoice' => $formInvoice->createView(),
            'client' => $invoice->getClient(),
            'contract' => $invoice->getContract(),
            'invoice_id' => $invoice->getId(),
        ]);
    }

    /**
     * @Route("/view/{invoice}", name="app_document_invoice_view")
     */
    public function viewInvoice(Invoice $invoice = null) : Response
    {

        if(!$invoice){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->accessService->companyClientAccess($invoice->getClient());

        $file = new File($invoice->getDir($this->getParameter('kernel.project_dir')));

        return $this->file($invoice->getDir($this->getParameter('kernel.project_dir')), 'Facture ' . $invoice->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/delete/{invoice}", name="app_document_invoice_delete")
     */
    public function deleteInvoice(Invoice $invoice = null,InvoiceService $invoiceService) : Response
    {

        if(!$invoice){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->accessService->companyClientAccess($invoice->getClient());

        $delete = $invoiceService->deleteInvoice($invoice, $this->getUser(), $this->getParameter('kernel.project_dir'));

        if($delete)
        {
            $this->addFlash('danger', $delete);
        }else
        {
            $this->addFlash('success', "La facture a bien été supprimer.");
        }

        return $this->redirectToRoute('app_client_edit', ["id" => $invoice->getClient()->getId()]);
    }
}