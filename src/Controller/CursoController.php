<?php

namespace App\Controller;

use App\Entity\Asinaturas;
use App\Entity\Curso;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CursoController extends AbstractController
{
    #[Route('/curso', name: 'app_curso', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CursoController.php',
        ]);
    }

    #[Route('/curso/add-asignatura', name: 'add_asignatura', methods: ['POST'])]
    public function addAsignatura(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nombre = $request->request->get('nombre');
        $horas = $request->request->get('horas');
        $cursoId = $request->request->get('curso_id');

        if (!$nombre || !$cursoId) {
            return $this->json(['error' => 'Nombre y curso_id son requeridos'], 400);
        }

        $curso = $entityManager->getRepository(Curso::class)->find($cursoId);
        if (!$curso) {
            return $this->json(['error' => 'Curso no encontrado'], 404);
        }

        $asignatura = new Asinaturas();
        $asignatura->setNombre($nombre);
        $asignatura->setHoras($horas);
        $asignatura->setCurso($curso);

        $entityManager->persist($asignatura);
        $entityManager->flush();

        return $this->json(['message' => 'Asignatura añadida exitosamente'], 201);
    }

    #[Route('/curso/add-curso', name: 'add_curso', methods: ['POST'])]
    public function addCurso(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nombre = $request->request->get('nombre');

        if (!$nombre) {
            return $this->json(['error' => 'Nombre es requerido'], 400);
        }

        $curso = new Curso();
        $curso->setNombre($nombre);

        $entityManager->persist($curso);
        $entityManager->flush();

        return $this->json(['message' => 'Curso añadido exitosamente'], 201);
    }

    #[Route('/curso/delete-asignatura/{id}', name: 'delete_asignatura', methods: ['DELETE'])]
    public function deleteAsignatura(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $asignatura = $entityManager->getRepository(Asinaturas::class)->find($id);

        if (!$asignatura) {
            return $this->json(['error' => 'Asignatura no encontrada'], 404);
        }

        $entityManager->remove($asignatura);
        $entityManager->flush();

        return $this->json(['message' => 'Asignatura eliminada exitosamente'], 200);
    }

    #[Route('/curso/delete-curso/{id}', name: 'delete_curso', methods: ['DELETE'])]
    public function deleteCurso(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $curso = $entityManager->getRepository(Curso::class)->find($id);

        if (!$curso) {
            return $this->json(['error' => 'Curso no encontrado'], 404);
        }

        $entityManager->remove($curso);
        $entityManager->flush();

        return $this->json(['message' => 'Curso eliminado exitosamente'], 200);
    }

    #[Route('/curso/get-cursos', name: 'get_cursos', methods: ['GET'])]
    public function getCursos(EntityManagerInterface $entityManager): JsonResponse
    {
        $cursos = $entityManager->getRepository(Curso::class)->findAll();
        return $this->json($cursos);
    }

    #[Route('/curso/get-asignaturas', name: 'get_asignaturas', methods: ['GET'])]
    public function getAsignaturas(EntityManagerInterface $entityManager): JsonResponse
    {
        $asignaturas = $entityManager->getRepository(Asinaturas::class)->findAll();
        return $this->json($asignaturas);
    }
}
