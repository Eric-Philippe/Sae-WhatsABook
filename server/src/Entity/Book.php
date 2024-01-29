<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: Book::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 50)]
    private ?string $language = null;

    #[ORM\Column(length: 150)]
    private ?string $coverLink = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'book')]
    #[ORM\JoinTable(name: 'book_category')]
    private $categories;
    
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Reservation::class)]
    private $reservations;
    
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Loan::class)]
    private $loans;
    
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'book')]
    #[ORM\JoinTable(name: 'book_author')]
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

    public function setCategories(?array $categories): static {
        $this->categories = $categories;
        return $this;
    }

    public function setAuthors(?array $authors): static {
        $this->authors = $authors;
        return $this;
    }

    public function getAuteurs() {
        return $this->authors;
    }

    public function setReservations(?array $reservations): static {
        $this->reservations = $reservations;
        return $this;
    }

    public function getReservations(): ?array {
        return $this->reservations;
    }

    public function setLoans(?array $loans): static {
        $this->loans = $loans;
        return $this;
    }

    public function getLoans(): ?array {
        return $this->loans;
    }
}
