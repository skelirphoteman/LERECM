<?php

namespace App\Infrastructure\Document;

use App\Infrastructure\FileUploader\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Domain\User\Entity\User;
use App\Domain\Documents\Entity\Quote;
use Symfony\Component\Filesystem\Filesystem;


class QuoteService
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

    private function insertQuote(Quote $quote)
    {
        $em = $this->entityManager;
        $em->persist($quote);
        $em->flush();
    }

    private function removeQuote(Quote $quote)
    {
        $em = $this->entityManager;
        $em->remove($quote);
        $em->flush();
    }

    private function insertFile(UploadedFile $file, Quote $quote, $user) : ?bool
    {
        $fileName = $user->getCompany()->getId() . '-' . $quote->getClient()->getId() . '-' . $quote->getId() . '-' . $quote->getCreatedAt()->format('d-m-Y');
        $path = $user->getCompany()->getId() . '/quote';
        $fileName = $user->getCompany()->getId() . '-' . $quote->getClient()->getId() . '-' . $quote->getCreatedAt()->format('d-m-Y');

        $quote->setFileName($fileName);


        $this->insertQuote($quote);

        $fileName = $quote->getId() . '-' . $fileName;

        $quote->setFilename($fileName);


        if(!$this->fileUploader->upload($file, $path,$fileName))
        {
            $this->removeQuote($quote);
            return false;
        }

        $this->insertQuote($quote);

        return true;
    }

    private function removeFile($quote, $path)
    {
        try {
            $this->filesystem->remove(['symlink', $quote->getDir($path), $quote->getFilename() . '.pdf']);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while delete the pdf at ".$exception->getPath();
        }

    }

    public function addQuote(UploadedFile $file, Quote $quote, User $user) : ?String
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de facture. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        if(!$this->insertFile($file, $quote, $user))
        {
            return "Une erreur c'est produite";
        }

        return null;
    }

    public function deleteQuote(Quote $quote, User $user, $path)
    {
        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de facture. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        $this->removeFile($quote, $path);
        $this->removeQuote($quote);

        return null;
    }
}