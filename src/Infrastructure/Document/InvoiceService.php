<?php

namespace App\Infrastructure\Document;

use App\Infrastructure\FileUploader\FileUploader;
use App\Infrastructure\Security\DocumentSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Domain\User\Entity\User;
use App\Domain\Documents\Entity\Invoice;
use Symfony\Component\Filesystem\Filesystem;


class InvoiceService
{
    private $entityManager;

    private $fileUploader;

    private $filesystem;

    private $documentSecurity;

    public function __construct(EntityManagerInterface $entityManager,
                                FileUploader $fileUploader,
                                Filesystem $filesystem,
                                DocumentSecurity $documentSecurity)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->filesystem = $filesystem;
        $this->documentSecurity = $documentSecurity;
    }

    private function accessIsValid(User $user) : bool
    {
        if(!$user->getCompany()->getSubscription()->subIsValid()) return false;

        return true;
    }

    private function insertInvoice(Invoice $invoice)
    {
        $em = $this->entityManager;
        $em->persist($invoice);
        $em->flush();
    }

    private function removeInvoice(Invoice $invoice)
    {
        $em = $this->entityManager;
        $em->remove($invoice);
        $em->flush();
    }

    private function insertFile(UploadedFile $file, Invoice $invoice, $user) : ?bool
    {
        $fileName = $user->getCompany()->getId() . '-' . $invoice->getClient()->getId() . '-' . $invoice->getId() . '-' . $invoice->getCreatedAt()->format('d-m-Y');
        $path = $user->getCompany()->getId() . '/invoice';
        $fileName = $user->getCompany()->getId() . '-' . $invoice->getClient()->getId() . '-' . $invoice->getCreatedAt()->format('d-m-Y');

        $invoice->setFileName($fileName);


        $this->insertInvoice($invoice);

        $fileName = $invoice->getId() . '-' . $fileName;

        $invoice->setFilename($fileName);


        if(!$this->fileUploader->upload($file, $path,$fileName))
        {
            $this->removeInvoice($invoice);
            return false;
        }

        $this->insertInvoice($invoice);

        return true;
    }

    private function removeFile($invoice, $path)
    {
        try {
            $this->filesystem->remove(['symlink', $invoice->getDir($path), $invoice->getFilename() . '.pdf']);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while delete the pdf at ".$exception->getPath();
        }

    }

    public function addInvoice(UploadedFile $file, Invoice $invoice, User $user) : ?String
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de facture. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        if($invoice->getContract() != null)
        {
            if(!$this->documentSecurity->contractIsValid($invoice->getContract(), $invoice->getClient()->getId())){
                return "Une erreur c'est produite. La liaison entre le contrat et la facture ne peut pas se faire.";
            }
        }

        if(!$this->insertFile($file, $invoice, $user))
        {
            return "Une erreur c'est produite";
        }

        return null;
    }

    public function deleteInvoice(Invoice $invoice, User $user, $path)
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de facture. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        $this->removeFile($invoice, $path);
        $this->removeInvoice($invoice);

        return null;
    }
}