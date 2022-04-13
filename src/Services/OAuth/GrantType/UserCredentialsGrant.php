<?php


namespace App\Services\OAuth\GrantType;


use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\GrantType\GrantTypeInterface;
use OAuth2\Storage\UserCredentialsInterface;
use OAuth2\ResponseType\AccessTokenInterface;
/**
 * Class UserCredentialsGrant
 * @package App\Services\OAuth\GrantType
 */
final class UserCredentialsGrant implements GrantTypeInterface
{
    private array $userInfo;

    private UserCredentialsInterface $storage;

    public function __construct(UserCredentialsInterface $storage)
    {
        $this->storage = $storage;
        $this->userInfo = array();
    }

    /**
     * Get query string identifier
     *
     * @return string
     */
    public function getQueryStringIdentifier(): string
    {
        // TODO: Implement getQueryStringIdentifier() method.
        return 'password';
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function validateRequest(RequestInterface $request, ResponseInterface $response): mixed
    {
        // TODO: Implement validateRequest() method.
        if (!$request->request("password") || !$request->request("username")) {
            $response->setError(400, 'invalid_request', 'Missing parameters: "username" and "password" required');
            return null;
        }

        $checkUserCredentials = $this->storage->checkUserCredentials($request->request("username"), $request->request("password"));

        if (!$checkUserCredentials) {
            $response->setError(401, 'invalid_grant', 'Invalid username and password combination');
            return null;
        }

        $userInfo = $this->storage->getUserDetails($request->request("username"));

        if (empty($userInfo)) {
            $response->setError(400, 'invalid_grant', 'Unable to retrieve user information');
            return null;
        }

        if (!isset($userInfo['user_id'])) {
            throw new \LogicException("you must set the user_id on the array returned by getUserDetails");
        }

        $this->userInfo = $userInfo;
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
        return null;
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        // TODO: Implement getUserId() method.
        return $this->userInfo['user_id'];
    }

    /**
     * Get scope
     *
     * @return string|null
     */
    public function getScope(): ?string
    {
        // TODO: Implement getScope() method.
        return $this->userInfo['scope'] ?? null;
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
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }
}
