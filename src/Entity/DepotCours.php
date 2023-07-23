<?php

namespace App\Entity;

use App\Repository\DepotCoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepotCoursRepository::class)]
class DepotCours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'depotCours', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $Wallet = null;

    #[ORM\Column]
    private ?int $coursMax = null;

    #[ORM\Column]
    private ?int $coursMin = null;

    #[ORM\Column]
    private ?int $MontantMRMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): ?Wallet
    {
        return $this->Wallet;
    }

    public function setWallet(Wallet $Wallet): self
    {
        $this->Wallet = $Wallet;

        return $this;
    }

    public function getCoursMax(): ?int
    {
        return $this->coursMax;
    }

    public function setCoursMax(int $coursMax): self
    {
        $this->coursMax = $coursMax;

        return $this;
    }

    public function getCoursMin(): ?int
    {
        return $this->coursMin;
    }

    public function setCoursMin(int $coursMin): self
    {
        $this->coursMin = $coursMin;

        return $this;
    }

    public function getMontantMRMax(): ?int
    {
        return $this->MontantMRMax;
    }

    public function setMontantMRMax(int $MontantMRMax): self
    {
        $this->MontantMRMax = $MontantMRMax;

        return $this;
    }
}
