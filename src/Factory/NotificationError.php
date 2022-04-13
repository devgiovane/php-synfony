<?php


namespace App\Factory;


final class NotificationError
{
    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var array
     */
    protected array $errors;

    /**
     * @var bool
     */
    protected bool $hasErrors;

    /**
     * NotificationError constructor.
     */
    public function __construct()
    {
        $this->statusCode = 400;
        $this->errors = array();
        $this->hasErrors = false;
    }

    public function reset(): NotificationError
    {
        $this->statusCode = 400;
        $this->errors = array();
        $this->hasErrors = false;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $code): NotificationError
    {
        $this->statusCode = $code;
        $this->hasErrors = true;
        return $this;
    }

    public function setError($key, $params = array()): NotificationError
    {
        if($key) {
            $this->errors[$key] = $params;
        } else {
            $this->errors = $params;
        }
        $this->hasErrors = true;
        return $this;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
