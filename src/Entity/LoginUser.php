<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class LoginUser
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="login_user")
 */
class LoginUser implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var Login
     * @ORM\ManyToOne(targetEntity="Login")
     * @ORM\JoinColumn(name="login_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private Login $login;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private User $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LoginUser
     */
    public function setId(int $id): LoginUser
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Login
     */
    public function getLogin(): Login
    {
        return $this->login;
    }

    /**
     * @param Login $login
     * @return LoginUser
     */
    public function setLogin(Login $login): LoginUser
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return LoginUser
     */
    public function setUser(User $user): LoginUser
    {
        $this->user = $user;
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

        ];
    }
}
