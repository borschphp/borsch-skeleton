<?php

namespace App\Handler;

use App\Repository\PeopleRepositoryInterface;
use Laminas\Diactoros\{Response, Response\JsonResponse};
use PDO;
use Psr\Http\{Message\ResponseInterface, Message\ServerRequestInterface, Server\RequestHandlerInterface};

/**
 * Class PeopleHandler
 * @package App\Handler
 */
class PeoplesHandler implements RequestHandlerInterface
{

    /**
     * @param PDO $pdo
     */
    public function __construct(
        protected PDO $pdo,
        protected PeopleRepositoryInterface $people_repository
    ) {}

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $body = $request->getParsedBody();

        return match ($request->getMethod()) {
            'GET' => $this->getPeoples($id),
            'POST' => $this->createPeoples($body),
            'PUT', 'PATCH' => $this->updatePeoples($id, $body),
            'DELETE' => $this->deletePeoples($id)
        };
    }

    /**
     * Fetches a list of people, or a unique one if an ID is provided.
     *
     * @param int|null $id
     * @return ResponseInterface
     */
    protected function getPeoples(?int $id = null): ResponseInterface
    {
        $peoples = $id ?
            $this->people_repository->getById($id) :
            $this->people_repository->getAll();

        if (!$peoples) {
            return new Response(status: 404);
        }

        return new JsonResponse($peoples);
    }

    /**
     * Creates a new people and returns it.
     *
     * @param array $body
     * @return ResponseInterface
     */
    protected function createPeoples(array $body): ResponseInterface
    {
        $new_people = $this->people_repository->create($body);

        return new JsonResponse($new_people, 201);
    }

    /**
     * Updates an existing people and returns it.
     *
     * @param int $id
     * @param array $body
     * @return ResponseInterface
     */
    protected function updatePeoples(int $id, array $body): ResponseInterface
    {
        $updated_people = $this->people_repository->update($id, $body);

        return new JsonResponse($updated_people);
    }

    /**
     * Delete an existing people by ID.
     *
     * @param int $id
     * @return ResponseInterface
     */
    protected function deletePeoples(int $id): ResponseInterface
    {
        $this->people_repository->delete($id);

        return new Response(status: 204);
    }
}
