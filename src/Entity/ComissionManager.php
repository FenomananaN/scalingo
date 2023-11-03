<?php

namespace App\Entity;

use App\Repository\ComissionManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComissionManagerRepository::class)]
class ComissionManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $UniteComissionAzo = null;

    #[ORM\Column(length: 255)]
    private ?string $UniteTransactionMahazoCommission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUniteComissionAzo(): ?string
    {
        return $this->UniteComissionAzo;
    }

    public function setUniteComissionAzo(string $UniteComissionAzo): static
    {
        $this->UniteComissionAzo = $UniteComissionAzo;

        return $this;
    }

    public function getUniteTransactionMahazoCommission(): ?string
    {
        return $this->UniteTransactionMahazoCommission;
    }

    public function setUniteTransactionMahazoCommission(string $UniteTransactionMahazoCommission): static
    {
        $this->UniteTransactionMahazoCommission = $UniteTransactionMahazoCommission;

        return $this;
    }
}
