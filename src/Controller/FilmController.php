<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class FilmController extends AbstractController {
    #[Route('/films', name: 'api_film_index', methods: ['GET'])]
    #[OA\Tag(name: 'Films')]
    #[OA\Get(
        path: '/api/films',
        description: 'Récupère la liste de tous les films à l\'affiche.',
        summary: 'Lister les films à l\'affiche',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des films à l\'affiche au format json',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(type: Film::class)
                    )
                )
            )
        ]
    )]
    public function index(FilmRepository      $filmRepository,
                          SerializerInterface $serializer): Response {
        $filmsAffiche = $filmRepository->findAllFilmsAffiche();

        $json = $serializer->serialize($filmsAffiche, "json");

        return new Response($json, 200, [
            'content-type' => 'application/json'
        ]);
    }

    #[OA\Tag(name: 'Films')]
    #[OA\Get(
        path: '/api/films/{id}',
        description: 'Récupère un film à l\'affiche par son id.',
        summary: 'Récupère un film à l\'affiche',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Id du film à l\'affiche à rechercher',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détails d'un film au format json",
                content: new OA\JsonContent(
                    ref: new Model(type: Film::class)
                )
            )
        ]
    )]
    #[Route('/films/{id}', name: 'api_film_find', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(FilmRepository      $filmRepository,
                         SerializerInterface $serializer,
                         int                 $id): Response {
        $film = $filmRepository->findOneFilm($id);

        $json = $film ? $serializer->serialize($film, 'json') : $serializer->serialize(["code" => 404, "message" => "Le film n'existe pas ou n'est pas à l'affiche"],
            'json');

        return new Response($json, 200, [
            'content-type' => 'application/json'
        ]);
    }
}
