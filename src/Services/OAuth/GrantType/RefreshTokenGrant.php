<?php


namespace App\Services\OAuth\GrantType;


use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\GrantType\GrantTypeInterface;
use OAuth2\Storage\RefreshTokenInterface;
use OAuth2\ResponseType\AccessTokenInterface;
/**
 * Class RefreshTokenGrant
 * @package App\Services\OAuth\GrantType
 */
final class RefreshTokenGrant implements GrantTypeInterface
{
    private array $config;

    private array $refreshToken;

    private RefreshTokenInterface $storage;

    /**
     * RefreshTokenGrant constructor.
     * @param RefreshTokenInterface $storage
     */
    public function __construct(RefreshTokenInterface $storage)
    {
        $this->config = [
            'unset_refresh_token_after_use' => true,
            'always_issue_new_refresh_token' => true
        ];
        $this->storage = $storage;
    }

    /**
     * Get query string identifier
     *
     * @return string
     */
    public function getQueryStringIdentifier(): string
    {
        // TODO: Implement getQueryStringIdentifier() method.
        return 'refresh_token';
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool|null
     */
    public function validateRequest(RequestInterface $request, ResponseInterface $response): ?bool
    {
        // TODO: Implement validateRequest() method.
        if (!$request->request("refresh_token")) {
            $response->setError(400, 'invalid_request', 'Missing parameter: "refresh_token" is required');
            return null;
        }

        $refreshToken = $this->storage->getRefreshToken($request->request("refresh_token"));

        if (is_null($refreshToken)) {
            $response->setError(400, 'invalid_grant', 'Invalid refresh token');
            return null;
        }

        if (!$refreshToken) {
            $response->setError(403, 'forbidden', 'Unable to retrieve information');
            return null;
        }

        if ($refreshToken['expires'] > 0 && $refreshToken["expires"] < time()) {
            $response->setError(400, 'invalid_grant', 'Refresh token has expired');
            return null;
        }

        $this->refreshToken = $refreshToken;
        return true;
    }

    /**
     * Get client id
     *
     * @return string|null
     */
    public function getClientId(): ?string
    {
        // TODO: Implement getClientId() method.
        return $this->refreshToken['client_id'];
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        // TODO: Implement getUserId() method.
        return $this->refreshToken['user_id'] ?? null;
    }

    /**
     * Get scope
     *
     * @return string|null
     */
    public function getScope(): ?string
    {
        // TODO: Implement getScope() method.
        return $this->refreshToken['scope'] ?? null;
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
        $removedRefresh = false;
        $unsetRefreshToken = $this->config['unset_refresh_token_after_use'];
        $issueNewRefreshToken = $this->config['always_issue_new_refresh_token'];

        if ($unsetRefreshToken) {
            $removedRefresh =  $this->storage->unsetRefreshToken($this->refreshToken['refresh_token']);
        }

        if ($unsetRefreshToken && $removedRefresh) {
            return $accessToken->createAccessToken($client_id, $user_id, $scope, $issueNewRefreshToken);
        }

        if (!$unsetRefreshToken) {
            return $accessToken->createAccessToken($client_id, $user_id, $scope, $issueNewRefreshToken);
        }

        return array('error_create_access_token');
    }

}
