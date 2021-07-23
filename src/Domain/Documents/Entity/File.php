<?php

namespace App\Domain\Documents\Entity;

use App\Domain\Documents\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Documents\Entity\Document;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File extends Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    public function __construct()
    {
        parent::__construct();
        $this->path = 'doc/';
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPath(): ?String
    {
        return $this->path;
    }

    public function getDir($projectDir) : String
    {
        return $projectDir .
            '/public/uploads/documents/' .
            $this->getClient()->getCompany()->getId() .
            '/doc/' .
            $this->getFilename() .
            '.pdf';
    }
}
