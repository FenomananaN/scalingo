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

    #[ORM\Column(length: 255)]
    private ?string $logoMain = null;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: GlobalWallet::class)]
    private Collection $globalWallets;

    #[ORM\OneToOne(mappedBy: 'wallet', cascade: ['persist', 'remove'])]
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

    public function setWalletName(string $walletName): static
    {
        $this->walletName = $walletName;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogoMain(): ?string
    {
        return $this->logoMain;
    }

    public function setLogoMain(string $logoMain): static
    {
        $this->logoMain = $logoMain;

        return $this;
    }

    /**
     * @return Collection<int, GlobalWallet>
     */
    public function getGlobalWallets(): Collection
    {
        return $this->globalWallets;
    }

    public function addGlobalWallet(GlobalWallet $globalWallet): static
    {
        if (!$this->globalWallets->contains($globalWallet)) {
            $this->globalWallets->add($globalWallet);
            $globalWallet->setWallet($this);
        }

        return $this;
    }

    public function removeGlobalWallet(GlobalWallet $globalWallet): static
    {
        if ($this->globalWallets->removeElement($globalWallet)) {
            // set the owning side to null (unless already changed)
            if ($globalWallet->getWallet() === $this) {
                $globalWallet->setWallet(null);
            }
        }

        return $this;
    }

    public function getRetraitCours(): ?RetraitCours
    {
        return $this->retraitCours;
    }

    public function setRetraitCours(RetraitCours $retraitCours): static
    {
        // set the owning side of the relation if necessary
        if ($retraitCours->getWallet() !== $this) {
            $retraitCours->setWallet($this);
        }

        $this->retraitCours = $retraitCours;

        return $this;
    }
}
