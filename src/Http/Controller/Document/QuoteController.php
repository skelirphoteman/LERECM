<?php

namespace App\Http\Controller\Document;

use App\Domain\Documents\Entity\Quote;
use App\Domain\Client\Entity\Client;
use App\Http\Form\AddQuoteType;
use App\Infrastructure\Client\ClientService;
use App\Infrastructure\User\UserClientService;
use App\Infrastructure\Document\QuoteService;
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
 * @Route("/app/document/quote")
 */
class QuoteController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }
    
    /**
     * @Route("/add/{id}", name="app_document_quote_add")
     */
    public function addQuote(Client $id = null,
                               Request $request,
                               QuoteService $quoteService) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $this->accessService->companyClientAccess($client);

        $quote = new Quote();
        $quote->setClient($client);

        $formQuote = $this->createForm(AddQuoteType::class, $quote);

        $formQuote->handleRequest($request);

        if ($formQuote->isSubmitted() && $formQuote->isValid()) {
            $quoteFile = $formQuote->get('filename')->getData();
            $quote = $formQuote->getData();

            if ($quoteFile) {
                $quotFileName = $quoteService->addQuote($quoteFile, $quote, $this->getUser());
                if($quotFileName)
                {
                    $this->addFlash('danger', $quotFileName);
                }else
                {
                    $this->addFlash('success', "Devis bien enregistré.");
                    return $this->redirectToRoute("app_client_edit", ["id" => $client->getId()]);
                }
            }

        }

        return $this->render('app/client/document/quote/add.html.twig', [
            'form_quote' => $formQuote->createView(),
            'client' => $client
        ]);
    }

    /**
     * @Route("/view/{quote}", name="app_document_quote_view")
     */
    public function viewQuote(Quote $quote = null) : Response
    {

        if(!$quote){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->accessService->companyClientAccess($quote->getClient());

        $file = new File($quote->getDir($this->getParameter('kernel.project_dir')));

        return $this->file($quote->getDir($this->getParameter('kernel.project_dir')), 'Devis ' . $quote->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/delete/{quote}", name="app_document_quote_delete")
     */
    public function deleteInvoice(Quote $quote = null,QuoteService $quoteService) : Response
    {

        if(!$quote){
            throw $this->createNotFoundException('Aucune facture trouvé');
        }

        $this->accessService->companyClientAccess($quote->getClient());

        $delete = $quoteService->deleteQuote($quote, $this->getUser(), $this->getParameter('kernel.project_dir'));

        if($delete)
        {
            $this->addFlash('danger', $delete);
        }else
        {
            $this->addFlash('success', "La facture a bien été supprimer.");
        }

        return $this->redirectToRoute('app_client_edit', ["id" => $quote->getClient()->getId()]);
    }
}