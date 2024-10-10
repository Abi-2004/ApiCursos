<?php

namespace App\Entity;

use App\Repository\AsinaturasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AsinaturasRepository::class)]
class Asinaturas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['asinaturas:read', 'asinaturas:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['asinaturas:read', 'asinaturas:write'])]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['asinaturas:read', 'asinaturas:write'])]
    private ?int $horas = null;

    #[ORM\ManyToOne(targetEntity: Curso::class, inversedBy: 'asignaturas')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['asinaturas:read', 'asinaturas:write'])]
    private ?Curso $curso = null;

    // Getters y Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getHoras(): ?int
    {
        return $this->horas;
    }

    public function setHoras(?int $horas): static
    {
        $this->horas = $horas;
        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): static
    {
        $this->curso = $curso;
        return $this;
    }
}
