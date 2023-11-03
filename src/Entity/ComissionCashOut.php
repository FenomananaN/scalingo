<?php

namespace App\Entity;

use App\Repository\ComissionCashOutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComissionCashOutRepository::class)]
class ComissionCashOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comissionCashOuts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $MGAValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $cashOutAt = null;

    #[ORM\Column]
    private ?bool $beingProcessed = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column]
    private ?bool $success = null;


    #[ORM\Column]
    private ?bool $failed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMGAValue(): ?string
    {
        return $this->MGAValue;
    }

    public function setMGAValue(string $MGAValue): static
    {
        $this->MGAValue = $MGAValue;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCashOutAt(): ?\DateTimeInterface
    {
        return $this->cashOutAt;
    }

    public function setCashOutAt(\DateTimeInterface $cashOutAt): static
    {
        $this->cashOutAt = $cashOutAt;

        return $this;
    }

    public function isBeingProcessed(): ?bool
    {
        return $this->beingProcessed;
    }

    public function setBeingProcessed(bool $beingProcessed): static
    {
        $this->beingProcessed = $beingProcessed;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): static
    {
        $this->success = $success;

        return $this;
    }

    public function isFailed(): ?bool
    {
        return $this->failed;
    }

    public function setFailed(bool $failed): static
    {
        $this->failed = $failed;

        return $this;
    }
}
