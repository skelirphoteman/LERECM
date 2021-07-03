<?php

namespace App\Domain\UserSupport\Entity;

use App\Domain\UserSupport\Repository\SupportTicketMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\User\Entity\User;
use \App\Domain\UserSupport\Entity\SupportTicket;

/**
 * @ORM\Entity(repositoryClass=SupportTicketMessageRepository::class)
 */
class SupportTicketMessage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supportTicketMessages")
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity=SupportTicket::class, inversedBy="supportTicketMessages")
     */
    private $support_ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getSupportTicket(): ?SupportTicket
    {
        return $this->support_ticket;
    }

    public function setSupportTicket(?SupportTicket $support_ticket): self
    {
        $this->support_ticket = $support_ticket;

        return $this;
    }
}
