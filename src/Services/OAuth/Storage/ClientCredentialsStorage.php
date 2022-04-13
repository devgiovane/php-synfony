<?php


namespace App\Services\OAuth\Storage;


use App\Entity\LoginAppClient;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Storage\ClientCredentialsInterface;
/**
 * Class ClientCredentialsStorage
 * @package App\Services\OAuth\Storage
 */
final class ClientCredentialsStorage implements ClientCredentialsInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * ClientCredentialsStorage constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Make sure that the client credentials is valid.
     *
     * @param $client_id
     * Client identifier to be check with.
     * @param null $client_secret
     * (optional) If a secret is required, check that they've given the right one.
     *
     * @return bool|null TRUE if the client credentials are valid, and MUST return FALSE if it isn't.
     * TRUE if the client credentials are valid, and MUST return FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-3.1
     *
     * @ingroup oauth2_section_3
     */
    public function checkClientCredentials($client_id, $client_secret = null): ?bool
    {
        // TODO: Implement checkClientCredentials() method.
        /** @var LoginAppClient $clientEntity */
        $clientEntity = $this->entityManager->getRepository(LoginAppClient::class)->findOneBy(['clientIdentifier' => $client_id, 'clientSecret' => $client_secret]);
        if($clientEntity) {
            return true;
        }
        return false;
    }

    /**
     * Determine if the client is a "public" client, and therefore
     * does not require passing credentials for certain grant types
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return bool TRUE if the client is public, and FALSE if it isn't.
     * TRUE if the client is public, and FALSE if it isn't.
     * @endcode
     *
     * @see http://tools.ietf.org/html/rfc6749#section-2.3
     * @see https://github.com/bshaffer/oauth2-server-php/issues/257
     *
     * @ingroup oauth2_section_2
     */
    public function isPublicClient($client_id): bool
    {
        // TODO: Implement isPublicClient() method.
        return true;
    }

    /**
     * Get client details corresponding client_id.
     *
     * OAuth says we should store request URIs for each registered client.
     * Implement this function to grab the stored URI for a given client id.
     *
     * @param $client_id
     * Client identifier to be check with.
     *
     * @return array
     *               Client details. The only mandatory key in the array is "redirect_uri".
     *               This function MUST return FALSE if the given client does not exist or is
     *               invalid. "redirect_uri" can be space-delimited to allow for multiple valid uris.
     *               <code>
     *               return array(
     *               "redirect_uri" => REDIRECT_URI,      // REQUIRED redirect_uri registered for the client
     *               "client_id"    => CLIENT_ID,         // OPTIONAL the client id
     *               "grant_types"  => GRANT_TYPES,       // OPTIONAL an array of restricted grant types
     *               "user_id"      => USER_ID,           // OPTIONAL the user identifier associated with this client
     *               "scope"        => SCOPE,             // OPTIONAL the scopes allowed for this client
     *               );
     *               </code>
     *
     * @ingroup oauth2_section_4
     */
    public function getClientDetails($client_id): array
    {
        // TODO: Implement getClientDetails() method.
        /** @var LoginAppClient $clientEntity */
        $clientEntity = $this->entityManager->getRepository(LoginAppClient::class)->findOneBy(['clientIdentifier' => $client_id, 'clientSecret' => $client_secret]);
        if ($clientEntity) {
            return $clientEntity->jsonSerialize();
        }
        return array();
    }

    /**
     * Get the scope associated with this client
     *
     * @param $client_id
     * @return string|null STRING the space-delineated scope list for the specified client_id
     * STRING the space-delineated scope list for the specified client_id
     */
    public function getClientScope($client_id): ?string
    {
        // TODO: Implement getClientScope() method.
        return null;
    }

    /**
     * Check restricted grant types of corresponding client identifier.
     *
     * If you want to restrict clients to certain grant types, override this
     * function.
     *
     * @param $client_id
     * Client identifier to be check with.
     * @param $grant_type
     * Grant type to be check with
     *
     * @return bool TRUE if the grant type is supported by this client identifier, and
     * TRUE if the grant type is supported by this client identifier, and
     * FALSE if it isn't.
     *
     * @ingroup oauth2_section_4
     */
    public function checkRestrictedGrantType($client_id, $grant_type): bool
    {
        // TODO: Implement checkRestrictedGrantType() method.
        return true;
    }
}
