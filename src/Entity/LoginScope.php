<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class LoginScope
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="login_scope",
 *      indexes={
 *          @ORM\Index(name="scope_idx", columns={"scope"})
 *      }
 * )
 */
class LoginScope
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected string $scope;

    /**
     * @var ?string
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LoginScope
     */
    public function setId(int $id): LoginScope
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return LoginScope
     */
    public function setScope(string $scope): LoginScope
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return LoginScope
     */
    public function setDescription(?string $description): LoginScope
    {
        $this->description = $description;
        return $this;
    }

}
