<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class LoginAccessToken
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="login_access_token",
 *      indexes={
 *          @ORM\Index(name="token_idx", columns={"token"})
 *     }
 * )
 */
class LoginAccessToken implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false, unique=true)
     */
    private string $token;

    /**
     * @var Login
     * @ORM\ManyToOne(targetEntity="Login")
     * @ORM\JoinColumn(name="login_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private Login $login;

    /**
     * @var LoginAppClient
     * @ORM\ManyToOne(targetEntity="LoginAppClient")
     * @ORM\JoinColumn(name="appclient_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private LoginAppClient $loginAppClient;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private \DateTime $expiresIn;

    /**
     * @var ?string
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $scope;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LoginAccessToken
     */
    public function setId(int $id): LoginAccessToken
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return LoginAccessToken
     */
    public function setToken(string $token): LoginAccessToken
    {
        $this->token = $token;
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
     * @return LoginAccessToken
     */
    public function setLogin(Login $login): LoginAccessToken
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return LoginAppClient
     */
    public function getLoginAppClient(): LoginAppClient
    {
        return $this->loginAppClient;
    }

    /**
     * @param LoginAppClient $loginAppClient
     * @return LoginAccessToken
     */
    public function setLoginAppClient(LoginAppClient $loginAppClient): LoginAccessToken
    {
        $this->loginAppClient = $loginAppClient;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresIn(): \DateTime
    {
        return $this->expiresIn;
    }

    /**
     * @param \DateTime $expiresIn
     * @return LoginAccessToken
     */
    public function setExpiresIn(\DateTime $expiresIn): LoginAccessToken
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     * @return LoginAccessToken
     */
    public function setScope(?string $scope): LoginAccessToken
    {
        $this->scope = $scope;
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
            'token' => $this->getToken(),
            'login' => $this->getLogin()->getId(),
            'client_id' => $this->getLoginAppClient()->getClientIdentifier(),
            'expires' => $this->getExpiresIn(),
            'scope' => $this->getScope()
        ];
    }
}
