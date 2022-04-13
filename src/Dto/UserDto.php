<?php


namespace App\Dto;


final class UserDto implements \JsonSerializable
{
    private ?string $name;

    private ?string $email;

    private ?string $cpf;

    private ?string $password;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return UserDto
     */
    public function setName(?string $name): UserDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserDto
     */
    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    /**
     * @param string|null $cpf
     * @return UserDto
     */
    public function setCpf(?string $cpf): UserDto
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UserDto
     */
    public function setPassword(?string $password): UserDto
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'cpf' => $this->getCpf(),
            'password' => $this->getPassword()
        ];
    }
}
