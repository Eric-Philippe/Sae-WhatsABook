<?php

namespace App\Entity;

use App\Repository\CategoryRepositoy;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepositoy::class)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'category')]
    private $books;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $idCat): Category
    {
        $this->id = $idCat;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBooks() 
    {
        return $this->books;
    }
}
