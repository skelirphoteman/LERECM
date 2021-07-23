<?php

namespace App\Http\Controller\Document;

use App\Domain\Documents\Entity\File as Doc;
use App\Domain\Client\Entity\Client;
use App\Domain\UserClient\Entity\UserClient;
use App\Http\Form\AddClientType;
use App\Http\Form\AddFileType;
use App\Infrastructure\Client\ClientService;
use App\Infrastructure\User\UserClientService;
use App\Infrastructure\Document\FileService;
use App\Infrastructure\Security\AccessService;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Class FileController
 * @package App\Http\Controller\Document
 * @Route("/app/document/file")
 */
class FileController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }
    
    /**
     * @Route("/add/{id}", name="app_document_file_add")
     */
    public function addFile(Client $id = null, 
                               Request $request, 
                               FileService $fileService) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $this->accessService->companyClientAccess($client);

        $file = new Doc();
        $file->setClient($client);

        $formFile = $this->createForm(AddFileType::class, $file);

        $formFile->handleRequest($request);

        if ($formFile->isSubmitted() && $formFile->isValid()) {
            $fileFile = $formFile->get('filename')->getData();
            $file = $formFile->getData();

            if ($formFile) {
                $fileName = $fileService->addFile($fileFile, $file, $this->getUser());
                if($fileName)
                {
                    $this->addFlash('danger', $fileName);
                }else
                {
                    $this->addFlash('success', "Document bien enregistré.");
                    return $this->redirectToRoute("app_client_edit", ["id" => $client->getId()]);
                }
            }

        }

        return $this->render('app/client/document/file/add.html.twig', [
            'form_file' => $formFile->createView(),
            'client' => $client
        ]);
    }

    /**
     * @Route("/view/{file}", name="app_document_file_view")
     */
    public function viewFile(Doc $file = null) : Response
    {

        if(!$file){
            throw $this->createNotFoundException('Aucun document trouvé');
        }

        $this->accessService->companyClientAccess($file->getClient());

        $doc = new File($file->getDir($this->getParameter('kernel.project_dir')));

        return $this->file($file->getDir($this->getParameter('kernel.project_dir')), 'Facture ' . $file->getFilename() . '.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/delete/{file}", name="app_document_file_delete")
     */
    public function deleteFile(Doc $file = null,FileService $fileService) : Response
    {

        if(!$file){
            throw $this->createNotFoundException('Aucun Document trouvé');
        }

        $this->accessService->companyClientAccess($file->getClient());

        $delete = $fileService->deleteFile($file, $this->getUser(), $this->getParameter('kernel.project_dir'));

        if($delete)
        {
            $this->addFlash('danger', $delete);
        }else
        {
            $this->addFlash('success', "Le Document a bien été supprimer.");
        }

        return $this->redirectToRoute('app_client_edit', ["id" => $file->getClient()->getId()]);
    }
}