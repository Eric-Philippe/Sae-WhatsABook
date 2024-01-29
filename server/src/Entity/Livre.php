<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $idLivre = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 50)]
    private ?string $langue = null;

    #[ORM\Column(length: 150)]
    private ?string $couvertureLink = null;

    /**
     * @var ArrayCollection|Categorie[]
     *
     * @ORM\ManyToMany(targetEntity="Categorie", inversedBy="livres")
     * @ORM\JoinTable(name="Appartenir",
     *      joinColumns={@ORM\JoinColumn(name="idLivre", referencedColumnName="id_livre")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="idCat", referencedColumnName="id_cat")}
     * )
     */
    private $categories;
    
    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Reservation::class)]
    private $reservations;
    
    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class)]
    private $emprunts;
    
    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Appartenir::class)]
    private $appartenances;
    
    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Ecrire::class)]
    private $auteurs;
    

    public function getId(): ?string
    {
        return $this->idLivre;
    }

    public function setIdLivre(string $idLivre): static
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getCouvertureLink(): ?string
    {
        return $this->couvertureLink;
    }

    public function setCouvertureLink(string $couvertureLink): static
    {
        $this->couvertureLink = $couvertureLink;

        return $this;
    }
}
