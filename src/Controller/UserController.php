<?php

namespace App\Controller;

use App\Entity\User;
use App\Functions\CreateUser\CreateUser;
use App\Functions\CreateUser\CreateUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ValidatorBuilder;

#[Route('/api')]
class UserController extends AbstractController {
    #[Route('/users/register', name: 'app_user_register', methods: ['POST'])]
    #[OA\Tag(name: 'Users')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: User::class,
            example: [
                "email" => "example@gmail.com",
                "password" => "NotAGoodPassword1234!:",
                "confirmPassword" => "NotAGoodPassword1234!:"
            ]
        )
    )]
    #[OA\Post(
        path: '/api/users/register',
        description: 'Créer un utilisateur',
        summary: 'Créer un utilisateur',
        responses: [
            new OA\Response(
                response: 201,
                description: 'Créer un utilisateur et renvoie un code 201',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(type: User::class)
                    )
                )
            )
        ]
    )]
    public function index(Request                     $request,
                          EntityManagerInterface      $entityManager,
                          SerializerInterface         $serializer,
                          UserPasswordHasherInterface $passwordHasher): Response {
        $body = $request->getContent();
        $requete = json_decode($body);

        if (!(isset($requete->email) && isset($requete->password) && isset($requete->confirmPassword))) {
            $json = $serializer->serialize(["code" => 400, "message" => "Il manque des entrées pour créer un utilisateur"],
                'json');
            return new Response($json, 400, [
                'content-type' => 'application/json'
            ]);
        }

        $validator = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
        $createUserRequest = new CreateUserRequest($requete->email, $requete->password, $requete->confirmPassword);
        $createUser = new CreateUser($entityManager, $validator);

        try {
            $createUser->execute($createUserRequest, $passwordHasher);
            $json = $serializer->serialize(["code" => 201, "message" => "L'utilisateur a bien été crée !"],
                'json');
            return new Response($json, 200, [
                'content-type' => 'application/json'
            ]);
        } catch (\Exception $exception) {
            $json = $serializer->serialize(["code" => 400, "message" => $exception->getMessage()],
                'json');
            return new Response($json, 400, [
                'content-type' => 'application/json'
            ]);
        }
    }
}
