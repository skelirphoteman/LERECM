<?php

namespace App\Domain\Documents\Entity;

use App\Domain\Contract\Entity\Contract;
use App\Domain\Documents\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Documents\Entity\Document;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice extends Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * 0: en attente de paiement
     * 1: payé
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

    /**
     * @ORM\ManyToOne(targetEntity=Contract::class, inversedBy="invoices")
     */
    private $contract;

    public function __construct()
    {
        parent::__construct();
        $this->path = 'invoice/';
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
            '/uploads/documents/' .
            $this->getClient()->getCompany()->getId() .
            '/invoice/' .
            $this->getFilename() .
            '.pdf';
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
