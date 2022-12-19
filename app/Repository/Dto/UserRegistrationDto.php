<?php

namespace App\Repository\Dto;

class UserRegistrationDto
{
    private string $name;
    private string $email;
    private ?string $phoneNumber;
    private string $password;

    public function __construct(
        string $name,
        string $email,
        ?string $phoneNumber,
        string $password
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['phone_number'],
            $data['password']
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
