<?php


namespace App\Services\OAuth\Bridge;


use OAuth2\RequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\HeaderBag;
/**
 * Class RequestBridge
 * @package App\Services\OAuth\Adapter
 */
class RequestBridge extends Request implements RequestInterface
{
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function query($name, $default = null): mixed
    {
        return $this->query->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function request($name, $default = null): mixed
    {
        return $this->request->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function server($name, $default = null): mixed
    {
        return $this->server->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string|null
     */
    public function headers($name, $default = null): ?string
    {
        return $this->headers->get($name, $default);
    }

    /**
     * @return array
     */
    public function getAllQueryParameters(): array
    {
        return $this->query->all();
    }

    /**
     * @param Request $request
     * @return static
     */
    public static function createFromRequest(Request $request): static
    {
        return new static(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent()
        );
    }

    /**
     * @return static
     */
    public static function createFromGlobals(): static
    {
        $request = parent::createFromGlobals();
        self::fixAuthHeader($request->headers);
        return $request;
    }

    /**
     * @param HeaderBag $headers
     */
    protected static function fixAuthHeader(HeaderBag $headers): void
    {
        if (!$headers->has('Authorization') && function_exists('apache_request_headers')) {
            $all = apache_request_headers();
            if (isset($all['Authorization'])) {
                $headers->set('Authorization', $all['Authorization']);
            }
        }
    }
}
