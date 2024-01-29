<?php

namespace App\Entity;

use App\Repository\AppartenirRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartenirRepository::class)]
class Appartenir
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idLivre = null;

    #[ORM\Column(length: 50)]
    private ?string $idCat = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'appartenances')]
    #[ORM\JoinColumn(name: 'idLivre', referencedColumnName: 'id_livre')]
    private $livre;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'appartenances')]
    #[ORM\JoinColumn(name: 'idCat', referencedColumnName: 'id_cat')]
    private $categorie;

    public function getId(): ?int
    {
        return $this->idLivre;
    }

    public function setIdLivre(string $idLivre): static
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getIdCat(): ?string
    {
        return $this->idCat;
    }

    public function setIdCat(string $idCat): static
    {
        $this->idCat = $idCat;

        return $this;
    }
}
