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
        $isCreated = $isEmailRegistered = $isCpfRegistered = false;
        $isValidUser = UserForm::validationCreate($notificationError, $userDto);
        if($isValidUser) {
            $isEmailRegistered = $this->userExists->findByEmail($notificationError, $userDto->getEmail());
        }
        if($isValidUser && !$isEmailRegistered) {
            $isCpfRegistered = $this->userExists->findByCPF($notificationError, $userDto->getCpf());
        }
        if($isValidUser && !$isEmailRegistered && !$isCpfRegistered) {
            $isCreated = $this->userStorage->create($notificationError, $userDto);
        }
        if($isCreated) {
            return true;
        }
        return false;
    }

    public function read($notificationError): ?array
    {
       return $this->userStorage->read($notificationError);
    }

    public function update(NotificationError $notificationError, User $userEntity, UserDto $userDto): bool
    {
        $isUpdated = false;
        $isValidUser = UserForm::validationUpdate($notificationError, $userDto);
        if($isValidUser) {
            $isUpdated = $this->userStorage->update($notificationError, $userEntity, $userDto);
        }
        if($isUpdated) {
            return true;
        }
        return false;
    }

    public function delete(NotificationError $notificationError, User $userEntity): bool
    {
        return $this->userStorage->delete($notificationError, $userEntity);
    }
}
