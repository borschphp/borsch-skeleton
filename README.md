<p align="center"><img src="https://www.dropbox.com/s/bmgwjfhr6i5cah2/borschphp.color.png?raw=1" alt="Logo" height="120" /></p>
<h1 align="center">Borsch Skeleton</h1>
<p align="center">Keep it simple.</p>
<p align="center">
<a href="//travis-ci.com/github/borschphp/borsch-application" rel="nofollow"><img src="https://travis-ci.com/borschphp/borsch-application.svg?branch=master" alt="Travis" style="max-width:100%;"></a>
<a href="//packagist.org/packages/borschphp/borsch-skeleton" rel="nofollow"><img src="https://poser.pugx.org/borschphp/borsch-skeleton/v" alt="Version" style="max-width:100%;"></a>
<a href="//packagist.org/packages/borschphp/borsch-skeleton" rel="nofollow"><img src="https://poser.pugx.org/borschphp/borsch-skeleton/license" alt="License" style="max-width:100%;"></a>
</p>
<br/>
<p align="center">
<img src="https://www.dropbox.com/s/3t7hvu2qe9p4j3q/borschphp-carbon.png?raw=1" alt="Example" />
</p>
<br/>

## About Borsch Skeleton

Borsch is a simple and efficient [PSR-15](https://www.php-fig.org/psr/psr-15/) micro framework made to kick start your
app development by allowing you to develop using the tools you prefer, and provides minimal structure and facilities
to ease your development.

It natively features :

* [Dependency Injection Container](https://github.com/borschphp/borsch-container)
* [Router](https://github.com/borschphp/borsch-router)
* [Request Handlers and Middlewares](https://github.com/borschphp/borsch-requesthandler)
* [Environment Variables](https://github.com/vlucas/phpdotenv)
* [Error Handling](https://github.com/borschphp/borsch-skeleton/blob/master/src/Middleware/ErrorHandlerMiddleware.php)
* [Listeners](https://github.com/borschphp/borsch-skeleton/blob/master/src/Listener/MonologListener.php)

Can be enriched with :

* [ORM](https://github.com/borschphp/borsch-orm)
* [Templating](https://github.com/borschphp/borsch-smarty)
* Anything else you want 

The framework is built around the DI Container, therefore everything is made around interfaces.  
If something is not at your taste, you can implement your own logic without having to modify everything.

## Get started

Via [composer](https://getcomposer.org/) :  
`composer create-project borschphp/borsch-skeleton [your-app-name]`

After installation, you can run the application in development with the command :  
`php -S 0.0.0.0:8080 -t ./public/ ./public/index.php`  
You can then visit http://0.0.0.0:8080 .

## Configuration

#### Public directory

After installing Borsch Skeleton, configure your web server's root to be the `public` directory.  
The `index.php` in the entry point for all HTTP requests of your application.

#### Environment file

When installed via Composer `create-project` command line, a `.env` file is automatically generated in the root folder
of your application.

If you did not use `create-project` command line then copy the `.env.example` file and rename it as `.env`.  
Typically: `cp .env.example .env`.

Review the default environment variable, update them, **add a proper `APP_KEY` if not done yet during the `create-project`
command**, add your own.

#### Permissions

Make sure directories within `storage` directory are writable by your web server or it will not run.

## Container

In the file `./config/container.php` you can find the application dependencies.  
Usually you will need to add your own dependencies in the Pipeline and Handlers section, but you can or update anything else.

## Pipeline

The pipeline is the heart of your application and consist of a sequence of Middlewares..  
Every request goes through the pipeline until a [PSR-7](https://www.php-fig.org/psr/psr-7/)
Response is return.

The default pipeline should be good as is and should not require much changes to comply to your
need.  
To ease your development, all Pipelines middlewares are located in your `./src/Middleware` folder so feel free
to change everything as you need.

The different steps that occurs in the pipeline :

1. Error handler middleware
2. Trailing slash middleware
3. Segregated path middleware
4. Routing middleware
5. Implicit HEAD middleware
6. Implicit OPTIONS middleware
7. Method Not Allowed middleware
8. Dispatched middleware
9. Not Found Handler middleware

The `./config/pipeline.php` file is well documented, have a look at it if you need more information.

## Routing

You can define your application's route in the file `./config/routes.php`.  
Note that this skeleton uses a [nikic/FastRoute](https://github.com/nikic/FastRoute) implementation router, so you can 
use the FastRoute pattern to define your app routes.

By default, in a production environment, a cache file is generated in `./storage/smarty/routes.cache.php`.  
You can modify this in the `./config/container.php` file, in the `RouterInterface::class` definition.

## Note

Borsch framework is heavily inspired by [Mezzio](https://docs.mezzio.dev/mezzio/) and [Laravel](https://laravel.com/), 
only it is a much simpler and lightweight implementation.

Do not hesitate to check [Mezzio](https://docs.mezzio.dev/mezzio/) and [Laravel](https://laravel.com/) out :wink: .

## License

The package is licensed under the MIT license. See [License File](https://github.com/borschphp/borsch-skeleton/blob/master/LICENSE.md) for more information.