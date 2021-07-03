<?php

namespace App\Domain\UserSupport\Entity;

use App\Domain\UserSupport\Repository\SupportTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Company\Entity\Company;
use \App\Domain\User\Entity\User;
use PhpParser\Node\Scalar\String_;

/**
 * @ORM\Entity(repositoryClass=SupportTicketRepository::class)
 */
class SupportTicket
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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * 0: en attente de paiement
     * 1: payé
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * 0: en attente de paiement
     * 1: payé
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="supportTickets")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supportTickets")
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=SupportTicketMessage::class, mappedBy="support_ticket")
     */
    private $supportTicketMessages;

    public function __construct()
    {
        $this->supportTicketMessages = new ArrayCollection();
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeString(): String
    {
        if($this->type == 1) return 'Renseignement sur mon abonnement';
        if($this->type == 2) return 'Rapport d\'un bug';
        if($this->type == 3) return 'Demande d\'amélioration';
        if($this->type == 4) return 'Demande de création d\'un nouveau service sur L.E.R.E.C.M';
        return 'Autres';
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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

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

    public function getClassStatus() : String
    {
        if($this->state == 0)
        {
            return "alert alert-success";
        }else if($this->state == 1)
        {
            return "alert alert-warning";
        }else
        {
            return "alert alert-danger";
        }
    }

    /**
     * @return Collection|SupportTicketMessage[]
     */
    public function getSupportTicketMessages(): Collection
    {
        return $this->supportTicketMessages;
    }

    public function addSupportTicketMessage(SupportTicketMessage $supportTicketMessage): self
    {
        if (!$this->supportTicketMessages->contains($supportTicketMessage)) {
            $this->supportTicketMessages[] = $supportTicketMessage;
            $supportTicketMessage->setSupportTicket($this);
        }

        return $this;
    }

    public function removeSupportTicketMessage(SupportTicketMessage $supportTicketMessage): self
    {
        if ($this->supportTicketMessages->removeElement($supportTicketMessage)) {
            // set the owning side to null (unless already changed)
            if ($supportTicketMessage->getSupportTicket() === $this) {
                $supportTicketMessage->setSupportTicket(null);
            }
        }

        return $this;
    }
}
