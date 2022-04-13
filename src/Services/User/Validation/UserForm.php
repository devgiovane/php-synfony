<?php


namespace App\Services\User\Validation;


use App\Dto\UserDto;
use App\Factory\NotificationError;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Response;
use Respect\Validation\Exceptions\NestedValidationException;


abstract class UserForm
{
    public static function validationCreate(NotificationError $notificationError, UserDto $userDto): bool
    {
        $validation = v::create()
            ->key('name', v::stringType()->notEmpty()->setName('name'))
            ->key('email', v::email()->notEmpty()->setName('email'))
            ->key('cpf', v::cpf()->notEmpty()->setName('cpf'))
            ->key('password', v::stringType()->notEmpty()->setName('password'));

        try {
            $validation->assert($userDto->jsonSerialize());
            return true;
        } catch (NestedValidationException $exception) {
            $notificationError->reset();
            $notificationError->setStatusCode(Response::HTTP_BAD_REQUEST);
            $notificationError->setError("validation", $exception->getMessages());
            return false;
        }
    }

    public static function validationUpdate(NotificationError $notificationError, UserDto $userDto): bool
    {
        $validation = v::create()
            ->key('name', v::stringType()->notEmpty()->setName('name'))
            ->key('email', v::email()->notEmpty()->setName('email'))
            ->key('cpf', v::cpf()->notEmpty()->setName('cpf'));

        try {
            $validation->assert($userDto->jsonSerialize());
            return true;
        } catch (NestedValidationException $exception) {
            $notificationError->reset();
            $notificationError->setStatusCode(Response::HTTP_BAD_REQUEST);
            $notificationError->setError("validation", $exception->getMessages());
            return false;
        }
    }
}
