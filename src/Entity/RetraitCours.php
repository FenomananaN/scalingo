<?php

namespace App\Entity;

use App\Repository\RetraitCoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetraitCoursRepository::class)]
class RetraitCours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'retraitCours', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column]
    private ?int $coursMax = null;

    #[ORM\Column]
    private ?int $coursMin = null;

    #[ORM\Column]
    private ?int $montantMRMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getCoursMax(): ?int
    {
        return $this->coursMax;
    }

    public function setCoursMax(int $coursMax): static
    {
        $this->coursMax = $coursMax;

        return $this;
    }

    public function getCoursMin(): ?int
    {
        return $this->coursMin;
    }

    public function setCoursMin(int $coursMin): static
    {
        $this->coursMin = $coursMin;

        return $this;
    }

    public function getMontantMRMax(): ?int
    {
        return $this->montantMRMax;
    }

    public function setMontantMRMax(int $montantMRMax): static
    {
        $this->montantMRMax = $montantMRMax;

        return $this;
    }
}
