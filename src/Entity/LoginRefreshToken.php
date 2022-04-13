<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class LoginRefreshToken
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="login_refresh_token",
 *      indexes={
 *          @ORM\Index(name="refresh_idx", columns={"refresh_token"})
 *     }
 * )
 */
class LoginRefreshToken implements \JsonSerializable
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
    private string $refreshToken;

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
     * @return LoginRefreshToken
     */
    public function setId(int $id): LoginRefreshToken
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return LoginRefreshToken
     */
    public function setRefreshToken(string $refreshToken): LoginRefreshToken
    {
        $this->refreshToken = $refreshToken;
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
     * @return LoginRefreshToken
     */
    public function setLogin(Login $login): LoginRefreshToken
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
     * @return LoginRefreshToken
     */
    public function setLoginAppClient(LoginAppClient $loginAppClient): LoginRefreshToken
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
     * @return LoginRefreshToken
     */
    public function setExpiresIn(\DateTime $expiresIn): LoginRefreshToken
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
     * @return LoginRefreshToken
     */
    public function setScope(?string $scope): LoginRefreshToken
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
            'refresh_token' => $this->getRefreshToken(),
            'client_id' => $this->getLoginAppClient()->getClientIdentifier(),
            'user_id' => $this->getLogin()->getId(),
            'expires' => $this->getExpiresIn(),
            'scope' => $this->getScope()
        ];
    }
}
