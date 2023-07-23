<?php

namespace App\Entity;

use App\Repository\RetraitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetraitRepository::class)]
class Retrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'retraits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'retraits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GasyWallet $gasyWallet = null;

    #[ORM\ManyToOne(inversedBy: 'retraits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GlobalWallet $globalWallet = null;

    #[ORM\Column(length: 255)]
    private ?string $soldeDemmande = null;

    #[ORM\Column(length: 255)]
    private ?string $totalToReceive = null;

    #[ORM\Column(length: 255)]
    private ?string $cours = null;

    #[ORM\Column]
    private ?bool $policyAgreement = null;

    #[ORM\Column]
    private ?bool $beingProcessed = null;

    #[ORM\Column]
    private ?bool $verified = null;

 
    #[ORM\Column]
    private ?bool $transacDone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceManavola = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transactionId = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGasyWallet(): ?GasyWallet
    {
        return $this->gasyWallet;
    }

    public function setGasyWallet(?GasyWallet $gasyWallet): self
    {
        $this->gasyWallet = $gasyWallet;

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

    public function getSoldeDemmande(): ?string
    {
        return $this->soldeDemmande;
    }

    public function setSoldeDemmande(string $soldeDemmande): self
    {
        $this->soldeDemmande = $soldeDemmande;

        return $this;
    }

    public function getTotalToReceive(): ?string
    {
        return $this->totalToReceive;
    }

    public function setTotalToReceive(string $totalToReceive): self
    {
        $this->totalToReceive = $totalToReceive;

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

    public function isPolicyAgreement(): ?bool
    {
        return $this->policyAgreement;
    }

    public function setPolicyAgreement(bool $policyAgreement): self
    {
        $this->policyAgreement = $policyAgreement;

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

    public function isTransacDone(): ?bool
    {
        return $this->transacDone;
    }

    public function setTransacDone(bool $transacDone): self
    {
        $this->transacDone = $transacDone;

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
}
