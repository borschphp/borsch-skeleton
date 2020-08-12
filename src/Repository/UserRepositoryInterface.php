<?php

namespace App\Repository;

use App\Model\User;

interface UserRepositoryInterface
{

    /**
     * @return User[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User;
}