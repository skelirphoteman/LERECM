<?php

namespace App\Domain\Contract\Entity;

use App\Domain\Contract\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Client\Entity\Client;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=ContractRepository::class)
 */
class Contract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="contracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="integer")
     */
    private $contract_type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $next_payment_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getContractType(): ?int
    {
        return $this->contract_type;
    }

    public function setContractType(int $contract_type): self
    {
        $this->contract_type = $contract_type;

        return $this;
    }

    public function getStartAt(): ?\Datetime
    {
        return $this->start_at;
    }

    public function setStartAt(\Datetime $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\Datetime
    {
        return $this->end_at;
    }

    public function setEndAt(\Datetime $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getNextPaymentAt(): ?\Datetime
    {
        return $this->next_payment_at;
    }

    public function setNextPaymentAt(?\Datetime $next_payment_at): self
    {
        $this->next_payment_at = clone $next_payment_at;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getContractTypeString(): String
    {
        if($this->contract_type == 0) return "Mensuel";
        if($this->contract_type == 1) return "Trimestriel";
        if($this->contract_type == 2) return "Semestriel";
        if($this->contract_type == 3) return "Annuel";
    }

    public function nextPaymentIsValid() : bool
    {
        if ($this->next_payment_at >= $this->end_at) return false;

        return true;
    }
}
