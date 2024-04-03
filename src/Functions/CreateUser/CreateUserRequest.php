<?php

namespace App\Functions\CreateUser;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest {
    #[Assert\Email(message: "L'email {{ value }} n'est pas valide")]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    public ?string $email;
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    #[Assert\Regex(pattern: '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[:?!;=]).*$/',message: "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial (?;!:=)")]
    #[Assert\EqualTo("this.password == this.confirmPassword",message: "Les mots de passe ne correspondent pas")]
    public ?string $password;
    #[Assert\NotBlank(message: "Veuillez confirmer le mot de passe")]
    public ?string $confirmPassword;

    /**
     * @param string|null $email
     * @param string|null $password
     * @param string|null $confirmPassword
     */
    public function __construct(?string $email, ?string $password, ?string $confirmPassword) {
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }
}