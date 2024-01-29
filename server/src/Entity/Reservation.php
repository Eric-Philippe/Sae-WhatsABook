<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateResa = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    private $book;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'id')]
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

    public function getMembre(): ?Member
    {
        return $this->member;
    }
}
