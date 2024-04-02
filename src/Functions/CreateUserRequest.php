<?php

namespace App\Functions;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest {
    #[Assert\Email(message: "L'email {{ value }} n'est pas valide")]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    public ?string $email;
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Regex(pattern: '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[:?!;=]).*$/',message: "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial (?;!:=)")]
    public ?string $mdp;
    #[Assert\NotBlank(message: "Veuillez confirmer le mot de passe")]
    public ?string $confirmMdp;

    /**
     * @param string|null $email
     * @param string|null $mdp
     * @param string|null $confirmMdp
     */
    public function __construct(?string $email, ?string $mdp, ?string $confirmMdp) {
        $this->email = $email;
        $this->mdp = $mdp;
        $this->confirmMdp = $confirmMdp;
    }
}