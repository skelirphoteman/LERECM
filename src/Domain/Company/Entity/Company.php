<?php

namespace App\Domain\Company\Entity;

use App\Domain\Client\Entity\Client;
use App\Domain\Subscription\Entity\Subscription;
use App\Domain\Company\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\User\Entity\User;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, mappedBy="company", cascade={"persist", "remove"})
     */
    private $subscription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_panel_id;

    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="company")
     */
    private $clients;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): self
    {
        // set the owning side of the relation if necessary
        if ($subscription->getCompany() !== $this) {
            $subscription->setCompany($this);
        }

        $this->subscription = $subscription;

        return $this;
    }

    public function getUserPanelId(): ?int
    {
        return $this->user_panel_id;
    }

    public function setUserPanelId(int $user_panel_id): self
    {
        $this->user_panel_id = $user_panel_id;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setCompany($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCompany() === $this) {
                $client->setCompany(null);
            }
        }

        return $this;
    }
}
