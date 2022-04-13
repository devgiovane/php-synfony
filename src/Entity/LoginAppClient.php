<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 * @ORM\Table(name="login_appclient",
 *      indexes={
 *          @ORM\Index(name="client_indetifier_idx", columns={"client_identifier"})
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="client_identifier_unique", columns={"client_identifier"})
 *     }
 * )
 */
class LoginAppClient implements \JsonSerializable
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
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $clientIdentifier;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $clientSecret;

    /**
     * @var ?string
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $redirectUri;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LoginAppClient
     */
    public function setId(int $id): LoginAppClient
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }

    /**
     * @param string $clientIdentifier
     * @return LoginAppClient
     */
    public function setClientIdentifier(string $clientIdentifier): LoginAppClient
    {
        $this->clientIdentifier = $clientIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     * @return LoginAppClient
     */
    public function setClientSecret(string $clientSecret): LoginAppClient
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    /**
     * @param string|null $redirectUri
     * @return LoginAppClient
     */
    public function setRedirectUri(?string $redirectUri): LoginAppClient
    {
        $this->redirectUri = $redirectUri;
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
            'client_id' => $this->getClientIdentifier(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri' => $this->getRedirectUri(),
            'scope' => null
        ];
    }

}
