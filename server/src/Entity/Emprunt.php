<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idEmp = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRetourLimite = null;

    #[ORM\Column(length: 50)]
    private ?string $idLivre = null;

    #[ORM\Column(length: 50)]
    private ?string $idAdherent = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(name: 'idLivre', referencedColumnName: 'id_livre')]
    private $livre;

    #[ORM\ManyToOne(targetEntity: Adherent::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(name: 'idAdherent', referencedColumnName: 'id_adherent')]
    private $adherent;

    public function getId(): ?string
    {
        return $this->idEmp;
    }
    public function setIdEmp(string $idEmp): static
    {
        $this->idEmp = $idEmp;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): static
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getDateRetourLimite(): ?\DateTimeInterface
    {
        return $this->dateRetourLimite;
    }

    public function setDateRetourLimite(\DateTimeInterface $dateRetourLimite): static
    {
        $this->dateRetourLimite = $dateRetourLimite;

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

    public function getIdAdherent(): ?string
    {
        return $this->idAdherent;
    }

    public function setIdAdherent(string $idAdherent): static
    {
        $this->idAdherent = $idAdherent;

        return $this;
    }
}
