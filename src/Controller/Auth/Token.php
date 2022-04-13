<?php


namespace App\Controller\Auth;


use OAuth2\ResponseInterface;
use App\Services\OAuth\ServerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * Class Token
 * @package App\Controller\Auth
 *
 * @Route("/auth/v1")
 */
final class Token extends AbstractController
{
    /**
     * @Route("/{_type}", name="auth_token", requirements={"_type"="token|refresh"}, methods={"POST", "OPTIONS"})
     *
     * @param Request $request
     * @param ServerManager $manager
     * @return ResponseInterface
     */
    public function token(Request $request, ServerManager $manager): ResponseInterface
    {
        $server = $manager->getServer();
        $oauthResponse = $manager->getResponse();
        $oauthRequest = $manager->getRequest($request);
        return $server->handleTokenRequest($oauthRequest, $oauthResponse);
    }
}
