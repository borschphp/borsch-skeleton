<?php

namespace App\Handler;


use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ApiHandler
 * @package App\Handler
 */
class ApiHandler implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Let's imagine we are the SWAPI Api
        // https://swapi.dev/
        return new JsonResponse([
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male',
            'homeworld' => 'https://swapi.dev/api/planets/1/',
            'films' =>
                [
                    0 => 'https://swapi.dev/api/films/2/',
                    1 => 'https://swapi.dev/api/films/6/',
                    2 => 'https://swapi.dev/api/films/3/',
                    3 => 'https://swapi.dev/api/films/1/',
                    4 => 'https://swapi.dev/api/films/7/',
                ],
            'species' =>
                [
                    0 => 'https://swapi.dev/api/species/1/',
                ],
            'vehicles' =>
                [
                    0 => 'https://swapi.dev/api/vehicles/14/',
                    1 => 'https://swapi.dev/api/vehicles/30/',
                ],
            'starships' =>
                [
                    0 => 'https://swapi.dev/api/starships/12/',
                    1 => 'https://swapi.dev/api/starships/22/',
                ],
            'created' => '2014-12-09T13:50:51.644000Z',
            'edited' => '2014-12-20T21:17:56.891000Z',
            'url' => 'https://swapi.dev/api/people/1/',
        ]);
    }
}
