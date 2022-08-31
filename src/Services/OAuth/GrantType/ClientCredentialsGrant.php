<?php


namespace App\Services\OAuth\GrantType;


use OAuth2\ClientAssertionType\HttpBasic;
use OAuth2\GrantType\GrantTypeInterface;
use OAuth2\ResponseType\AccessTokenInterface;
use OAuth2\Storage\ClientCredentialsInterface;
/**
 * Class ClientCredentialsGrant
 * @package App\Services\OAuth\GrantType
 */
final class ClientCredentialsGrant extends HttpBasic implements GrantTypeInterface
{
    private $clientData;

    /**
     * ClientCredentialsGrant constructor.
     * @param ClientCredentialsInterface $storage
     * @param array $config
     */
    public function __construct(ClientCredentialsInterface $storage, array $config = array())
    {
        $config['allow_public_clients'] = false;
        parent::__construct($storage, $config);
    }

    /**
     * Get query string identifier
     *
     * @return string
     */
    public function getQueryStringIdentifier(): string
    {
        // TODO: Implement getQueryStringIdentifier() method.
        return 'client_credentials';
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        // TODO: Implement getUserId() method.
        $this->loadClientData();
        return $this->clientData['user_id'] ?? null;
    }

    /**
     * Get scope
     *
     * @return string|null
     */
    public function getScope(): ?string
    {
        // TODO: Implement getScope() method.
        $this->loadClientData();
        return $this->clientData['scope'] ?? null;
    }

    /**
     * Create access token
     *
     * @param AccessTokenInterface $accessToken
     * @param mixed $client_id - client identifier related to the access token.
     * @param mixed $user_id - user id associated with the access token
     * @param string $scope - scopes to be stored in space-separated string.
     * @return array
     */
    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope): array
    {
        // TODO: Implement createAccessToken() method.
        $includeRefreshToken = false;
        return $accessToken->createAccessToken($client_id, $user_id, $scope, $includeRefreshToken);
    }

    /**
     * Load client data
     */
    private function loadClientData(): void
    {
        if (empty($this->clientData)) {
            $this->clientData = $this->storage->getClientDetails($this->getClientId());
        }
    }
}
