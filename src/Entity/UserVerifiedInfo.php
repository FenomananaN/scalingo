<?php

namespace App\Entity;

use App\Repository\UserVerifiedInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserVerifiedInfoRepository::class)]
class UserVerifiedInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCIN = null;

    #[ORM\Column(length: 255)]
    private ?string $rectophoto = null;

    #[ORM\Column(length: 255)]
    private ?string $versoPhoto = null;

    #[ORM\Column(length: 255)]
    private ?string $selfieAvecCIN = null;

    #[ORM\Column( nullable: true)]
    private ?\DateTimeInterface $verifiedAt = null;

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNumeroCIN(): ?string
    {
        return $this->numeroCIN;
    }

    public function setNumeroCIN(string $numeroCIN): self
    {
        $this->numeroCIN = $numeroCIN;

        return $this;
    }

    public function getRectophoto(): ?string
    {
        return $this->rectophoto;
    }

    public function setRectophoto(string $rectophoto): self
    {
        $this->rectophoto = $rectophoto;

        return $this;
    }

    public function getVersoPhoto(): ?string
    {
        return $this->versoPhoto;
    }

    public function setVersoPhoto(string $versoPhoto): self
    {
        $this->versoPhoto = $versoPhoto;

        return $this;
    }

    public function getSelfieAvecCIN(): ?string
    {
        return $this->selfieAvecCIN;
    }

    public function setSelfieAvecCIN(string $selfieAvecCIN): self
    {
        $this->selfieAvecCIN = $selfieAvecCIN;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(\DateTimeInterface $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }


}
