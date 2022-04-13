<?php


namespace App\Services\OAuth\Storage;


use OAuth2\ScopeInterface;
use OAuth2\RequestInterface;
use App\Entity\LoginScope;
use App\Entity\LoginAppClient;
use App\Entity\LoginScopeAppClient;
use Doctrine\ORM\EntityManagerInterface;
/**
 * Class ScopeStorage
 * @package App\Services\OAuth\Storage
 */
final class ScopeStorage implements ScopeInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * ScopeStorage constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Check if the provided scope exists.
     *
     * @param $scope
     * A space-separated string of scopes.
     *
     * @return bool TRUE if it exists, FALSE otherwise.
     * TRUE if it exists, FALSE otherwise.
     */
    public function scopeExists($scope): bool
    {
        // TODO: Implement scopeExists() method.
        return false;
    }

    /**
     * Check if everything in required scope is contained in available scope.
     *
     * @param string $required_scope - A space-separated string of scopes.
     * @param string $available_scope - A space-separated string of scopes.
     * @return boolean                - TRUE if everything in required scope is contained in available scope and FALSE
     *                                  if it isn't.
     *
     * @see http://tools.ietf.org/html/rfc6749#section-7
     *
     * @ingroup oauth2_section_7
     */
    public function checkScope($required_scope, $available_scope): bool
    {
        // TODO: Implement checkScope() method.
        return false;
    }

    /**
     * The default scope to use in the event the client
     * does not request one. By returning "false", a
     * request_error is returned by the server to force a
     * scope request by the client. By returning "null",
     * opt out of requiring scopes
     *
     * @param null $client_id
     * An optional client id that can be used to return customized default scopes.
     *
     * @return string string representation of default scope, null if
     * string representation of default scope, null if
     * scopes are not defined, or false to force scope
     * request by the client
     *
     * ex:
     *     'default'
     * ex:
     *     null
     */
    public function getDefaultScope($client_id = null): string
    {
        // TODO: Implement getDefaultScope() method.
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ls.scope')
            ->from(LoginScope::class, 'ls')
            ->innerJoin(LoginScopeAppClient::class, 'lsa', 'WITH', 'lsa.scope = ls.id')
            ->innerJoin(LoginAppClient::class, 'lac', 'WITH', 'lsa.appClient = lac.id')
            ->where(
                $qb->expr()->eq('lac.clientIdentifier', ':client_id')
            )
            ->setParameter('client_id', $client_id);

        $q = $qb->getQuery();
        try {
            $scopes = $q->getResult();
        } catch (\Exception $exception) {
            unset($exception);
            $scopes = array();
        }

        $scopes = array_map(function ($scope) {
            return $scope['scope'];
        }, $scopes);

        return implode(' ', $scopes);
    }

    /**
     * Return scope info from request
     *
     * @param RequestInterface $request - Request object to check
     * @return string                   - representation of requested scope
     */
    public function getScopeFromRequest(RequestInterface $request): string
    {
        // TODO: Implement getScopeFromRequest() method.
        return '';
    }
}
