<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User implements \JsonSerializable
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
     * @ORM\Column(type="string", nullable=false)
     */
    private string $name;

    /**
     * @var string $email
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private string $email;

    /**
     * @var string $cpf
     * @ORM\Column(length=14, type="string", nullable=false, unique=true)
     */
    private string $cpf;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private \DateTime $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false, columnDefinition="DATETIME ON UPDATE CURRENT_TIMESTAMP")
     */
    private \DateTime $updatedAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
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
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     * @return User
     */
    public function setCpf(string $cpf): User
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
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
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'cpf' => $this->getCpf(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
