<?php

namespace App\Infrastructure\Document;

use App\Infrastructure\FileUploader\FileUploader;
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

    public function __construct(EntityManagerInterface $entityManager,
                                FileUploader $fileUploader,
                                Filesystem $filesystem)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->filesystem = $filesystem;
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

    private function insertFile(UploadedFile $file, File $doc, $user) : ?bool
    {
        $fileName = $user->getCompany()->getId() . '-' . $doc->getClient()->getId() . '-' . $doc->getId() . '-' . $doc->getCreatedAt()->format('d-m-Y');
        $path = $user->getCompany()->getId() . '/doc';
        $fileName = $user->getCompany()->getId() . '-' . $doc->getClient()->getId() . '-' . $doc->getCreatedAt()->format('d-m-Y');

        $doc->setFileName($fileName);


        $this->insertInvoice($doc);

        $fileName = $doc->getId() . '-' . $fileName;

        $doc->setFilename($fileName);


        if(!$this->fileUploader->upload($file, $path,$fileName))
        {
            $this->removeInvoice($doc);
            return false;
        }

        $this->insertInvoice($doc);

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

        if(!$this->insertFile($file, $doc, $user))
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