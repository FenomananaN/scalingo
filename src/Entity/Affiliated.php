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
    private ?User $user = null;

    #[ORM\Column]
    private ?int $mvx = null;

    #[ORM\Column(length: 255)]
    private ?string $parrainageId = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMvx(): ?int
    {
        return $this->mvx;
    }

    public function setMvx(int $mvx): self
    {
        $this->mvx = $mvx;

        return $this;
    }

    public function getParrainageId(): ?string
    {
        return $this->parrainageId;
    }

    public function setParrainageId(string $parrainageId): self
    {
        $this->parrainageId = $parrainageId;

        return $this;
    }

    /**
     * @return Collection<int, AffiliatedLevel>
     */
    public function getAffiliatedLevels(): Collection
    {
        return $this->affiliatedLevels;
    }

    public function addAffiliatedLevel(AffiliatedLevel $affiliatedLevel): self
    {
        if (!$this->affiliatedLevels->contains($affiliatedLevel)) {
            $this->affiliatedLevels->add($affiliatedLevel);
            $affiliatedLevel->setAffiliated($this);
        }

        return $this;
    }

    public function removeAffiliatedLevel(AffiliatedLevel $affiliatedLevel): self
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
