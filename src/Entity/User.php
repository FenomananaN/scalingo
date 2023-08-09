<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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

    #[ORM\Column(length: 255)]
    private ?string $currentRP = null;

    #[ORM\Column(nullable: true)]
    private ?bool $verifiedStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $verificationPending = null;

    #[ORM\Column(nullable: true)]
    private ?bool $verificationFailed = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    private ?UserVerifiedInfo $userVerifiedInfo = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    private ?Affiliated $affiliated = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: AffiliatedLevel::class)]
    private Collection $affiliatedLevels;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: OldPhoneNumber::class)]
    private Collection $oldPhoneNumbers;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: CashOutRP::class)]
    private Collection $cashOutRPs;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    public function __construct()
    {
        $this->affiliatedLevels = new ArrayCollection();
        $this->oldPhoneNumbers = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->cashOutRPs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getTelma(): ?string
    {
        return $this->telma;
    }

    public function setTelma(?string $telma): static
    {
        $this->telma = $telma;

        return $this;
    }

    public function getOrange(): ?string
    {
        return $this->orange;
    }

    public function setOrange(?string $orange): static
    {
        $this->orange = $orange;

        return $this;
    }

    public function getAirtel(): ?string
    {
        return $this->airtel;
    }

    public function setAirtel(?string $airtel): static
    {
        $this->airtel = $airtel;

        return $this;
    }

    public function getCurrentRP(): ?string
    {
        return $this->currentRP;
    }

    public function setCurrentRP(string $currentRP): static
    {
        $this->currentRP = $currentRP;

        return $this;
    }

    public function isVerifiedStatus(): ?bool
    {
        return $this->verifiedStatus;
    }

    public function setVerifiedStatus(?bool $verifiedStatus): static
    {
        $this->verifiedStatus = $verifiedStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isVerificationPending(): ?bool
    {
        return $this->verificationPending;
    }

    public function setVerificationPending(?bool $verificationPending): static
    {
        $this->verificationPending = $verificationPending;

        return $this;
    }

    public function isVerificationFailed(): ?bool
    {
        return $this->verificationFailed;
    }

    public function setVerificationFailed(?bool $verificationFailed): static
    {
        $this->verificationFailed = $verificationFailed;

        return $this;
    }

    public function getUserVerifiedInfo(): ?UserVerifiedInfo
    {
        return $this->userVerifiedInfo;
    }

    public function setUserVerifiedInfo(UserVerifiedInfo $userVerifiedInfo): static
    {
        // set the owning side of the relation if necessary
        if ($userVerifiedInfo->getUsers() !== $this) {
            $userVerifiedInfo->setUsers($this);
        }

        $this->userVerifiedInfo = $userVerifiedInfo;

        return $this;
    }

    public function getAffiliated(): ?Affiliated
    {
        return $this->affiliated;
    }

    public function setAffiliated(Affiliated $affiliated): static
    {
        // set the owning side of the relation if necessary
        if ($affiliated->getUsers() !== $this) {
            $affiliated->setUsers($this);
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

    public function addAffiliatedLevel(AffiliatedLevel $affiliatedLevel): static
    {
        if (!$this->affiliatedLevels->contains($affiliatedLevel)) {
            $this->affiliatedLevels->add($affiliatedLevel);
            $affiliatedLevel->setUsers($this);
        }

        return $this;
    }

    public function removeAffiliatedLevel(AffiliatedLevel $affiliatedLevel): static
    {
        if ($this->affiliatedLevels->removeElement($affiliatedLevel)) {
            // set the owning side to null (unless already changed)
            if ($affiliatedLevel->getUsers() === $this) {
                $affiliatedLevel->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OldPhoneNumber>
     */
    public function getOldPhoneNumbers(): Collection
    {
        return $this->oldPhoneNumbers;
    }

    public function addOldPhoneNumber(OldPhoneNumber $oldPhoneNumber): static
    {
        if (!$this->oldPhoneNumbers->contains($oldPhoneNumber)) {
            $this->oldPhoneNumbers->add($oldPhoneNumber);
            $oldPhoneNumber->setUsers($this);
        }

        return $this;
    }

    public function removeOldPhoneNumber(OldPhoneNumber $oldPhoneNumber): static
    {
        if ($this->oldPhoneNumbers->removeElement($oldPhoneNumber)) {
            // set the owning side to null (unless already changed)
            if ($oldPhoneNumber->getUsers() === $this) {
                $oldPhoneNumber->setUsers(null);
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

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setUsers($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUsers() === $this) {
                $transaction->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CashOutRP>
     */
    public function getCashOutRPs(): Collection
    {
        return $this->cashOutRPs;
    }

    public function addCashOutRP(CashOutRP $cashOutRP): static
    {
        if (!$this->cashOutRPs->contains($cashOutRP)) {
            $this->cashOutRPs->add($cashOutRP);
            $cashOutRP->setUsers($this);
        }

        return $this;
    }

    public function removeCashOutRP(CashOutRP $cashOutRP): static
    {
        if ($this->cashOutRPs->removeElement($cashOutRP)) {
            // set the owning side to null (unless already changed)
            if ($cashOutRP->getUsers() === $this) {
                $cashOutRP->setUsers(null);
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
}
