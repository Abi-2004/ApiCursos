<?php

namespace App\Controller;

use App\Entity\Asinaturas;
use App\Repository\AsinaturasRepository;
use App\Repository\CursoRepository;
use App\Entity\Curso;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;

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
    public function addAsignatura(Request $request, AsinaturasRepository $asignaturaR, CursoRepository $cR, SerializerInterface $sr): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'] ?? null;
        $horas = $data['horas'] ?? null;
        $cursoId = $data['curso_id'] ?? null;
    
        if (!$nombre || !$cursoId) {
            return $this->json(['error' => 'Nombre y curso_id son requeridos'], 400);
        }
    
        $curso = $cR->findOneBy(['id' => $cursoId]);
    
        if (!$curso) {
            return $this->json(['error' => 'Curso no encontrado'], 404);
        }
    
        $asignatura = new Asinaturas();
        $asignatura->setNombre($nombre);
        $asignatura->setHoras($horas);
        $asignatura->setCurso($curso); // Correctly set the curso property
    
        $asignaturaR->add($asignatura);
    
        // Corregimos el grupo de serialización
        $data = $sr->serialize($asignatura, 'json', ['groups' => 'asinaturas:read']);
    
        // Indicamos que los datos ya están en formato JSON
        return new JsonResponse($data, 201, [], true);
    }
    

    #[Route('/curso/add-curso', name: 'add_curso', methods: ['POST'])]
    public function addCurso(Request $request, CursoRepository $cursoRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'] ?? null;


        dump($data);
        dump($nombre);

        if (!$nombre) {
            return $this->json(['error' => 'Nombre es requerido'], 400);
        }

        $curso = new Curso();
        $curso->setNombre($nombre);

        $cursoRepository->add($curso);

        $data = $serializer->serialize($curso, 'json', ['groups' => 'curso:read']);

        // Add console log statement
        error_log('Datos añadidos: ' . $data);

        return new JsonResponse($data, 201, [], true);
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
    public function getCursos(CursoRepository $cursoRepository, SerializerInterface $serializer): JsonResponse
    {
        $cursos = $cursoRepository->findAll();
        foreach ($cursos as $curso) {
            $curso->getAsignaturas(); // Ensure 'asignaturas' are loaded
        }
        $data = $serializer->serialize($cursos, 'json', [
            'groups' => 'curso:read',
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['curso']
        ]);
        return new JsonResponse($data, 200, [], true);
    }
    

    #[Route('/curso/get-asignaturas', name: 'get_asignaturas', methods: ['GET'])]
    public function getAsignaturas(AsinaturasRepository $asignaturasRepository, SerializerInterface $serializer): JsonResponse
    {
        $asignaturas = $asignaturasRepository->findAll();
        $data = $serializer->serialize($asignaturas, 'json', ['groups' => 'asignatura:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/curso/get-asignaturas-by-curso/{cursoId}', name: 'get_asignaturas_by_curso', methods: ['GET'])]
    public function getAsignaturasByCursoId(int $cursoId, AsinaturasRepository $asignaturasRepository, SerializerInterface $serializer): JsonResponse
    {
        $asignaturas = $asignaturasRepository->findBy(['curso' => $cursoId]); // Correctly find by 'curso'
        $data = $serializer->serialize($asignaturas, 'json', ['groups' => 'asignatura:read', 'curso_id']);
        return new JsonResponse($data, 200, [], true);
    }
}
