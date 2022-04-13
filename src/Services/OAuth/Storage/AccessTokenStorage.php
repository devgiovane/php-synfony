<?php


namespace App\Services\OAuth\Storage;


use App\Entity\Login;
use App\Entity\LoginAccessToken;
use App\Entity\LoginAppClient;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Storage\AccessTokenInterface;
/**
 * Class AccessTokenStorage
 * @package App\Services\OAuth\Storage
 */
final class AccessTokenStorage implements AccessTokenInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Look up the supplied oauth_token from storage.
     *
     * We need to retrieve access token data as we create and verify tokens.
     *
     * @param string $oauth_token - oauth_token to be check with.
     *
     * @return array|null - An associative array as below, and return NULL if the supplied oauth_token is invalid:
     * @code
     *     array(
     *         'expires'   => $expires,   // Stored expiration in unix timestamp.
     *         'client_id' => $client_id, // (optional) Stored client identifier.
     *         'user_id'   => $user_id,   // (optional) Stored user identifier.
     *         'scope'     => $scope,     // (optional) Stored scope values in space-separated string.
     *         'id_token'  => $id_token   // (optional) Stored id_token (if "use_openid_connect" is true).
     *     );
     * @endcode
     *
     * @ingroup oauth2_section_7
     */
    public function getAccessToken($oauth_token): ?array
    {
        // TODO: Implement getAccessToken() method.
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('lat.token', 'lac.clientIdentifier as client_id', 'l.id as user_id', 'lat.expiresIn as expires', 'lat.scope as scope')
            ->from(LoginAccessToken::class, 'lat')
            ->innerJoin(LoginAppClient::class, 'lac', 'WITH', 'lac.id = lat.loginAppClient')
            ->leftJoin(Login::class, 'l', 'WITH', 'l.id = lat.login')
            ->where(
                $qb->expr()->eq('lat.token', ':token')
            )
            ->setParameter('token', $oauth_token);

        $q = $qb->getQuery();
        try {
            $token = $q->getSingleResult();
        } catch (\Exception $exception) {
            unset($exception);
            $token = null;
        }
        if($token) {
            $token['expires'] = $token['expires']->getTimestamp();
        }
        return $token;
    }

    /**
     * Store the supplied access token values to storage.
     *
     * We need to store access token data as we create and verify tokens.
     *
     * @param string $oauth_token - oauth_token to be stored.
     * @param mixed $client_id - client identifier to be stored.
     * @param mixed $user_id - user identifier to be stored.
     * @param int $expires - expiration to be stored as a Unix timestamp.
     * @param string $scope - OPTIONAL Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_4
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null): void
    {
        // TODO: Implement setAccessToken() method.
        $accessToken = new LoginAccessToken();
        /** @var Login $loginEntity */
        $loginEntity = $this->entityManager->getRepository(Login::class)->find($user_id);
        /** @var LoginAppClient $appClientEntity */
        $appClientEntity = $this->entityManager->getRepository(LoginAppClient::class)->findOneBy(['clientIdentifier' => $client_id]);

        $accessToken
            ->setToken($oauth_token)
            ->setExpiresIn((new \DateTime())->setTimestamp($expires))
            ->setLoginAppClient($appClientEntity)
            ->setLogin($loginEntity)
            ->setScope($scope);

        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();
    }
}
