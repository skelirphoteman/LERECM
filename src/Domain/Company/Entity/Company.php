<?php

namespace App\Domain\Company\Entity;

use App\Domain\Client\Entity\Client;
use App\Domain\Subscription\Entity\Subscription;
use App\Domain\Company\Repository\CompanyRepository;
use App\Domain\Task\Entity\Task;
use App\Domain\UserSupport\Entity\SupportTicket;
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

    /**
     * @ORM\OneToMany(targetEntity=SupportTicket::class, mappedBy="company")
     */
    private $supportTickets;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ape_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avenue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tva_code;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="company")
     */
    private $tasks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->supportTickets = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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

    /**
     * @return Collection|SupportTicket[]
     */
    public function getSupportTickets(): Collection
    {
        return $this->supportTickets;
    }

    public function addSupportTicket(SupportTicket $supportTicket): self
    {
        if (!$this->supportTickets->contains($supportTicket)) {
            $this->supportTickets[] = $supportTicket;
            $supportTicket->setCompany($this);
        }

        return $this;
    }

    public function removeSupportTicket(SupportTicket $supportTicket): self
    {
        if ($this->supportTickets->removeElement($supportTicket)) {
            // set the owning side to null (unless already changed)
            if ($supportTicket->getCompany() === $this) {
                $supportTicket->setCompany(null);
            }
        }

        return $this;
    }

    public function getSiretCode(): ?string
    {
        return $this->siret_code;
    }

    public function setSiretCode(?string $siret_code): self
    {
        $this->siret_code = $siret_code;

        return $this;
    }

    public function getApeCode(): ?string
    {
        return $this->ape_code;
    }

    public function setApeCode(?string $ape_code): self
    {
        $this->ape_code = $ape_code;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvenue(): ?string
    {
        return $this->avenue;
    }

    public function setAvenue(?string $avenue): self
    {
        $this->avenue = $avenue;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTvaCode(): ?string
    {
        return $this->tva_code;
    }

    public function setTvaCode(?string $tva_code): self
    {
        $this->tva_code = $tva_code;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setCompany($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCompany() === $this) {
                $task->setCompany(null);
            }
        }

        return $this;
    }
}
