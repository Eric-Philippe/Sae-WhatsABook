<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $permissionRank = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: Member::class)]
    private $members;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

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
        return $this->permissionRank;
    }

    public function setPermissionRang(int $permissionRang): static
    {
        $this->permissionRank = $permissionRang;

        return $this;
    }
}
