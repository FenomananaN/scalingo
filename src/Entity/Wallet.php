<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $walletName = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: GlobalWallet::class)]
    private Collection $globalWallets;

    #[ORM\OneToOne(mappedBy: 'Wallet', cascade: ['persist', 'remove'])]
    private ?DepotCours $depotCours = null;

    #[ORM\OneToOne(mappedBy: 'Wallet', cascade: ['persist', 'remove'])]
    private ?RetraitCours $retraitCours = null;


    public function __construct()
    {
        $this->globalWallets = new ArrayCollection();
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWalletName(): ?string
    {
        return $this->walletName;
    }

    public function setWalletName(string $walletName): self
    {
        $this->walletName = $walletName;

        return $this;
    }


    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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
            $globalWallet->setWallet($this);
        }

        return $this;
    }

    public function removeGlobalWallet(GlobalWallet $globalWallet): self
    {
        if ($this->globalWallets->removeElement($globalWallet)) {
            // set the owning side to null (unless already changed)
            if ($globalWallet->getWallet() === $this) {
                $globalWallet->setWallet(null);
            }
        }

        return $this;
    }

    public function getDepotCours(): ?DepotCours
    {
        return $this->depotCours;
    }

    public function setDepotCours(DepotCours $depotCours): self
    {
        // set the owning side of the relation if necessary
        if ($depotCours->getWallet() !== $this) {
            $depotCours->setWallet($this);
        }

        $this->depotCours = $depotCours;

        return $this;
    }

    public function getRetraitCours(): ?RetraitCours
    {
        return $this->retraitCours;
    }

    public function setRetraitCours(RetraitCours $retraitCours): self
    {
        // set the owning side of the relation if necessary
        if ($retraitCours->getWallet() !== $this) {
            $retraitCours->setWallet($this);
        }

        $this->retraitCours = $retraitCours;

        return $this;
    }
}
