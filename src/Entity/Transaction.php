<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $transactionType = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?User $users = null;

    #[ORM\Column(length: 255)]
    private ?string $solde = null;

    #[ORM\Column(length: 255)]
    private ?string $accountNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $soldeAriary = null;

    #[ORM\Column(length: 255)]
    private ?string $cours = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GlobalWallet $globalWallet = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?GasyWallet $gasyWallet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactionAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $beingProcessed = null;

    #[ORM\Column(nullable: true)]
    private ?bool $verified = null;

    #[ORM\Column(nullable: true)]
    private ?bool $transactionDone = null;

    #[ORM\Column(nullable: true)]
    private ?bool $failed = null;

    #[ORM\Column]
    private ?bool $policyAgreement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceManavola = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(length: 255)]
    private ?string $RPObtenue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function setTransactionType(string $transactionType): static
    {
        $this->transactionType = $transactionType;

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

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): static
    {
        $this->solde = $solde;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): static
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getSoldeAriary(): ?string
    {
        return $this->soldeAriary;
    }

    public function setSoldeAriary(string $soldeAriary): static
    {
        $this->soldeAriary = $soldeAriary;

        return $this;
    }

    public function getCours(): ?string
    {
        return $this->cours;
    }

    public function setCours(string $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getGlobalWallet(): ?GlobalWallet
    {
        return $this->globalWallet;
    }

    public function setGlobalWallet(?GlobalWallet $globalWallet): static
    {
        $this->globalWallet = $globalWallet;

        return $this;
    }

    public function getGasyWallet(): ?GasyWallet
    {
        return $this->gasyWallet;
    }

    public function setGasyWallet(?GasyWallet $gasyWallet): static
    {
        $this->gasyWallet = $gasyWallet;

        return $this;
    }

    public function getTransactionAt(): ?\DateTimeInterface
    {
        return $this->transactionAt;
    }

    public function setTransactionAt(\DateTimeInterface $transactionAt): static
    {
        $this->transactionAt = $transactionAt;

        return $this;
    }

    public function isBeingProcessed(): ?bool
    {
        return $this->beingProcessed;
    }

    public function setBeingProcessed(?bool $beingProcessed): static
    {
        $this->beingProcessed = $beingProcessed;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(?bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function isTransactionDone(): ?bool
    {
        return $this->transactionDone;
    }

    public function setTransactionDone(?bool $transactionDone): static
    {
        $this->transactionDone = $transactionDone;

        return $this;
    }

    public function isFailed(): ?bool
    {
        return $this->failed;
    }

    public function setFailed(?bool $failed): static
    {
        $this->failed = $failed;

        return $this;
    }

    public function isPolicyAgreement(): ?bool
    {
        return $this->policyAgreement;
    }

    public function setPolicyAgreement(bool $policyAgreement): static
    {
        $this->policyAgreement = $policyAgreement;

        return $this;
    }

    public function getReferenceManavola(): ?string
    {
        return $this->referenceManavola;
    }

    public function setReferenceManavola(?string $referenceManavola): static
    {
        $this->referenceManavola = $referenceManavola;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): static
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getRPObtenue(): ?string
    {
        return $this->RPObtenue;
    }

    public function setRPObtenue(string $RPObtenue): static
    {
        $this->RPObtenue = $RPObtenue;

        return $this;
    }
}
