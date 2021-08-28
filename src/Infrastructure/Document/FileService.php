<?php

namespace App\Infrastructure\Document;

use App\Infrastructure\FileUploader\FileUploader;
use App\Infrastructure\Security\DocumentSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Domain\User\Entity\User;
use App\Domain\Documents\Entity\File;
use Symfony\Component\Filesystem\Filesystem;


class FileService
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

    private function insertInvoice(File $file)
    {
        $em = $this->entityManager;
        $em->persist($file);
        $em->flush();
    }

    private function removeDoc(File $file)
    {
        $em = $this->entityManager;
        $em->remove($file);
        $em->flush();
    }

    public function getFilename(User $user, File $doc) : String
    {
        $fileName = $user->getCompany()->getId() . '-' . $doc->getClient()->getId() . '-' . $doc->getId() . '-' . $doc->getCreatedAt()->format('d-m-Y');
        return $user->getCompany()->getId() . '-' . $doc->getClient()->getId() . '-' . $doc->getCreatedAt()->format('d-m-Y');
    }

    /**
     * @param User $user
     * @return String
     *
     * Return path for file
     */
    public function getPath(User $user): String
    {
        return $user->getCompany()->getId() . '/doc';
    }

    /**
     * @param UploadedFile $file
     * @param File $doc
     * @param $user
     * @return bool|null
     *
     * Prepare and insert new file
     */
    private function insertFile(File $doc, $user, ?UploadedFile $file) : ?bool
    {
        $fileName = $this->getFilename($user, $doc);

        $path = $this->getPath($user);

        $doc->setFileName($fileName);


        $this->insertInvoice($doc);

        $fileName = $doc->getId() . '-' . $fileName;

        $doc->setFilename($fileName);


        if(!$this->fileUploader->upload($file, $path, $fileName))
        {
            $this->removeInvoice($doc);
            return false;
        }

        $this->insertInvoice($doc);

        return true;
    }

    /**
     * @param UploadedFile $file
     * @param File $doc
     * @param $user
     * @return bool|null
     *
     * Prepare and update file
     */
    private function updateFile(File $doc, $user, ?UploadedFile $file = null) : ?bool
    {

        $this->insertInvoice($doc);


        if($file != null) {
            $path = $this->getPath($user);


            $fileName = $doc->getId() . '-' . $doc->getFileName();

            $doc->setFilename($fileName);


            if (!$this->fileUploader->upload($file, $path, $fileName)) {
                $this->removeInvoice($doc);
                return false;
            }

            $this->insertInvoice($doc);
        }

        return true;
    }

    private function removeFile($file, $path)
    {
        try {
            $this->filesystem->remove(['symlink', $file->getDir($path), $file->getFilename() . '.pdf']);
        } catch (IOExceptionInterface $exception) {
            echo "Une erreur est survenue lors de la suppression du docuement, ".$exception->getPath();
        }

    }

    public function addFile(UploadedFile $file, File $doc, User $user) : ?String
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de docuement. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        if($doc->getContract() != null) // check if contract and client are valid
        {
            if(!$this->documentSecurity->contractIsValid($doc->getContract(), $doc->getClient()->getId())){
                return "Une erreur c'est produite. La liaison entre le contrat et la facture ne peut pas se faire.";
            }
        }

        if(!$this->insertFile($doc, $user, $file))
        {
            return "Une erreur c'est produite";
        }

        return null;
    }

    public function editFile(File $doc, User $user, ?UploadedFile $file = null) : ?String
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de docuement. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        if($doc->getContract() != null) // check if contract and client are valid
        {
            if(!$this->documentSecurity->contractIsValid($doc->getContract(), $doc->getClient()->getId())){
                return "Une erreur c'est produite. La liaison entre le contrat et la facture ne peut pas se faire.";
            }
        }

        if(!$this->updateFile($doc, $user, $file))
        {
            return "Une erreur c'est produite";
        }

        return null;
    }

    public function deleteFile(File $file, User $user, $path)
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de document. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        $this->removeFile($file, $path);
        $this->removeDoc($file);

        return null;
    }
}