<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 50)]
    private ?string $idRole = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $permissionRang = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: Adherent::class)]
    private $adherents;

    public function getId(): ?string
    {
        return $this->idRole;
    }

    public function setIdRole(string $idRole): static
    {
        $this->idRole = $idRole;

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

    public function getPermissionRang(): ?int
    {
        return $this->permissionRang;
    }

    public function setPermissionRang(int $permissionRang): static
    {
        $this->permissionRang = $permissionRang;

        return $this;
    }
}
