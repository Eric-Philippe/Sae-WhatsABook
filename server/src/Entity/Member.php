<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[Groups(['getBookReservation'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['getBookReservation'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getBookReservation'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getBookReservation'])]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['getBookReservation'])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getBookReservation'])]
    private ?string $email = "";

    #[ORM\Column(length: 80)]
    #[Groups(['getBookReservation'])]
    private ?string $adress = null;

    #[ORM\Column(length: 10)]
    #[Groups(['getBookReservation'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getBookReservation'])]
    private ?string $photoLink = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'members')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    private ?Role $role = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Reservation::class, orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private $reservation;
    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Loan::class, orphanRemoval: true)]
    #[ORM\OrderBy(['loanDate' => 'ASC'])]
    private $loans;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPhotoLink(): ?string
    {
        return $this->photoLink;
    }

    public function setPhotoLink(string $photoLink): static
    {
        $this->photoLink = $photoLink;

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

    public function getRoles(): array
    {
        return [$this->role->getName()];
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function setLoan($loans): static
    {
        $this->loans = $loans;
        return $this;
    }

    public function addLoan(Loan $loan): static
    {
        $this->loans[] = $loan;
        return $this;
    }

    public function getReservation()
    {
        return $this->reservation;
    }

    public function setReservations($reservation): static
    {
        $this->reservation = $reservation;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): static
    {
        return $this;
    }
}
