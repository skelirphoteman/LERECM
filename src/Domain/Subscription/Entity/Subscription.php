<?php

namespace App\Domain\Subscription\Entity;

use App\Domain\Subscription\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Company\Entity\Company;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscription_panel_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @ORM\Column(type="json")
     */
    private $subscription_access = [];

    /**
     * @ORM\OneToOne(targetEntity=Company::class, inversedBy="subscription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionPanelId(): ?int
    {
        return $this->subscription_panel_id;
    }

    public function setSubscriptionPanelId(int $subscription_panel_id): self
    {
        $this->subscription_panel_id = $subscription_panel_id;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getSubscriptionAccess(): ?array
    {
        return $this->subscription_access;
    }

    public function setSubscriptionAccess(array $subscription_access): self
    {
        $this->subscription_access = $subscription_access;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function subIsValid() : bool
    {
        if($this->end_at > (new \DateTime('now'))){
            return true;
        }

        return false;
    }
}
