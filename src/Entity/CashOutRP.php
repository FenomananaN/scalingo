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
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $RP = null;

    #[ORM\Column(length: 255)]
    private ?string $RPRate = null;

    #[ORM\Column(length: 255)]
    private ?string $MGAValue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRP(): ?string
    {
        return $this->RP;
    }

    public function setRP(string $RP): self
    {
        $this->RP = $RP;

        return $this;
    }

    public function getRPRate(): ?string
    {
        return $this->RPRate;
    }

    public function setRPRate(string $RPRate): self
    {
        $this->RPRate = $RPRate;

        return $this;
    }

    public function getMGAValue(): ?string
    {
        return $this->MGAValue;
    }

    public function setMGAValue(string $MGAValue): self
    {
        $this->MGAValue = $MGAValue;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
