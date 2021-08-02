<?php

namespace App\Domain\Contract\Entity;

use App\Domain\Contract\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Client\Entity\Client;

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
     * @ORM\Column(type="datetime_immutable")
     */
    private $start_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $end_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $next_payment_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

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

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeImmutable $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeImmutable $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getNextPaymentAt(): ?\DateTimeImmutable
    {
        return $this->next_payment_at;
    }

    public function setNextPaymentAt(?\DateTimeImmutable $next_payment_at): self
    {
        $this->next_payment_at = $next_payment_at;

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
}
