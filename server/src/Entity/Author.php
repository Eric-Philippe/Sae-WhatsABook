<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["getBooks", "getLoans"])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["getBooks", "getLoans"])]
    private ?\DateTimeInterface $deathDate = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $nationality = null;

    #[ORM\Column(length: 150)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $photoLink = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getBooks", "getLoans"])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'authors')]
    private $books;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

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

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getDeathDate(): ?\DateTimeInterface
    {
        return $this->deathDate;
    }

    public function setDeathDate(?\DateTimeInterface $deathDate): static
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
