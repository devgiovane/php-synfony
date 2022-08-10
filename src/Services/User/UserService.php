<?php


namespace App\Services\User;


use App\Dto\UserDto;
use App\Entity\User;
use App\Factory\NotificationError;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\User\Storage\UserStorage;
use App\Services\User\Validation\UserForm;
use App\Services\User\Validation\UserExists;


final class UserService
{
    private UserExists $userExists;
    private UserStorage $userStorage;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userExists = new UserExists($entityManager);
        $this->userStorage = new UserStorage($entityManager);
    }

    public function create(NotificationError $notificationError, UserDto $userDto): bool
    {
        $isValidUser = UserForm::validationCreate($notificationError, $userDto);
        if (!$isValidUser) {
            return false;
        }
        $isEmailRegistered = $this->userExists->findByEmail($notificationError, $userDto->getEmail());
        if ($isEmailRegistered) {
            return false;
        }
        $isCpfRegistered = $this->userExists->findByCPF($notificationError, $userDto->getCpf());
        if($isCpfRegistered) {
            return false;
        }
        $isCreated = $this->userStorage->create($notificationError, $userDto);
        if(!$isCreated) {
            return false;
        }
        return true;
    }

    public function read($notificationError): ?array
    {
       return $this->userStorage->read($notificationError);
    }

    public function update(NotificationError $notificationError, User $userEntity, UserDto $userDto): bool
    {
        $isValidUser = UserForm::validationUpdate($notificationError, $userDto);
        if(!$isValidUser) {
            return false;
        }
        $isUpdated = $this->userStorage->update($notificationError, $userEntity, $userDto);
        if(!$isUpdated) {
            return false;
        }
        return true;
    }

    public function delete(NotificationError $notificationError, User $userEntity): bool
    {
        return $this->userStorage->delete($notificationError, $userEntity);
    }
}
