# Borsch Skeleton

Borsch is a micro/medium framework made to kick start your apps development.

This package provides a simple and efficient [PSR-15](https://www.php-fig.org/psr/psr-15/)
framework with DI container, routing, database connection, environment variables and error handling.  

## Installation

Via [composer](https://getcomposer.org/) :
`composer create-project borschphp/borsch-skeleton [your-app-name]`

After installation, you can run the application in development with the command :
`php -S 0.0.0.0:8080 -t ./public/ ./public/index.php`
You can then visit the two demo links :
* http://0.0.0.0:8080
* http://0.0.0.0:8080/api

## Usage

The skeleton uses :
* [Borsch Container](https://github.com/borschphp/borsch-container)
* [Borsch Router](https://github.com/borschphp/borsch-router)
* [Borsch Request Handler](https://github.com/borschphp/borsch-requesthandler)
* [Laminas Dicatoros](https://docs.laminas.dev/laminas-diactoros)
* [Laminas Db](https://docs.laminas.dev/laminas-db).

#### Configuration
##### Pipeline (config/pipeline.php)

The pipeline is the heart of your application.  
Every request goes through the pipeline until a [PSR-7](https://www.php-fig.org/psr/psr-7/)
Response is return.

The default pipeline should be good as is and should not require much changes to comply to your
need.  
To ease your development, all Pipelines middlewares are located in your _src_ folder so feel free
to change everything as you need.

##### Pipeline (config/routes.php)

You can define your route there.  
Note that this skeleton uses a [nikic/FastRoute](https://github.com/nikic/FastRoute) implementation router so you can use the fastroute pattern to
define your app routes. 

##### Container (config/container.php)

The container creation is done here with its definitions.  
You can add your own definitions when needed.

##### Environment variable (config/env.ini)

You know what to do with that file ;) .

##### App code

Most of the code is documented, have a look around to learn more about Borsch Framework.  
You'll see, the recipe is easy and tasty !

## License

The package is licensed under the MIT license. See [License File](https://github.com/borschphp/borsch-skeleton/blob/master/LICENSE.md) for more information.