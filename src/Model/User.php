<?php

namespace App\Model;

use JsonSerializable;

class User implements JsonSerializable
{

    /** @var int */
    protected $id;

    /** @var string */
    protected $firstname;

    /** @var string */
    protected $lastname;

    /** @var string */
    protected $homeworld;

    /** @var string */
    protected $link;

    /**
     * User constructor.
     *
     * @param int $id
     * @param string $firstname
     * @param string $lastname
     * @param string $homeworld
     */
    public function __construct(int $id, string $firstname, string $lastname, string $homeworld)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->homeworld = $homeworld;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getHomeworld(): string
    {
        return $this->homeworld;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'homeworld' => $this->homeworld,
            'link' => $this->link
        ];
    }
}
