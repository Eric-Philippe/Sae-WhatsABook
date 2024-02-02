<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[Groups(['getBooks', 'getMemberReservation', 'getBookReservation'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['getBooks', 'getMemberReservation', 'getBookReservation'])]
    private ?\DateTimeInterface $dateResa = null;

    #[ORM\OneToOne(targetEntity: Book::class, inversedBy: 'reservation')]
    #[ORM\JoinColumn(name: 'book_id', referencedColumnName: 'id')]
    #[Groups(['getMemberReservation'])]
    private $book;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'reservation')]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'id')]
    #[Groups(['getBookReservation'])]
    private ?Member $member = null;


    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDateResa(): ?\DateTimeInterface
    {
        return $this->dateResa;
    }

    public function setDateResa(\DateTimeInterface $dateResa): static
    {
        $this->dateResa = $dateResa;

        return $this;
    }

    public function getBook()
    {
        return $this->book;
    }

    public function setBook($book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }
}
