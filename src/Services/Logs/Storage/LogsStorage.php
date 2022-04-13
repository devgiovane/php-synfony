<?php


namespace App\Services\Logs\Storage;


use App\Document\Logs;
use App\Factory\NotificationError;
use App\Services\User\Validation\UserExists;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\EntityManagerInterface;


class LogsStorage
{
    private UserExists $userExists;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userExists = new UserExists($entityManager);
    }

    public function insert(NotificationError $notificationError, DocumentManager $documentManager, string $token, string $message): bool
    {
        $userEntity = $this->userExists->findByToken($notificationError, $token);
        if ($userEntity) {
            try {
                $log = new Logs();
                $log->setName($userEntity->getName());
                $log->setDescription($message);
                $log->setCreatedAt(new \DateTime());

                $documentManager->persist($log);
                $documentManager->flush();
                return true;
            } catch (MongoDBException $exception) {
                unset($exception);
                return false;
            }
        }
        return false;
    }
}
