<p align="center"><img src="https://www.dropbox.com/s/981geehhoahpv2u/logo.png?raw=1" alt="Logo" height="120" /></p>
<h1 align="center">Borsch Skeleton</h1>
<p align="center">Keep it simple.</p>
<p align="center">
<a href="https://github.com/borschphp/borsch-skeleton/actions/workflows/php.yml" rel="nofollow"><img src="https://github.com/borschphp/borsch-skeleton/actions/workflows/php.yml/badge.svg" alt="Version" style="max-width:100%;"></a>
<a href="//packagist.org/packages/borschphp/borsch-skeleton" rel="nofollow"><img src="https://poser.pugx.org/borschphp/borsch-skeleton/v" alt="Version" style="max-width:100%;"></a>
<a href="//packagist.org/packages/borschphp/borsch-skeleton" rel="nofollow"><img src="https://poser.pugx.org/borschphp/borsch-skeleton/license" alt="License" style="max-width:100%;"></a>
</p>
<br/>

## Note

This fork is made to be deployed to [railway.app](https://railway.app).  
It adds a Dockerfile and some Apache configuration files.

## About Borsch Skeleton

Sometimes, you don't need an overkill solution like [Laravel](https://laravel.com/) or [Symfony](https://symfony.com/).

Borsch is a simple and efficient [PSR-15](https://www.php-fig.org/psr/psr-15/) micro framework made to kick start your
web app or API development by using the tools you prefer, and provides minimal structure and facilities to ease your
development.

It natively features :

* [Dependency Injection Container](https://github.com/borschphp/borsch-container)
* [Router](https://github.com/borschphp/borsch-router)
* [Request Handlers and Middlewares](https://github.com/borschphp/borsch-requesthandler)
* [Environment Variables](https://github.com/vlucas/phpdotenv)
* [Error Handling](https://github.com/borschphp/borsch-skeleton/blob/master/src/Middleware/ErrorHandlerMiddleware.php)
* [Listeners](https://github.com/borschphp/borsch-skeleton/blob/master/src/Listener/MonologListener.php)

Can be enriched with :

* ORM
* Templating
* Anything else you want 

The framework is built around a [PSR-11](https://www.php-fig.org/psr/psr-11/) Container, therefore everything is made around interfaces.  
If something is not at your taste, you can implement your own logic without having to modify everything.

## Get started

Via [composer](https://getcomposer.org/) :  
`composer create-project borschphp/borsch-skeleton [your-app-name]`

After installation, you can run the application in development with the command :  
`php -S 0.0.0.0:8080 -t ./public/ ./public/index.php`  

Or you can use `docker-compose` to run the app with docker :  
`docker-compose up -d`

Or you can also use `lando` to run the app with docker :  
`lando start`

You can then visit http://0.0.0.0:8080 .

## Documentation

An extended documentation is [available here](https://github.com/borschphp/borsch-skeleton/wiki).

## Note

Borsch Framework is heavily inspired by [Mezzio](https://docs.mezzio.dev/mezzio/) and [Laravel](https://laravel.com/), 
only it is a much simpler and lightweight implementation.

Do not hesitate to check [Mezzio](https://docs.mezzio.dev/mezzio/) and [Laravel](https://laravel.com/) out :wink: .

## License

The package is licensed under the MIT license. See [License File](https://github.com/borschphp/borsch-skeleton/blob/master/LICENSE.md) for more information.