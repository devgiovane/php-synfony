<?php


namespace App\Services\User\Storage;


use App\Dto\UserDto;
use App\Entity\Login;
use App\Entity\User;
use App\Entity\LoginUser;
use App\Factory\PasswordCrypto;
use App\Factory\NotificationError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


final class UserStorage
{
    private const CLASS_MAPPING = 900;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(NotificationError $notificationError, UserDto $userDto): bool
    {
        $userEntity = new User();
        $loginEntity = new Login();
        $loginUserEntity = new LoginUser();

        $userEntity
            ->setName($userDto->getName())
            ->setEmail($userDto->getEmail())
            ->setCpf($userDto->getCpf());

        try {
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_create_user",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }

        $loginEntity
            ->setEmail($userDto->getEmail())
            ->setUsername(PasswordCrypto::createAlias($userDto->getName(), $userEntity->getId()))
            ->setPassword(PasswordCrypto::encrypt($userDto->getPassword()));

        try {
            $this->entityManager->persist($loginEntity);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_create_login",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }

        $loginUserEntity
            ->setUser($userEntity)
            ->setLogin($loginEntity);

        try {
            $this->entityManager->persist($loginUserEntity);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_link_user_or_login",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }

    public function read(NotificationError $notificationError): ?array
    {
        try {
            $userEntities = $this->entityManager->getRepository(User::class)->findAll();
            if ($userEntities) {
                return $userEntities;
            }
            $notificationError->setStatusCode(Response::HTTP_NOT_FOUND);
            return null;
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_list_user",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return null;
        }
    }

    public function update(NotificationError $notificationError, User $userEntity, UserDto $userDto): bool
    {
        $userEntity
            ->setName($userDto->getName())
            ->setEmail($userDto->getEmail())
            ->setCpf($userDto->getCpf());

        try {
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            return true;
        } catch (UniqueConstraintViolationException $exception) {
            unset($exception);
            $notificationError->setError("conflict", [
                "message" => "email_or_password_already_exists",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_CONFLICT);
            return false;
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_update_user",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }

    public function delete(NotificationError  $notificationError, User $userEntity): bool
    {
        try {
            $loginUserEntity = $this->entityManager->getRepository(LoginUser::class)->findOneBy(["user" => $userEntity]);
            $this->entityManager->remove($loginUserEntity->getLogin());
            $this->entityManager->remove($loginUserEntity->getUser());
            $this->entityManager->flush();
            return true;
        } catch (\Exception $exception) {
            unset($exception);
            $notificationError->setError("internal", [
                "message" => "error_in_delete_user",
                "code" => self::CLASS_MAPPING
            ]);
            $notificationError->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
    }
}
