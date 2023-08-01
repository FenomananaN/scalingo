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
    private ?int $PObtenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $RPRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRPInitial(): ?int
    {
        return $this->RPInitial;
    }

    public function setRPInitial(int $RPInitial): self
    {
        $this->RPInitial = $RPInitial;

        return $this;
    }

    public function getRRAriary(): ?int
    {
        return $this->RRAriary;
    }

    public function setRRAriary(int $RRAriary): self
    {
        $this->RRAriary = $RRAriary;

        return $this;
    }

    public function getPObtenu(): ?int
    {
        return $this->PObtenu;
    }

    public function setPObtenu(int $PObtenu): self
    {
        $this->PObtenu = $PObtenu;

        return $this;
    }

    public function getRPRate(): ?string
    {
        return $this->RPRate;
    }

    public function setRPRate(?string $RPRate): self
    {
        $this->RPRate = $RPRate;

        return $this;
    }
}
