<?php

namespace App\CachePool;

use Borsch\Cache\CacheItem;
use Borsch\Cache\Trait\HasKeyValidation;
use Exception;
use PDO;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use function unserialize;

/**
 * PSR-6 CachePool implementation to work with the embedded SQLite database.
 * Its main purpose is to be used in the PSR-16 Simple Cache implementation shipped with this skeleton.
 * This class is configured and set in the container.
 *
 * In case you deleted the cache table, here is the schema:
 *
 * create table cache (
 *     key  TEXT not null constraint cache_pk primary key,
 *     item TEXT not null
 * );
 *
 * @see config/container.php
 * @see vendor/borschphp/cache/src/Cache/Cache.php
 */
class SQLiteCacheItemPool implements CacheItemPoolInterface
{

    use HasKeyValidation;

    protected array $items = [];

    public function __construct(
        protected PDO $pdo
    ) {}

    public function __destruct()
    {
        $this->commit();
    }

    public function getItem(string $key): CacheItemInterface
    {
        $this->validateKey($key);

        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        $statement = $this->pdo->prepare('SELECT `item` FROM `cache` WHERE `key` = ? LIMIT 1');
        $statement->execute([$key]);

        $item = unserialize($statement->fetchColumn());
        if ($item instanceof CacheItem) {
            $this->items[$key] = $item;

            return $item;
        }

        return new CacheItem($key);
    }

    public function getItems(array $keys = []): iterable
    {
        $items = [];

        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    public function hasItem(string $key): bool
    {
        $this->validateKey($key);

        if (isset($this->items[$key])) {
            return true;
        }

        $statement = $this->pdo->prepare('SELECT COUNT(`key`) FROM `cache` WHERE `key` = ? LIMIT 1');
        $statement->execute([$key]);

        $count = (int)$statement->fetchColumn();

        return $count > 0;
    }

    public function clear(): bool
    {
        $this->items = [];
        $statement = $this->pdo->prepare('DELETE FROM `cache` WHERE 1');

        return $statement->execute();
    }

    public function deleteItem(string $key): bool
    {
        $this->validateKey($key);

        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }

        $statement = $this->pdo->prepare('DELETE FROM `cache` WHERE `key` = ?');
        $statement->execute([$key]);

        return true;
    }

    public function deleteItems(array $keys): bool
    {
        $success = true;
        foreach ($keys as $key) {
            $success &= $this->deleteItem($key);
        }

        return $success;
    }

    public function save(CacheItemInterface $item): bool
    {
        $statement = $this->pdo->prepare('INSERT OR REPLACE INTO `cache` (`key`, `item`) VALUES (?, ?)');

        return $statement->execute([$item->getKey(), serialize($item)]);
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->items[$item->getKey()] = $item;

        return true;
    }

    public function commit(): bool
    {
        try {
            $this->pdo->beginTransaction();
            foreach ($this->items as $item) {
                $this->save($item);
            }
            return $this->pdo->commit();
        } catch (Exception) {
            $this->pdo->rollBack();
        }

        return false;
    }
}