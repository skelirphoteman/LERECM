<?php

namespace App\Domain\Task\Entity;

use App\Domain\Task\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Company\Entity\Company;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="tasks")
     */
    private $company;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
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

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTableClass() : String
    {
        $now = new \DateTime('now');
        $now->settime(0,0);
        if($this->getEndAt() > $now) return "table-success";
        if($this->getEndAt() == $now) return "table-warning";
        if($this->getEndAt() < $now) return "table-danger";
    }
}
