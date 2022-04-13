<?php


namespace App\Services\OAuth;


use OAuth2\Server;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\OAuth\Bridge\ResponseBridge;

/**
 * Class OAuthService
 * @package App\Services\OAuth
 */
final class OAuthService
{
    /**
     * @var ServerManager
     */
    private ServerManager $serverManager;

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
        $this->serverManager = new ServerManager($entityManager);
        $this->server = $this->serverManager->getServer();
    }

    /**
     * @return ResponseBridge
     */
    public function getResponse(): ResponseBridge
    {
        return $this->responseAdapter;
    }

    /**
     * @param $request
     * @param $scope
     * @return mixed
     */
    public function validateScope($request, $scope): mixed
    {
        $this->responseAdapter = $this->serverManager->getResponse();
        $OAuthRequest = $this->serverManager->getRequest($request);
        return $this->server->verifyResourceRequest($OAuthRequest, $this->responseAdapter, $scope);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getData($request): mixed
    {
        $this->responseAdapter = $this->serverManager->getResponse();
        $OAuthRequest = $this->serverManager->getRequest($request);
        return $this->server->getAccessTokenData($OAuthRequest, $this->responseAdapter);
    }
}
