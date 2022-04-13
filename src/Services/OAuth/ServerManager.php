<?php


namespace App\Services\OAuth;


use OAuth2\Server;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Services\OAuth\Bridge\RequestBridge;
use App\Services\OAuth\Bridge\ResponseBridge;
use App\Services\OAuth\GrantType\RefreshTokenGrant;
use App\Services\OAuth\GrantType\ClientCredentialsGrant;
use App\Services\OAuth\GrantType\UserCredentialsGrant;
use App\Services\OAuth\Storage\ScopeStorage;
use App\Services\OAuth\Storage\AccessTokenStorage;
use App\Services\OAuth\Storage\RefreshTokenStorage;
use App\Services\OAuth\Storage\UserCredentialsStorage;
use App\Services\OAuth\Storage\ClientCredentialsStorage;
/**
 * Class ServerManager
 * @package App\Services\OAuth
 */
final class ServerManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var Server|null
     */
    private ?Server $server;

    /**
     * @var ResponseBridge|null
     */
    private ?ResponseBridge $responseAdapter;

    /**
     * OAuthService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->server = null;
        $this->entityManager = $entityManager;
        $this->responseAdapter = new ResponseBridge();
        $this->setup();
    }

    public function setup(): void
    {
        $userStorage = new UserCredentialsStorage($this->entityManager);
        $scopeStorage = new ScopeStorage($this->entityManager);
        $clientStorage = new ClientCredentialsStorage($this->entityManager);
        $accessTokenStorage = new AccessTokenStorage($this->entityManager);
        $refreshTokenStorage = new RefreshTokenStorage($this->entityManager);

        $storage = [
            'scope' => $scopeStorage,
            'access_token' => $accessTokenStorage,
            'refresh_token' => $refreshTokenStorage,
            'user_credentials' => $userStorage,
            'client_credentials' => $clientStorage,
        ];

        $grantTypes = [
            'refresh_token' => new RefreshTokenGrant($refreshTokenStorage),
            'user_credentials' => new UserCredentialsGrant($userStorage),
            'client_credentials' => new ClientCredentialsGrant($clientStorage),
        ];

        $config = [
            'access_lifetime' => $_ENV['ACCESS_TOKEN_LIFETIME'],
            'refresh_token_lifetime' => $_ENV['REFRESH_TOKEN_LIFETIME']
        ];

        $this->server = new Server($storage, $config, $grantTypes);
    }

    /**
     * @return Server|null
     */
    public function getServer(): ?Server
    {
        return $this->server;
    }

    /**
     * @param Request $request
     * @return RequestBridge|null
     */
    public function getRequest(Request $request): ?RequestBridge
    {
        return RequestBridge::createFromRequest($request);
    }

    /**
     * @return ResponseBridge|null
     */
    public function getResponse(): ?ResponseBridge
    {
        return $this->responseAdapter;
    }

}
