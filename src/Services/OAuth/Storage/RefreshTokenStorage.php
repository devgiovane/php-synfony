<?php


namespace App\Services\OAuth\Storage;


use App\Entity\Login;
use App\Entity\LoginAppClient;
use App\Entity\LoginRefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Storage\RefreshTokenInterface;
/**
 * Class RefreshTokenStorage
 * @package App\Services\OAuth\Storage
 */
final class RefreshTokenStorage implements RefreshTokenInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * RefreshTokenStorage constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Grant refresh access tokens.
     *
     * Retrieve the stored data for the given refresh token.
     *
     * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
     *
     * @param $refresh_token
     * Refresh token to be check with.
     *
     * @return array|null An associative array as below, and NULL if the refresh_token is
     * An associative array as below, and NULL if the refresh_token is
     * invalid:
     * - refresh_token: Refresh token identifier.
     * - client_id: Client identifier.
     * - user_id: User identifier.
     * - expires: Expiration unix timestamp, or 0 if the token doesn't expire.
     * - scope: (optional) Scope values in space-separated string.
     *
     * @see http://tools.ietf.org/html/rfc6749#section-6
     *
     * @ingroup oauth2_section_6
     */
    public function getRefreshToken($refresh_token): ?array
    {
        // TODO: Implement getRefreshToken() method.
        /** @var LoginRefreshToken $refreshTokenEntity */
        $refreshTokenEntity = $this->entityManager->getRepository(LoginRefreshToken::class)->findOneBy(['refreshToken' => $refresh_token]);
        if(!$refreshTokenEntity) {
            return null;
        }

        $data = $refreshTokenEntity->jsonSerialize();
        $loginEntity = $this->entityManager->getRepository(Login::class)->find($data['user_id']);
        if(!$loginEntity) {
            return null;
        }

        $data['expires'] = $data['expires']->getTimestamp();
        return $data;
    }

    /**
     * Take the provided refresh token values and store them somewhere.
     *
     * This function should be the storage counterpart to getRefreshToken().
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
     *
     * @param $refresh_token
     * Refresh token to be stored.
     * @param $client_id
     * Client identifier to be stored.
     * @param $user_id
     * User identifier to be stored.
     * @param $expires
     * Expiration timestamp to be stored. 0 if the token doesn't expire.
     * @param $scope
     * (optional) Scopes to be stored in space-separated string.
     *
     * @ingroup oauth2_section_6
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // TODO: Implement setRefreshToken() method.
        /** @var Login $loginEntity */
        $loginEntity = $this->entityManager->getRepository(Login::class)->find($user_id);
        /** @var LoginAppClient $appClientEntity */
        $appClientEntity = $this->entityManager->getRepository(LoginAppClient::class)->findOneBy(['clientIdentifier' => $client_id]);

        $refreshToken = new LoginRefreshToken();

        $refreshToken
            ->setRefreshToken($refresh_token)
            ->setExpiresIn((new \DateTime())->setTimestamp($expires))
            ->setLoginAppClient($appClientEntity)
            ->setLogin($loginEntity)
            ->setScope($scope);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();
    }

    /**
     * Expire a used refresh token.
     *
     * This is not explicitly required in the spec, but is almost implied.
     * After granting a new refresh token, the old one is no longer useful and
     * so should be forcibly expired in the data store so it can't be used again.
     *
     * If storage fails for some reason, we're not currently checking for
     * any sort of success/failure, so you should bail out of the script
     * and provide a descriptive fail message.
     *
     * @param $refresh_token
     * Refresh token to be expired.
     *
     * @ingroup oauth2_section_6
     */
    public function unsetRefreshToken($refresh_token): bool
    {
        // TODO: Implement unsetRefreshToken() method.
        /** @var LoginRefreshToken $refreshToken */
        $refreshTokenEntity = $this->entityManager->getRepository(LoginRefreshToken::class)->findOneBy(['refreshToken' => $refresh_token]);
        if($refreshTokenEntity) {
            $this->entityManager->remove($refreshToken);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
}
