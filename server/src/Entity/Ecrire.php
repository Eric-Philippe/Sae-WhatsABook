<?php

namespace App\Entity;

use App\Repository\EcrireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcrireRepository::class)]
class Ecrire
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idLivre = null;

    #[ORM\Column(length: 50)]
    private ?string $idAut = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'auteurs')]
    #[ORM\JoinColumn(name: 'idLivre', referencedColumnName: 'id_livre')]
    private $livre;

    #[ORM\ManyToOne(targetEntity: Auteur::class, inversedBy: 'livres')]
    #[ORM\JoinColumn(name: 'idAut', referencedColumnName: 'id_aut')]
    private $auteur;

    public function getId(): ?string
    {
        return $this->idLivre;
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

    public function getIdAut(): ?string
    {
        return $this->idAut;
    }

    public function setIdAut(string $idAut): static
    {
        $this->idAut = $idAut;

        return $this;
    }
}
