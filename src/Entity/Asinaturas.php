<?php

namespace App\Entity;

use App\Repository\AsinaturasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AsinaturasRepository::class)]
class Asinaturas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?string $nombre = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['asignatura:read', 'asignatura:write'])]
    private ?int $horas = null; // Add horas property

    #[ORM\ManyToOne(inversedBy: 'asignaturas')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['asignatura:read', 'asignatura:write', 'curso:read'])]
    #[MaxDepth(1)]
    private ?Curso $curso = null;

    #[Groups(['asignatura:read', 'curso_id'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['asignatura:read'])]
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    #[Groups(['asignatura:read'])]
    public function getHoras(): ?int // Getter for horas
    {
        return $this->horas;
    }

    public function setHoras(?int $horas): static // Setter for horas
    {
        $this->horas = $horas;
        return $this;
    }

    #[Groups(['asignatura:read', 'curso:read'])]
    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): static
    {
        if ($this->curso !== $curso) {
            $this->curso = $curso;
            if ($curso !== null) {
                $curso->addAsignatura($this);
            }
        }
        return $this;
    }

    #[Groups(['curso_id'])]
    public function getCursoId(): ?int
    {
        return $this->curso ? $this->curso->getId() : null;
    }
}
