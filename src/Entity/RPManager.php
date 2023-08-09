<?php

namespace App\Entity;

use App\Repository\RPManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RPManagerRepository::class)]
class RPManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $RPInitial = null;

    #[ORM\Column]
    private ?int $RRAriary = null;

    #[ORM\Column]
    private ?int $PObtenue = null;

    #[ORM\Column(length: 255)]
    private ?string $RPRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRPInitial(): ?int
    {
        return $this->RPInitial;
    }

    public function setRPInitial(int $RPInitial): static
    {
        $this->RPInitial = $RPInitial;

        return $this;
    }

    public function getRRAriary(): ?int
    {
        return $this->RRAriary;
    }

    public function setRRAriary(int $RRAriary): static
    {
        $this->RRAriary = $RRAriary;

        return $this;
    }

    public function getPObtenue(): ?int
    {
        return $this->PObtenue;
    }

    public function setPObtenue(int $PObtenue): static
    {
        $this->PObtenue = $PObtenue;

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
}
