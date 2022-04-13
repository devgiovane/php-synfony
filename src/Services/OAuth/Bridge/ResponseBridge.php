<?php


namespace App\Services\OAuth\Bridge;


use OAuth2\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Class ResponseBridge
 * @package App\Services\OAuth\Adapter
 */
class ResponseBridge extends JsonResponse implements ResponseInterface
{
    /**
     * @param array $parameters
     */
    public function addParameters(array $parameters): void
    {
        if ($this->content && $data = json_decode($this->content, true)) {
            $parameters = array_merge($data, $parameters);
        }
        $this->setData($parameters);
    }

    /**
     * @param array $httpHeaders
     */
    public function addHttpHeaders(array $httpHeaders): void
    {
        foreach ($httpHeaders as $key => $value) {
            $this->headers->set($key, $value);
        }
    }

    /**
     * @param $statusCode
     * @param $name
     * @param null $description
     * @param null $uri
     */
    public function setError($statusCode, $name, $description = null, $uri = null): void
    {
        $this->setStatusCode($statusCode);
        $this->addParameters(array_filter(array(
            'error'             => $name,
            'error_description' => $description,
            'error_uri'         => $uri,
        )));
    }

    /**
     * @param int $statusCode
     * @param string $url
     * @param null $state
     * @param null $error
     * @param null $errorDescription
     * @param null $errorUri
     * @return void
     */
    public function setRedirect($statusCode, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null): void
    {
        $this->setStatusCode($statusCode);

        $params = array_filter(array(
            'state'             => $state,
            'error'             => $error,
            'error_description' => $errorDescription,
            'error_uri'         => $errorUri,
        ));

        if ($params) {
            $parts = parse_url($url);
            $sep = isset($parts['query']) && !empty($parts['query']) ? '&' : '?';
            $url .= $sep . http_build_query($params);
        }

        $this->headers->set('Location', $url);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getParameter($name): ?string
    {
        if ($this->content && $data = json_decode($this->content, true)) {
            return $data[$name] ?? null;
        }
        return null;
    }

    /**
     * @param int $code
     * @param null $text
     */
    public function setStatusCode($code, $text = null): static
    {
        return parent::setStatusCode($code);
    }
}
