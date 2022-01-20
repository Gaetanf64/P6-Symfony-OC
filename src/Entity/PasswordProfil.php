<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


class PasswordProfil
{

    /**
     * @Assert\NotBlank(
     *      message = "Ce champs est requis."
     * )
     * @SecurityAssert\UserPassword(
     *      message = "Le mot de passe n'est pas valide."
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Length(
     *      min = 8,
     *      max = 254,
     *      minMessage = "Votre mot de passe doit contenir au moins 8 caractères.",
     *      maxMessage = "Votre mot de passe ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     * @Assert\Regex(
     *     pattern = "^(?=.*[a-z])(?=.*[A-Z])^",
     *     match = true,
     *     message = "Le mot de passe doit contenir au moins une minuscule et une majuscule !"
     * )
     */
    private $newPasswordProfil;

    /**
     * @Assert\EqualTo(
     *      propertyPath = "newPasswordProfil",
     *      message = "Le mot de passe n'est pas identique."
     * )
     */
    private $confirmPasswordProfil;


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNewPasswordProfil(): ?string
    {
        return $this->newPasswordProfil;
    }

    public function setNewPasswordProfil(string $newPasswordProfil): self
    {
        $this->newPasswordProfil = $newPasswordProfil;

        return $this;
    }

    public function getConfirmPasswordProfil(): ?string
    {
        return $this->confirmPasswordProfil;
    }

    public function setConfirmPasswordProfil(string $confirmPasswordProfil): self
    {
        $this->confirmPasswordProfil = $confirmPasswordProfil;

        return $this;
    }
}
