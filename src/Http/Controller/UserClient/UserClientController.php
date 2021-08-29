<?php

namespace App\Http\Controller\UserClient;

use App\Domain\Client\Entity\Client;
use App\Domain\Contract\Entity\Contract;
use App\Domain\Documents\Entity\Invoice;
use App\Domain\Documents\Entity\Quote;
use App\Domain\Documents\Entity\File as Doc;
use App\Infrastructure\Security\ClientAccessService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

 /**
  * @Route("/clientapp")
  */
class UserClientController extends AbstractController
{

    private $clientAccessService;

    public function __construct(ClientAccessService $clientAccessService)
    {
        $this->clientAccessService = $clientAccessService;
    }

    /**
     * @Route("/index", name="client_index")
     */
    public function index() : Response
    {
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findOneBy(["id" => $this->getUser()->getUuid()]);

        $invoices = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->findBy(["client" => $client], ["created_at" => "DESC"]);

        $quotes = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->findBy(["client" => $client], ["created_at" => "DESC"]);

        $files = $this->getDoctrine()
            ->getRepository(Doc::class)
            ->findBy(["client" => $client], ["created_at" => "DESC"]);

        $contracts = $this->getDoctrine()
            ->getRepository(Contract::class)
            ->findBy(["client" => $client]);


        return $this->render('client/panel/index.html.twig', [
            'client' => $client,
            'invoices' => $invoices,
            'quotes' => $quotes,
            'files' => $files,
            'contracts' => $contracts,
        ]);
    }

    /**
     * @Route("/invoice/view/{invoice}", name="client_document_invoice_view")
     */
    public function viewInvoice(Invoice $invoice = null) : Response
    {

        if(!$invoice){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->clientAccessService->clientAccess($invoice->getClient());
        return $this->file($invoice->getDir($this->getParameter('kernel.project_dir')), 'Facture ' . $invoice->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/quote/view/{quote}", name="client_document_quote_view")
     */
    public function viewQuote(Quote $quote = null) : Response
    {

        if(!$quote){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->clientAccessService->clientAccess($quote->getClient());
        return $this->file($quote->getDir($this->getParameter('kernel.project_dir')), 'Devis ' . $quote->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/file/view/{file}", name="client_document_file_view")
     */
    public function viewFile(Doc $file = null) : Response
    {

        if(!$file){
            throw $this->createNotFoundException('Aucun Document trouvé');
        }

        $this->clientAccessService->clientAccess($file->getClient());
        return $this->file($file->getDir($this->getParameter('kernel.project_dir')), 'Document ' . $file->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }
}