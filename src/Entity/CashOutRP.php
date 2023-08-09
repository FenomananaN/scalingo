<?php

namespace App\Entity;

use App\Repository\CashOutRPRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashOutRPRepository::class)]
class CashOutRP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cashOutRPs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\Column(length: 255)]
    private ?string $RP = null;

    #[ORM\Column(length: 255)]
    private ?string $RPRate = null;

    #[ORM\Column(length: 255)]
    private ?string $MGAValue = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $beingProcessed = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cashoutSuccessed = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cashoutFailed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $cashoutAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getRP(): ?string
    {
        return $this->RP;
    }

    public function setRP(string $RP): static
    {
        $this->RP = $RP;

        return $this;
    }

    public function getRPRate(): ?string
    {
        return $this->RPRate;
    }

    public function setRPRate(string $RPRate): static
    {
        $this->RPRate = $RPRate;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function isCashoutSuccessed(): ?bool
    {
        return $this->cashoutSuccessed;
    }

    public function setCashoutSuccessed(?bool $cashoutSuccessed): static
    {
        $this->cashoutSuccessed = $cashoutSuccessed;

        return $this;
    }

    public function isCashoutFailed(): ?bool
    {
        return $this->cashoutFailed;
    }

    public function setCashoutFailed(?bool $cashoutFailed): static
    {
        $this->cashoutFailed = $cashoutFailed;

        return $this;
    }

    public function getCashoutAt(): ?\DateTimeInterface
    {
        return $this->cashoutAt;
    }

    public function setCashoutAt(\DateTimeInterface $cashoutAt): static
    {
        $this->cashoutAt = $cashoutAt;

        return $this;
    }
}
