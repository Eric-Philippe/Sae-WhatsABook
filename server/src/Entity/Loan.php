<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[Groups(['getBooks', 'getLoans'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['getBooks', 'getLoans'])]
    private ?\DateTimeInterface $loanDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['getBooks', 'getLoans'])]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'loan')]
    #[ORM\JoinColumn(name: 'book_id', referencedColumnName: 'id')]
    #[Groups(['getLoans'])]
    private $book;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'loans')]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'id')]
    private ?Member $member = null;

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

    public function getLoanDate(): ?\DateTimeInterface
    {
        return $this->loanDate;
    }

    public function setLoanDate(\DateTimeInterface $dateEmprunt): static
    {
        $this->loanDate = $dateEmprunt;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(\DateTimeInterface $dateRetour): static
    {
        $this->returnDate = $dateRetour;

        return $this;
    }

    public function getBorrowedBook()
    {
        return $this->book;
    }

    public function setBorrowedBook($book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getMember()
    {
        return $this->member;
    }

    public function setMember($member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getBook()
    {
        return $this->book;
    }

    public function getBooks()
    {
        return $this->books;
    }

    public function setBook(Book | ArrayCollection | null $book): static
    {
        if ($book === null) {
            return $this;
        }
        // If the type is Book
        if ($book instanceof Book) {
            $this->book = $book;
            return $this;
        }
       $this->books = $book;
       $this->book = $book[0];

        return $this;
    }

    public function getDelay() {
        if ($this->returnDate === null) {
            $today = new \DateTime();
            $diff = $today->diff($this->loanDate);
            return $diff->format('%a jours');
        } else {
            // If returnDate is not null, we calculate the delay from loanDate to returnDate
            $diff = $this->returnDate->diff($this->loanDate);
            return $diff->format('%a jours');
        }
    }

    public function isReturnDateNull(): bool
    {
        return $this->returnDate === null;
    }

    /**
     * To string
     */
    public function __toString(): string
    {
        return 'Loan ID: ' . $this->id . ', Loan Date: ' . $this->loanDate->format('Y-m-d') . ', Return Date: ' . ($this->returnDate ? $this->returnDate->format('Y-m-d') : 'Not returned yet');
    }
}
