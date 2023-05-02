<?php

namespace App\UserModule\Entity;

use App\UserModule\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 13)]
    private string $user_name;

    #[ORM\Column(length: 255)]
    private string $user_password;

    #[ORM\Column(length: 35)]
    private string $user_email;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getUserPassword(): string
    {
        return $this->user_password;
    }

    public function setUserPassword(string $user_password): self
    {
        $this->user_password = $user_password;

        return $this;
    }

    public function getUserEmail(): string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }
}
