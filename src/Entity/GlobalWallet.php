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
    private ?string $Reserve = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $fraisDepotCharged = null;

    #[ORM\Column]
    private ?float $fraisDepot = null;

    #[ORM\OneToMany(mappedBy: 'globalWallet', targetEntity: Depot::class)]
    private Collection $depots;

    #[ORM\Column(nullable: true)]
    private ?float $fraisWallet = null;

    #[ORM\Column(nullable: true)]
    private ?float $fraisBlockchain = null;

    #[ORM\Column(nullable: true)]
    private ?float $fraisRetrait = null;

    #[ORM\OneToMany(mappedBy: 'globalWallet', targetEntity: Retrait::class)]
    private Collection $retraits;

    #[ORM\OneToMany(mappedBy: 'globalWallet', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->retraits = new ArrayCollection();
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

    public function setMainWallet(?MainWallet $mainWallet): self
    {
        $this->mainWallet = $mainWallet;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getReserve(): ?string
    {
        return $this->Reserve;
    }

    public function setReserve(string $Reserve): self
    {
        $this->Reserve = $Reserve;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function isFraisDepotCharged(): ?bool
    {
        return $this->fraisDepotCharged;
    }

    public function setFraisDepotCharged(bool $fraisDepotCharged): self
    {
        $this->fraisDepotCharged = $fraisDepotCharged;

        return $this;
    }

    public function getFraisDepot(): ?float
    {
        return $this->fraisDepot;
    }

    public function setFraisDepot(float $fraisDepot): self
    {
        $this->fraisDepot = $fraisDepot;

        return $this;
    }

    /**
     * @return Collection<int, Depot>
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots->add($depot);
            $depot->setGlobalWallet($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getGlobalWallet() === $this) {
                $depot->setGlobalWallet(null);
            }
        }

        return $this;
    }

    public function getFraisWallet(): ?float
    {
        return $this->fraisWallet;
    }

    public function setFraisWallet(?float $fraisWallet): self
    {
        $this->fraisWallet = $fraisWallet;

        return $this;
    }

    public function getFraisBlockchain(): ?float
    {
        return $this->fraisBlockchain;
    }

    public function setFraisBlockchain(?float $fraisBlockchain): self
    {
        $this->fraisBlockchain = $fraisBlockchain;

        return $this;
    }

    public function getFraisRetrait(): ?float
    {
        return $this->fraisRetrait;
    }

    public function setFraisRetrait(?float $fraisRetrait): self
    {
        $this->fraisRetrait = $fraisRetrait;

        return $this;
    }

    /**
     * @return Collection<int, Retrait>
     */
    public function getRetraits(): Collection
    {
        return $this->retraits;
    }

    public function addRetrait(Retrait $retrait): self
    {
        if (!$this->retraits->contains($retrait)) {
            $this->retraits->add($retrait);
            $retrait->setGlobalWallet($this);
        }

        return $this;
    }

    public function removeRetrait(Retrait $retrait): self
    {
        if ($this->retraits->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getGlobalWallet() === $this) {
                $retrait->setGlobalWallet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setGlobalWallet($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getGlobalWallet() === $this) {
                $transaction->setGlobalWallet(null);
            }
        }

        return $this;
    }
}
