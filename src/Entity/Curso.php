<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['curso:read', 'curso:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['curso:read', 'curso:write'])]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'curso', targetEntity: Asinaturas::class)]
    #[Groups(['curso:read', 'curso:write'])]
    private Collection $asignaturas;

    public function __construct()
    {
        $this->asignaturas = new ArrayCollection();
    }

    #[Groups(['curso:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['curso:read'])]
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return Collection<int, Asinaturas>
     */
    #[Groups(['curso:read'])]
    public function getAsignaturas(): Collection
    {
        return $this->asignaturas;
    }

    public function addAsignatura(Asinaturas $asignatura): static
    {
        if (!$this->asignaturas->contains($asignatura)) {
            $this->asignaturas->add($asignatura);
            $asignatura->setCurso($this); // Set the owning side of the relation
        }
        return $this;
    }

    public function removeAsignatura(Asinaturas $asignatura): static
    {
        if ($this->asignaturas->removeElement($asignatura)) {
            // Set the owning side to null (unless already changed)
            if ($asignatura->getCurso() === $this) {
                $asignatura->setCurso(null);
            }
        }
        return $this;
    }
}
