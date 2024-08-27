[< Go Back](../README.md)

# Basic setup of Slim microframework

### 1. Install dependencies
- Install `slim` via composer `composer require slim/slim:"4.*"`
- Install PSR-7 implementation `composer require slim/psr7`

### 2. Setup .htaccess files
- In root of your project create following `.htaccess` file

```apacheconf
RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]

RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

> Note: This .htaccess is responsible for redirecting all requests to public/index.php

> Also, `.* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]` line is optional, it depends on weather you need or don't tokens in your request

- Now, inside `public/` create `index.php` and `.htaccess`
- `.htaccess` should look like this

```apacheconf
# Redirect to front controller
RewriteEngine On
# RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]

RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

- That's it, you are all set to write Slim app

### 3. Writing first lines of Slim 
- In your new `index.php` first include autoloader and all deps

```php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;

require __DIR__ . '/../vendor/autoload.php';
```

- Next up, create instance of an app

```php
$app = AppFactory::create();
```

- After instance of an app is create, register all middlewares

```php
	$app->addBodyParsingMiddleware();
	
	$app->add(new BasePathMiddleware($app));
	
	$app->addRoutingMiddleware();

    $app->addErrorMiddleware(true, false, false);
```

- Now all that's left is to register our first route and run app

```php
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello World');
    return $response;
});

$app->run();
```

- That's it, you are all set, you are running slim app

### 4. Overview

```php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
	
$app->add(new BasePathMiddleware($app));
	
$app->addRoutingMiddleware();

$app->addErrorMiddleware(true, false, false);

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello World');
    return $response;
});

$app->run();
```

- As we can see, this could work, we can read code and everything seems fine
- But, first thing, we could easily have much more middlewares that are even custom ones
- Not to mention routes, what if we need 10, 15, 20 or more routes
- Putting all this in `index.php` won't scale well, so we need to decouple our application

[< Go Back](../README.md)