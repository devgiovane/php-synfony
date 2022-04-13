<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class Login
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\LoginRepository")
 * @ORM\Table(name="login")
 */
class Login implements \JsonSerializable
{
    /**
     * @var int $id
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string $name
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private string $email;

    /**
     * @var string $name
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private string $username;

    /**
     * @var string $name
     * @ORM\Column(type="string", nullable=false)
     */
    private string $password;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Login
     */
    public function setId(int $id): Login
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Login
     */
    public function setEmail(string $email): Login
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Login
     */
    public function setUsername(string $username): Login
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Login
     */
    public function setPassword(string $password): Login
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
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword()
        ];
    }
}
