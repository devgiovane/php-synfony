<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class LoginScopeAppClient
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="login_scope_appclient",
 *      indexes={
 *          @ORM\Index(name="scope_idx", columns={"scope_id"}),
 *          @ORM\Index(name="appclient_idx", columns={"appclient_id"})
 *      }
 * )
 */
class LoginScopeAppClient
{
    /**
     * @var LoginScope
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="LoginScope")
     * @ORM\JoinColumn(name="scope_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private LoginScope $scope;

    /**
     * @var LoginAppClient
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="LoginAppClient")
     * @ORM\JoinColumn(name="appclient_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private LoginAppClient $appClient;

    /**
     * @return LoginScope
     */
    public function getScope(): LoginScope
    {
        return $this->scope;
    }

    /**
     * @param LoginScope $scope
     * @return LoginScopeAppClient
     */
    public function setScope(LoginScope $scope): LoginScopeAppClient
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return LoginAppClient
     */
    public function getAppClient(): LoginAppClient
    {
        return $this->appClient;
    }

    /**
     * @param LoginAppClient $appClient
     * @return LoginScopeAppClient
     */
    public function setAppClient(LoginAppClient $appClient): LoginScopeAppClient
    {
        $this->appClient = $appClient;
        return $this;
    }

}
