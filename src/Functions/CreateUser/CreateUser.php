<?php

namespace App\Functions\CreateUser;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUser {
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function execute(CreateUserRequest $requete, UserPasswordHasherInterface $passwordHasher): bool {
        $errors = $this->validator->validate($requete);
        if (count($errors) > 0) {
            $messageError = "";
            foreach ($errors as $message) {
                $messageError .= $message->getMessage() . ". ";
            }
            throw new \Exception($messageError);
        }

        if ($this->repository->findOneBy(["email" => $requete->email])) {
            throw new \Exception("L'email est déjà utilisé");
        }

        $user = new User();
        $user->setEmail($requete->email);
        $user->setRoles(["ROLE_USER"]);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $requete->password
        );
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }
}