<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idResa = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateResa = null;

    #[ORM\Column(length: 50)]
    private ?string $idLivre = null;

    #[ORM\Column(length: 50)]
    private ?string $idAdherant = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idLivre', referencedColumnName: 'id_livre')]
    private $livre;

    #[ORM\ManyToOne(targetEntity: Adherent::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idAdherent', referencedColumnName: 'id_adherent')]
    private $adherent;

    public function getId(): ?string
    {
        return $this->idResa;
    }

    public function getIdResa(): ?string
    {
        return $this->idResa;
    }

    public function setIdResa(string $idResa): static
    {
        $this->idResa = $idResa;

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

    public function getIdLivre(): ?string
    {
        return $this->idLivre;
    }

    public function setIdLivre(string $idLivre): static
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getIdAdherant(): ?string
    {
        return $this->idAdherant;
    }

    public function setIdAdherant(string $idAdherant): static
    {
        $this->idAdherant = $idAdherant;

        return $this;
    }
}
