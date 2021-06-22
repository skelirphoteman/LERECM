<?php

namespace App\Domain\Documents\Entity;

use App\Domain\Documents\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Documents\Entity\Invoice;
use App\Domain\Documents\Entity\Quote;
use \App\Domain\Client\Entity\Client;
use \App\Domain\Company\Entity\Company;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "Invoice" = "Invoice",
 *     "Quote" = "Quote",
 * })
 */
abstract class Document
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function __construct()
    {
        $this->created_at = new \Datetime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->filename;
    }

    public function setFileName(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedFor(): ?User
    {
        return $this->created_for;
    }

    public function setCreatedFor(?User $created_for): self
    {
        $this->created_for = $created_for;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCompany() : Company
    {
        return $this->client->getCompany();
    }
}
