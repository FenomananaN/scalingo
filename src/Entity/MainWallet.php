<?php

namespace App\Entity;

use App\Repository\MainWalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MainWalletRepository::class)]
class MainWallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mainWalletName = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'mainWallet', targetEntity: GlobalWallet::class)]
    private Collection $globalWallets;

    public function __construct()
    {
        $this->globalWallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainWalletName(): ?string
    {
        return $this->mainWalletName;
    }

    public function setMainWalletName(string $mainWalletName): self
    {
        $this->mainWalletName = $mainWalletName;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }


    /**
     * @return Collection<int, GlobalWallet>
     */
    public function getGlobalWallets(): Collection
    {
        return $this->globalWallets;
    }

    public function addGlobalWallet(GlobalWallet $globalWallet): self
    {
        if (!$this->globalWallets->contains($globalWallet)) {
            $this->globalWallets->add($globalWallet);
            $globalWallet->setMainWallet($this);
        }

        return $this;
    }

    public function removeGlobalWallet(GlobalWallet $globalWallet): self
    {
        if ($this->globalWallets->removeElement($globalWallet)) {
            // set the owning side to null (unless already changed)
            if ($globalWallet->getMainWallet() === $this) {
                $globalWallet->setMainWallet(null);
            }
        }

        return $this;
    }
}
