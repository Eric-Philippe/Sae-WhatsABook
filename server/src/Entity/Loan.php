<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $loanDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $maxDateReturnLimit = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'loan')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    private $book;

    #[ORM\ManyToOne(targetEntity: Loan::class, inversedBy: 'loan')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    private $member;

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setIdEmp(string $idEmp): static
    {
        $this->id = $idEmp;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->loanDate;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->loanDate = $dateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): static
    {
        $this->returnDate = $dateRetour;

        return $this;
    }

    public function getDateRetourLimite(): ?\DateTimeInterface
    {
        return $this->maxDateReturnLimit;
    }

    public function setDateRetourLimite(\DateTimeInterface $dateRetourLimite): static
    {
        $this->maxDateReturnLimit = $dateRetourLimite;

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
}
