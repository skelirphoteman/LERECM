<?php

namespace App\Domain\Documents\Entity;

use App\Domain\Documents\Repository\QuoteRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Documents\Entity\Document;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 */
class Quote extends Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * 0: proposé
     * 1: signé
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    public function __construct()
    {
        parent::__construct();
        $this->path = 'quote/';
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
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
            '/quote/' .
            $this->getFilename() .
            '.pdf';
    }
}
