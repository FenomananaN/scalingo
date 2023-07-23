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
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAffiliated(): ?Affiliated
    {
        return $this->affiliated;
    }

    public function setAffiliated(?Affiliated $affiliated): self
    {
        $this->affiliated = $affiliated;

        return $this;
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
}
