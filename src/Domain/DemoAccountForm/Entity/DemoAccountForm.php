<?php

namespace App\Domain\DemoAccountForm\Entity;

use App\Domain\DemoAccountForm\Repository\DemoAccountFormRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemoAccountFormRepository::class)
 */
class DemoAccountForm
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
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $find_by;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $informations;

    /**
     * @ORM\Column(type="datetime")
     */
    private $post_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_ip;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFindBy(): ?string
    {
        return $this->find_by;
    }

    public function setFindBy(?string $find_by): self
    {
        $this->find_by = $find_by;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getInformations(): ?string
    {
        return $this->informations;
    }

    public function setInformations(?string $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    public function getPostAt(): ?\DateTimeInterface
    {
        return $this->post_at;
    }

    public function setPostAt(\DateTimeInterface $post_at): self
    {
        $this->post_at = $post_at;

        return $this;
    }

    public function getUserIp(): ?string
    {
        return $this->user_ip;
    }

    public function setUserIp(string $user_ip): self
    {
        $this->user_ip = $user_ip;

        return $this;
    }
}
