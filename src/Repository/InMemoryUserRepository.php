<?php

namespace App\Repository;

use App\Model\User;
use InvalidArgumentException;

class InMemoryUserRepository implements UserRepositoryInterface
{

    /**
     * @var User[]
     */
    private $users = [];

    /**
     * InMemoryUserRepository constructor.
     * @param User[]|null $users
     */
    public function __construct(?array $users = null)
    {
        $this->users = $users ?: [
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
        return array_values($this->users);
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

        throw new InvalidArgumentException(sprintf(
            'User with ID #%d is unknown...',
            $id
        ));
    }
}
