<?php


namespace App\Services\OAuth\Storage;


use App\Entity\Login;
use App\Factory\PasswordCrypto;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Storage\UserCredentialsInterface;
/**
 * Class UserCredentialsStorage
 * @package App\Services\OAuth\Storage
 */
final class UserCredentialsStorage implements UserCredentialsInterface
{
    private EntityManagerInterface $entityManager;

    private ?Login $loginEntity;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->loginEntity = null;
        $this->entityManager = $entityManager;
    }

    /**
     * Grant access tokens for basic user credentials.
     *
     * Check the supplied username and password for validity.
     *
     * You can also use the $client_id param to do any checks required based
     * on a client, if you need that.
     *
     * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
     *
     * @param $username
     * Username to be check with.
     * @param $password
     * Password to be check with.
     *
     * @return bool TRUE if the username and password are valid, and FALSE if it isn't.
     * TRUE if the username and password are valid, and FALSE if it isn't.
     * Moreover, if the username and password are valid, and you want to
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.3
     *
     * @ingroup oauth2_section_4
     */
    public function checkUserCredentials($username, $password): bool
    {
        // TODO: Implement checkUserCredentials() method.
        $validPassword = false;
        $this->loginEntity = $this->entityManager->getRepository(Login::class)->findOneByEmailOrUsername($username);
        if($this->loginEntity) {
            $validPassword = PasswordCrypto::verify($password, $this->loginEntity->getPassword());
        }
        if($this->loginEntity && $validPassword) {
            return true;
        }
        return false;
    }

    /**
     * @param string $username - username to get details for
     * @return array|false     - the associated "user_id" and optional "scope" values
     *                           This function MUST return FALSE if the requested user does not exist or is
     *                           invalid. "scope" is a space-separated list of restricted scopes.
     * @code
     *     return array(
     *         "user_id"  => USER_ID,    // REQUIRED user_id to be stored with the authorization code or access token
     *         "scope"    => SCOPE       // OPTIONAL space-separated list of restricted scopes
     *     );
     * @endcode
     */
    public function getUserDetails($username): array|bool
    {
        // TODO: Implement getUserDetails() method.
        if($this->loginEntity) {
            return array(
                'user_id' => $this->loginEntity->getId(),
                'scope' => null
            );
        }
        return false;
    }
}
