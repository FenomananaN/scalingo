<?php

namespace App\Entity;

use App\Repository\ComissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComissionRepository::class)]
class Comission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $comissionFrom = null;

    #[ORM\Column(length: 255)]
    private ?string $MGAValue = null;

    #[ORM\Column(length: 255)]
    private ?string $comission = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $comissionAt = null;

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

    public function getComissionFrom(): ?User
    {
        return $this->comissionFrom;
    }

    public function setComissionFrom(?User $comissionFrom): static
    {
        $this->comissionFrom = $comissionFrom;

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

    public function getComission(): ?string
    {
        return $this->comission;
    }

    public function setComission(string $comission): static
    {
        $this->comission = $comission;

        return $this;
    }

    public function getComissionAt(): ?\DateTimeInterface
    {
        return $this->comissionAt;
    }

    public function setComissionAt(\DateTimeInterface $comissionAt): static
    {
        $this->comissionAt = $comissionAt;

        return $this;
    }

}
