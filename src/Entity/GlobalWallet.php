<?php

namespace App\Entity;

use App\Repository\GlobalWalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GlobalWalletRepository::class)]
class GlobalWallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'globalWallets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MainWallet $mainWallet = null;

    #[ORM\ManyToOne(inversedBy: 'globalWallets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column(length: 255)]
    private ?string $reserve = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $fraisDepotCharged = null;

    #[ORM\Column]
    private ?float $fraisDepot = null;

    #[ORM\Column]
    private ?float $fraisWallet = null;

    #[ORM\Column]
    private ?float $fraisBlockchain = null;

    #[ORM\OneToMany(mappedBy: 'globalWallet', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(nullable: true)]
    private ?float $fraisRetrait = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainWallet(): ?MainWallet
    {
        return $this->mainWallet;
    }

    public function setMainWallet(?MainWallet $mainWallet): static
    {
        $this->mainWallet = $mainWallet;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getReserve(): ?string
    {
        return $this->reserve;
    }

    public function setReserve(string $reserve): static
    {
        $this->reserve = $reserve;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function isFraisDepotCharged(): ?bool
    {
        return $this->fraisDepotCharged;
    }

    public function setFraisDepotCharged(bool $fraisDepotCharged): static
    {
        $this->fraisDepotCharged = $fraisDepotCharged;

        return $this;
    }

    public function getFraisDepot(): ?float
    {
        return $this->fraisDepot;
    }

    public function setFraisDepot(float $fraisDepot): static
    {
        $this->fraisDepot = $fraisDepot;

        return $this;
    }

    public function getFraisWallet(): ?float
    {
        return $this->fraisWallet;
    }

    public function setFraisWallet(float $fraisWallet): static
    {
        $this->fraisWallet = $fraisWallet;

        return $this;
    }

    public function getFraisBlockchain(): ?float
    {
        return $this->fraisBlockchain;
    }

    public function setFraisBlockchain(float $fraisBlockchain): static
    {
        $this->fraisBlockchain = $fraisBlockchain;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setGlobalWallet($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getGlobalWallet() === $this) {
                $transaction->setGlobalWallet(null);
            }
        }

        return $this;
    }

    public function getFraisRetrait(): ?float
    {
        return $this->fraisRetrait;
    }

    public function setFraisRetrait(?float $fraisRetrait): static
    {
        $this->fraisRetrait = $fraisRetrait;

        return $this;
    }
}
