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
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $solde = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $AccountNumber = null;

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
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $beingProcessed = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column]
    private ?bool $transactionDone = null;
    
    #[ORM\Column(nullable: true)]
    private ?bool $failed = null;

    #[ORM\Column]
    private ?bool $policyAgreement = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceManavola = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $RPObtenu = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function setTransactionType(string $transactionType): self
    {
        $this->transactionType = $transactionType;

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

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->AccountNumber;
    }

    public function setAccountNumber(?string $AccountNumber): self
    {
        $this->AccountNumber = $AccountNumber;

        return $this;
    }

    public function getSoldeAriary(): ?string
    {
        return $this->soldeAriary;
    }

    public function setSoldeAriary(string $soldeAriary): self
    {
        $this->soldeAriary = $soldeAriary;

        return $this;
    }

    public function getCours(): ?string
    {
        return $this->cours;
    }

    public function setCours(string $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function getGlobalWallet(): ?GlobalWallet
    {
        return $this->globalWallet;
    }

    public function setGlobalWallet(?GlobalWallet $globalWallet): self
    {
        $this->globalWallet = $globalWallet;

        return $this;
    }

    public function getGasyWallet(): ?GasyWallet
    {
        return $this->gasyWallet;
    }

    public function setGasyWallet(?GasyWallet $gasyWallet): self
    {
        $this->gasyWallet = $gasyWallet;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isBeingProcessed(): ?bool
    {
        return $this->beingProcessed;
    }

    public function setBeingProcessed(bool $beingProcessed): self
    {
        $this->beingProcessed = $beingProcessed;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function isTransactionDone(): ?bool
    {
        return $this->transactionDone;
    }

    public function setTransactionDone(bool $transactionDone): self
    {
        $this->transactionDone = $transactionDone;

        return $this;
    }

    public function isPolicyAgreement(): ?bool
    {
        return $this->policyAgreement;
    }

    public function setPolicyAgreement(bool $policyAgreement): self
    {
        $this->policyAgreement = $policyAgreement;

        return $this;
    }

    public function getReferenceManavola(): ?string
    {
        return $this->referenceManavola;
    }

    public function setReferenceManavola(string $referenceManavola): self
    {
        $this->referenceManavola = $referenceManavola;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function isFailed(): ?bool
    {
        return $this->failed;
    }

    public function setFailed(?bool $failed): self
    {
        $this->failed = $failed;

        return $this;
    }

    public function getRPObtenu(): ?string
    {
        return $this->RPObtenu;
    }

    public function setRPObtenu(?string $RPObtenu): self
    {
        $this->RPObtenu = $RPObtenu;

        return $this;
    }
}
