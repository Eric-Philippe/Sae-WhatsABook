<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 50)]
    private ?string $idAdherent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAdhesion = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaiss = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 80)]
    private ?string $adressePostale = null;

    #[ORM\Column(length: 10)]
    private ?string $numTel = null;

    #[ORM\Column(length: 255)]
    private ?string $photoLink = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $idRole = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'adherents')]
    #[ORM\JoinColumn(name: 'idRole', referencedColumnName: 'id_role')]
    private $role;

    #[ORM\OneToMany(mappedBy: 'adherent', targetEntity: Reservation::class)]
    private $reservations;

    #[ORM\OneToMany(mappedBy: 'adherent', targetEntity: Emprunt::class)]
    private $emprunts;

    public function getId(): ?string
    {
        return $this->idAdherent;
    }

    public function setIdAdherent(string $idAdherent): static
    {
        $this->idAdherent = $idAdherent;

        return $this;
    }

    public function getDateAdhesion(): ?\DateTimeInterface
    {
        return $this->dateAdhesion;
    }

    public function setDateAdhesion(\DateTimeInterface $dateAdhesion): static
    {
        $this->dateAdhesion = $dateAdhesion;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(\DateTimeInterface $dateNaiss): static
    {
        $this->dateNaiss = $dateNaiss;

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

    public function getAdressePostale(): ?string
    {
        return $this->adressePostale;
    }

    public function setAdressePostale(string $adressePostale): static
    {
        $this->adressePostale = $adressePostale;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): static
    {
        $this->numTel = $numTel;

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

    public function getIdRole(): ?string
    {
        return $this->idRole;
    }

    public function setIdRole(string $idRole): static
    {
        $this->idRole = $idRole;

        return $this;
    }
}
