<?php


namespace App\Services\Logs;


use App\Factory\NotificationError;
use App\Services\Logs\Storage\LogsStorage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;


class LogsService
{
    private LogsStorage $logsStorage;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->logsStorage = new LogsStorage($entityManager);
    }

    public function insert(NotificationError $notificationError, DocumentManager $documentManager, string $token, string $description): bool
    {
        return $this->logsStorage->insert($notificationError, $documentManager, $token, $description);
    }
}
