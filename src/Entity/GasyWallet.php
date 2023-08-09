<?php

namespace App\Entity;

use App\Repository\GasyWalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GasyWalletRepository::class)]
class GasyWallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $gasyWalletName = null;

    #[ORM\Column(length: 255)]
    private ?string $reserve = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $logoMain = null;

    #[ORM\OneToMany(mappedBy: 'gasyWallet', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGasyWalletName(): ?string
    {
        return $this->gasyWalletName;
    }

    public function setGasyWalletName(string $gasyWalletName): static
    {
        $this->gasyWalletName = $gasyWalletName;

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
            $transaction->setGasyWallet($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getGasyWallet() === $this) {
                $transaction->setGasyWallet(null);
            }
        }

        return $this;
    }
}
