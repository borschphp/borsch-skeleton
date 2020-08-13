<?php

namespace App\Handler;

use App\Repository\UserRepositoryInterface;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class UserHandler
 * @package App\Handler
 */
class UserHandler implements RequestHandlerInterface
{

    /** @var UserRepositoryInterface */
    protected $user_repository;

    /**
     * UserHandler constructor.
     *
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(UserRepositoryInterface $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user_id = $request->getAttribute('id');
        if ($user_id) {
            try {
                $user = $this->user_repository->getById($user_id);
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse(
                    [
                        'error' => $exception->getMessage()
                    ],
                    $exception->getCode()
                );
            }

            return new JsonResponse($user);
        }

        return new JsonResponse($this->user_repository->getAll());
    }
}
