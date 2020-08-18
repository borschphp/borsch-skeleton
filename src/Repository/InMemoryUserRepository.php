<?php

namespace App\Repository;

use App\Model\User;
use Borsch\Router\RouterInterface;
use InvalidArgumentException;

class InMemoryUserRepository implements UserRepositoryInterface
{

    /** @var RouterInterface */
    private $router;

    /** @var User[] */
    private $users = [];

    /**
     * InMemoryUserRepository constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;

        $this->users = [
            new User(1, 'Luke', 'Skywalker', 'Tatooine'),
            new User(2, 'Darth ', 'Vador', 'Tatooine'),
            new User(3, 'Leia', 'Organa', 'Alderaan'),
            new User(4, 'Obi-Wan', 'Kenobi', 'Stewjon'),
            new User(5, 'Han', 'Solo', 'Corellia')
        ];
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return array_values(array_map(function(User $user) {
            $user->setLink(sprintf(
                '%s%s',
                rtrim(env('APP_URL'), '/ '),
                $this->getUserUriById($user->getId())
            ));

            return $user;
        }, $this->users));
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() == $id) {
                return $user;
            }
        }

        throw new InvalidArgumentException(
            sprintf('User with ID #%d is unknown...', $id),
            404
        );
    }

    /**
     * @param int $user_id
     * @return string
     */
    public function getUserUriById(int $user_id): string
    {
        return $this->router->generateUri('user', ['id' => $user_id]);
    }
}
