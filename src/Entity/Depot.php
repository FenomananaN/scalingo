<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepotRepository::class)]
class Depot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $soldeDemande = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCompte = null;

    #[ORM\Column(length: 255)]
    private ?string $totalToPaid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $confirmedDemand = null;

    #[ORM\Column]
    private ?bool $manualVerification = null;

    #[ORM\Column(length: 255)]
    private ?string $cours = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceManavola = null;

    #[ORM\ManyToOne(inversedBy: 'depots')]
    #[ORM\JoinColumn(nullable: true)]
    private ?GasyWallet $gasyWallet = null;

    #[ORM\ManyToOne(inversedBy: 'depot')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $doneCommand = null;

    #[ORM\ManyToOne(inversedBy: 'depots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GlobalWallet $globalWallet = null;

    #[ORM\Column]
    private ?bool $policyAgreement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSoldeDemande(): ?string
    {
        return $this->soldeDemande;
    }

    public function setSoldeDemande(string $soldeDemande): self
    {
        $this->soldeDemande = $soldeDemande;

        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(string $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    public function getTotalToPaid(): ?string
    {
        return $this->totalToPaid;
    }

    public function setTotalToPaid(string $totalToPaid): self
    {
        $this->totalToPaid = $totalToPaid;

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

    public function isConfirmedDemand(): ?bool
    {
        return $this->confirmedDemand;
    }

    public function setConfirmedDemand(bool $confirmedDemand): self
    {
        $this->confirmedDemand = $confirmedDemand;

        return $this;
    }

    public function isManualVerification(): ?bool
    {
        return $this->manualVerification;
    }

    public function setManualVerification(bool $manualVerification): self
    {
        $this->manualVerification = $manualVerification;

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

    public function getReferenceManavola(): ?string
    {
        return $this->referenceManavola;
    }

    public function setReferenceManavola(string $referenceManavola): self
    {
        $this->referenceManavola = $referenceManavola;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isDoneCommand(): ?bool
    {
        return $this->doneCommand;
    }

    public function setDoneCommand(bool $doneCommand): self
    {
        $this->doneCommand = $doneCommand;

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

    public function isPolicyAgreement(): ?bool
    {
        return $this->policyAgreement;
    }

    public function setPolicyAgreement(bool $policyAgreement): self
    {
        $this->policyAgreement = $policyAgreement;

        return $this;
    }
}
