<?php


namespace App\Services\User\Validation;


use App\Entity\Login;
use App\Entity\LoginAccessToken;
use App\Entity\LoginUser;
use App\Entity\User;
use App\Factory\NotificationError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


final class UserExists
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(NotificationError $notificationError, int $id): ?User
    {
        /** @var User $userEntity */
        $userEntity = $this->entityManager->getRepository(User::class)->find($id);
        if ($userEntity) {
            return $userEntity;
        }
        $notificationError->setStatusCode(Response::HTTP_NOT_FOUND);
        return null;
    }

    public function findByEmail(NotificationError $notificationError, string $email): bool
    {
        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if($userEntity) {
            $notificationError->setError("conflict", [ "message" => "email_already_exists" ]);
            $notificationError->setStatusCode(Response::HTTP_CONFLICT);
            return true;
        }
        $notificationError->setStatusCode(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function findByCPF(NotificationError $notificationError, string $cpf): bool
    {
        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy(['cpf' => $cpf]);
        if($userEntity) {
            $notificationError->setError("not_found", [ "message" => "cpf_already_exists" ]);
            $notificationError->setStatusCode(Response::HTTP_CONFLICT);
            return true;
        }
        $notificationError->setStatusCode(Response::HTTP_NOT_FOUND);
        return false;
    }

    public function findByToken(NotificationError $notificationError, string $token): ?User
    {
        $loginUserEntity = null;
        /** @var LoginAccessToken $accessTokenEntity */
        $accessTokenEntity = $this->entityManager->getRepository(LoginAccessToken::class)->findOneBy(['token' => $token]);
        if($accessTokenEntity) {
            /** @var LoginUser $loginUserEntity */
            $loginUserEntity = $this->entityManager->getRepository(LoginUser::class)->findOneBy(['login' => $accessTokenEntity->getLogin()]);
        }
        if($loginUserEntity) {
            return $loginUserEntity->getUser();
        }
        return null;
    }

}
