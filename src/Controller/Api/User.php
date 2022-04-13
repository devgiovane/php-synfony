<?php


namespace App\Controller\Api;


use App\Dto\UserDto;
use App\Factory\NotificationError;
use App\Services\Logs\LogsService;
use App\Services\OAuth\OAuthService;
use App\Services\User\UserService;
use App\Services\User\Validation\UserExists;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * Class User
 * @package App\Controller\Api
 *
 * @Route("/api/v1/user")
 */
final class User extends AbstractController
{
    protected const SCOPE_MANAGER_USER = 1;
    /**
     * @Route("", methods={"POST", "OPTIONS"})
     *
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function create(Request $request, UserService $userService): Response
    {
        $userDto = new UserDto();
        $notificationError = new NotificationError();
        $response = new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);

        $userDto
            ->setName($request->get('name'))
            ->setEmail($request->get('email'))
            ->setCpf($request->get('cpf', ''))
            ->setPassword($request->get('password'));

        $isCreated = $userService->create($notificationError, $userDto);
        if($isCreated) {
            $response =  new Response('', Response::HTTP_CREATED);
        }
        if(!$isCreated && $notificationError->hasErrors()) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return $response;
    }

    /**
     * @Route("", methods={"GET", "OPTIONS"})
     *
     * @param Request $request
     * @param UserService $userService
     * @param OAuthService $authService
     * @return Response
     */
    public function read(Request $request, UserService $userService, OAuthService $authService): Response
    {
        $users = null;
        $notificationError = new NotificationError();
        $response = new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);

        $isValidScope = $authService->validateScope($request, self::SCOPE_MANAGER_USER);
        if (!$isValidScope) {
            $response = $authService->getResponse();
        }
        if ($isValidScope) {
            $users = $userService->read($notificationError);
        }
        if ($isValidScope && $users) {
            $response = new JsonResponse($users, Response::HTTP_OK);
        }
        if ($isValidScope && !$users && $notificationError->hasErrors()) {
            $response =new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return $response;
    }

    /**
     * @Route("/{idUser}", methods={"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @param UserExists $userExists
     * @param UserService $userService
     * @param OAuthService $authService
     * @param int $idUser
     * @return Response
     */
    public function update(Request $request, UserExists $userExists, UserService $userService, OAuthService $authService, int $idUser): Response
    {
        $isUpdated = false;
        $userEntity = null;
        $notificationError = new NotificationError();
        $response = new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);

        $isValidScope = $authService->validateScope($request, self::SCOPE_MANAGER_USER);
        if (!$isValidScope) {
            $response = $authService->getResponse();
        }
        if ($isValidScope) {
            $userEntity = $userExists->findById($notificationError, $idUser);
        }
        if ($isValidScope && $userEntity) {
            $userDto = new UserDto();
            $userDto
                ->setName($request->get('name'))
                ->setEmail($request->get('email'))
                ->setCpf($request->get('cpf'))
                ->setPassword(null);

            $isUpdated = $userService->update($notificationError, $userEntity, $userDto);
        }
        if($isValidScope && $isUpdated) {
           $response = new Response('', Response::HTTP_NO_CONTENT);
        }
        if ($isValidScope && !$isUpdated && $notificationError->hasErrors()) {
            $response = new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return $response;
    }

    /**
     * @Route("/{idUser}", methods={"DELETE", "OPTIONS"})
     *
     * @param Request $request
     * @param DocumentManager $documentManager
     * @param UserService $userService
     * @param UserExists $userExists
     * @param OAuthService $authService
     * @param int $idUser
     * @return Response
     */
    public function delete(Request $request, DocumentManager $documentManager, LogsService $logsService, UserService $userService, UserExists $userExists, OAuthService $authService, int $idUser): Response
    {
        $isDeleted = false;
        $token = $userEntity = null;
        $notificationError = new NotificationError();
        $response = new Response('', Response::HTTP_UNPROCESSABLE_ENTITY);

        $isValidScope = $authService->validateScope($request, self::SCOPE_MANAGER_USER);
        if (!$isValidScope) {
            $response = $authService->getResponse();
        }
        if ($isValidScope) {
            $token = $authService->getData($request)['token'];
            $userEntity = $userExists->findById($notificationError, $idUser);
        }
        if ($isValidScope && $userEntity) {
            $isDeleted = $userService->delete($notificationError, $userEntity);
        }
        if ($isValidScope && $isDeleted) {
            $logsService->insert($notificationError, $documentManager, $token, "Deleted user with name {$userEntity->getName()}");
            $response = new Response('', Response::HTTP_NO_CONTENT);
        }
        if ($isValidScope && !$isDeleted && $notificationError->hasErrors()) {
            $response = new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return $response;
    }
}
