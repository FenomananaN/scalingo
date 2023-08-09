<?php

namespace App\Entity;

use App\Repository\AffiliatedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffiliatedRepository::class)]
class Affiliated
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'affiliated', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\Column(length: 255)]
    private ?string $commision = null;

    #[ORM\Column(length: 255)]
    private ?string $mvxId = null;

    #[ORM\OneToMany(mappedBy: 'affiliated', targetEntity: AffiliatedLevel::class)]
    private Collection $affiliatedLevels;

    public function __construct()
    {
        $this->affiliatedLevels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getCommision(): ?string
    {
        return $this->commision;
    }

    public function setCommision(string $commision): static
    {
        $this->commision = $commision;

        return $this;
    }

    public function getMvxId(): ?string
    {
        return $this->mvxId;
    }

    public function setMvxId(string $mvxId): static
    {
        $this->mvxId = $mvxId;

        return $this;
    }

    /**
     * @return Collection<int, AffiliatedLevel>
     */
    public function getAffiliatedLevels(): Collection
    {
        return $this->affiliatedLevels;
    }

    public function addAffiliatedLevel(AffiliatedLevel $affiliatedLevel): static
    {
        if (!$this->affiliatedLevels->contains($affiliatedLevel)) {
            $this->affiliatedLevels->add($affiliatedLevel);
            $affiliatedLevel->setAffiliated($this);
        }

        return $this;
    }

    public function removeAffiliatedLevel(AffiliatedLevel $affiliatedLevel): static
    {
        if ($this->affiliatedLevels->removeElement($affiliatedLevel)) {
            // set the owning side to null (unless already changed)
            if ($affiliatedLevel->getAffiliated() === $this) {
                $affiliatedLevel->setAffiliated(null);
            }
        }

        return $this;
    }
}
