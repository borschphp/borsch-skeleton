<?php

namespace App\Repository;

use App\Model\People;

interface PeopleRepositoryInterface
{

    /**
     * @return People[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return People
     */
    public function getById(int $id): ?People;

    /**
     * @param array{'name': string, 'height': string, 'birth_year': int, 'gender': string} $body
     * @return People
     */
    public function create(array $body): People;

    /**
     * @param int $id
     * @param array{'name': string, 'height': string, 'birth_year': int, 'gender': string} $body
     * @return People
     */
    public function update(int $id, array $body): People;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
