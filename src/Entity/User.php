<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telma = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orange = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $airtel = null;

    #[ORM\Column]
    private ?bool $verifiedStatus = null;

    #[ORM\Column]
    private ?int $fidelityPt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Depot::class)]
    private Collection $depot;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Affiliated $affiliated = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AffiliatedLevel::class)]
    private Collection $affiliatedLevels;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Retrait::class)]
    private Collection $retraits;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(nullable: true)]
    private ?bool $verificationPending = null;

    #[ORM\Column(nullable: true)]
    private ?bool $verificationFailed = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: OldPhoneNumber::class)]
    private Collection $oldPhoneNumbers;

    public function __construct()
    {
        $this->depot = new ArrayCollection();
        $this->affiliatedLevels = new ArrayCollection();
        $this->retraits = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->oldPhoneNumbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getTelma(): ?string
    {
        return $this->telma;
    }

    public function setTelma(?string $telma): self
    {
        $this->telma = $telma;

        return $this;
    }

    public function getOrange(): ?string
    {
        return $this->orange;
    }

    public function setOrange(?string $orange): self
    {
        $this->orange = $orange;

        return $this;
    }

    public function getAirtel(): ?string
    {
        return $this->airtel;
    }

    public function setAirtel(?string $airtel): self
    {
        $this->airtel = $airtel;

        return $this;
    }

    public function isVerifiedStatus(): ?bool
    {
        return $this->verifiedStatus;
    }

    public function setVerifiedStatus(bool $verifiedStatus): self
    {
        $this->verifiedStatus = $verifiedStatus;

        return $this;
    }

    public function getFidelityPt(): ?int
    {
        return $this->fidelityPt;
    }

    public function setFidelityPt(int $fidelityPt): self
    {
        $this->fidelityPt = $fidelityPt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return Collection<int, Depot>
     */
    public function getDepot(): Collection
    {
        return $this->depot;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depot->contains($depot)) {
            $this->depot->add($depot);
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    public function getAffiliated(): ?Affiliated
    {
        return $this->affiliated;
    }

    public function setAffiliated(Affiliated $affiliated): self
    {
        // set the owning side of the relation if necessary
        if ($affiliated->getUser() !== $this) {
            $affiliated->setUser($this);
        }

        $this->affiliated = $affiliated;

        return $this;
    }

    /**
     * @return Collection<int, AffiliatedLevel>
     */
    public function getAffiliatedLevels(): Collection
    {
        return $this->affiliatedLevels;
    }

    public function addAffiliatedLevel(AffiliatedLevel $affiliatedLevel): self
    {
        if (!$this->affiliatedLevels->contains($affiliatedLevel)) {
            $this->affiliatedLevels->add($affiliatedLevel);
            $affiliatedLevel->setUser($this);
        }

        return $this;
    }

    public function removeAffiliatedLevel(AffiliatedLevel $affiliatedLevel): self
    {
        if ($this->affiliatedLevels->removeElement($affiliatedLevel)) {
            // set the owning side to null (unless already changed)
            if ($affiliatedLevel->getUser() === $this) {
                $affiliatedLevel->setUser(null);
            }
        }

        return $this;
    }

    //
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $retrait->setUser($this);
        }

        return $this;
    }

    public function removeRetrait(Retrait $retrait): self
    {
        if ($this->retraits->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getUser() === $this) {
                $retrait->setUser(null);
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
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }

    public function isVerificationPending(): ?bool
    {
        return $this->verificationPending;
    }

    public function setVerificationPending(?bool $verificationPending): self
    {
        $this->verificationPending = $verificationPending;

        return $this;
    }

    public function isVerificationFailed(): ?bool
    {
        return $this->verificationFailed;
    }

    public function setVerificationFailed(?bool $verificationFailed): self
    {
        $this->verificationFailed = $verificationFailed;

        return $this;
    }

    /**
     * @return Collection<int, OldPhoneNumber>
     */
    public function getOldPhoneNumbers(): Collection
    {
        return $this->oldPhoneNumbers;
    }

    public function addOldPhoneNumber(OldPhoneNumber $oldPhoneNumber): self
    {
        if (!$this->oldPhoneNumbers->contains($oldPhoneNumber)) {
            $this->oldPhoneNumbers->add($oldPhoneNumber);
            $oldPhoneNumber->setUser($this);
        }

        return $this;
    }

    public function removeOldPhoneNumber(OldPhoneNumber $oldPhoneNumber): self
    {
        if ($this->oldPhoneNumbers->removeElement($oldPhoneNumber)) {
            // set the owning side to null (unless already changed)
            if ($oldPhoneNumber->getUser() === $this) {
                $oldPhoneNumber->setUser(null);
            }
        }

        return $this;
    }
}
