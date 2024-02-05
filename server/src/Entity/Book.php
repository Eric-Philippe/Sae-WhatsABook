<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?string $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?string $summary = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?string $language = null;

    #[ORM\Column(length: 150)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?string $coverLink = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["getBooks", "getMemberReservation", "getLoans", "getAuthors"])]
    private ?int $pageNumber = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'book')]
    #[ORM\JoinTable(name: 'book_category')]
    #[Groups(["getBooks", "getLoans", "getAuthors"])]
    #[Assert\Count(
        min: 1,
        max: 3,
        minMessage: "Vous devez spécifier au moins une catégorie",
        maxMessage: "Vous ne pouvez pas spécifier plus de 3 catégories"
    )]
    private $categories;
    
    #[ORM\OneToOne(mappedBy: 'book', targetEntity: Reservation::class)]
    #[Groups(["getBooks"])]
    private $reservation;
    
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Loan::class)]
    #[Groups(["getBooks"])]
    private $loans;
    
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinTable(name: 'book_author')]
    #[Groups(["getBooks", "getLoans"])]
    private $authors;
    

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCoverLink(): ?string
    {
        return $this->coverLink;
    }

    public function setCoverLink(string $coverLink): static
    {
        $this->coverLink = $coverLink;

        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): static
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    public function setCategories(?array $categories): static {
        $this->categories = $categories;
        return $this;
    }

    public function setAuthors(?array $authors): static {
        $this->authors = $authors;
        return $this;
    }

    public function getAuthors()
    {
        if (!$this->authors) {
            $this->authors = new ArrayCollection();
        }
        return $this->authors;
    }

    public function getCategories() {
        if (!$this->categories) {
            $this->categories = new ArrayCollection();
        }
        return $this->categories;
    }

    public function setReservation($reservation): static {
        $this->reservation = $reservation;
        return $this;
    }

    public function getReservation() {
        return $this->reservation;
    }

    public function setLoans(?array $loans): static {
        $this->loans = $loans;
        return $this;
    }

    public function getLoans() {
        return $this->loans;
    }
}
