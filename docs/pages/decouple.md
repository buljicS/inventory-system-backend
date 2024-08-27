[< Go Back](../README.md)

# Decouple Slim app

### 1. Overview
- Let's take look again at the our original app

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

- In conclusion, things could get pretty messy really fast
- In that case, it's much better to separate things and keep them clean

### 2. Separating app in logical pieces
- First, in root of your project, create `app/` dir
- Inside app, create `middleware/` dir
- `middleware/` is where we are going to store all of our middlewares
- For the sake of presentation, let's take out base middlewares out of the project
- Create `base.php` inside of middleware folder

```php
<?php

declare(strict_types=1);

use Selective\BasePath\BasePathMiddleware;
use Slim\App as Slim;

return function (Slim $app) {
	$app->addBodyParsingMiddleware();
	$app->add(new BasePathMiddleware($app));
	$app->addRoutingMiddleware();
	$app->addErrorMiddleware(true, false, false);
};
```

- Now in `index.php` we can do this
```php
//config middlewares
$base = require '../app/middleware/base.php';
$base($app);
```

> Note: For more reference about how middleware stack and middlewares in general work<br/>
  visit [slim docs on life cycle](https://www.slimframework.com/docs/v4/concepts/life-cycle.html) and [middleware](https://www.slimframework.com/docs/v4/concepts/middleware.html)

- We can do the same for all our middlewares in project

### 3. Separating routes from index
- Now, routes could get us in a bit more trouble, so it's also good idea to have them in separate folder
- First, inside `app/` dir, create `config/` dir
- Inside config create `routes.php`

```php
use Slim\App as Slim;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (Slim $app) {
    $app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello World');
    return $response;
});
};
```

- Now, same as for middleware, in index.php we can include this

```php
//routes
$routes = require '../app/config/routes.php';
$routes($app);
```

### 4. Overview of new index file
- After all tweaks, our `index.php` now looks like this

```php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

#region bootstrap
//config middlewares
$base = require '../app/middleware/base.php';
$base($app);

//routes
$routes = require '../app/config/routes.php';
$routes($app);
#endregion
```

- We can even extend this further by taking out whole middleware logic into `bootstrap.php` and then including file in `index.php`, but this is more than enough 

[< Go Back](../README.md)