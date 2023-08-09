<?php

namespace App\Entity;

use App\Repository\UserVerifiedInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserVerifiedInfoRepository::class)]
class UserVerifiedInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userVerifiedInfo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCIN = null;

    #[ORM\Column(length: 255)]
    private ?string $versoPhoto = null;

    #[ORM\Column(length: 255)]
    private ?string $selfieAvecCIN = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $verifiedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $rectoPhoto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getNumeroCIN(): ?string
    {
        return $this->numeroCIN;
    }

    public function setNumeroCIN(string $numeroCIN): static
    {
        $this->numeroCIN = $numeroCIN;

        return $this;
    }

    public function getVersoPhoto(): ?string
    {
        return $this->versoPhoto;
    }

    public function setVersoPhoto(string $versoPhoto): static
    {
        $this->versoPhoto = $versoPhoto;

        return $this;
    }

    public function getSelfieAvecCIN(): ?string
    {
        return $this->selfieAvecCIN;
    }

    public function setSelfieAvecCIN(string $selfieAvecCIN): static
    {
        $this->selfieAvecCIN = $selfieAvecCIN;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(\DateTimeInterface $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getRectoPhoto(): ?string
    {
        return $this->rectoPhoto;
    }

    public function setRectoPhoto(string $rectoPhoto): static
    {
        $this->rectoPhoto = $rectoPhoto;

        return $this;
    }
}
