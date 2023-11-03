<?php

namespace App\Entity;

use App\Repository\AffiliatedLevelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffiliatedLevelRepository::class)]
class AffiliatedLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affiliatedLevels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Affiliated $affiliated = null;

    #[ORM\ManyToOne(inversedBy: 'affiliatedLevels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $miniCommission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAffiliated(): ?Affiliated
    {
        return $this->affiliated;
    }

    public function setAffiliated(?Affiliated $affiliated): static
    {
        $this->affiliated = $affiliated;

        return $this;
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

    public function getMiniCommission(): ?string
    {
        return $this->miniCommission;
    }

    public function setMiniCommission(?string $miniCommission): static
    {
        $this->miniCommission = $miniCommission;

        return $this;
    }
}
