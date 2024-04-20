[< Go Back](../README.md)

# Setup environment variables in PHP project


### 1. Install dependencies
- Install `phpdotenv` via composer to your project `composer require vlucas/phpdotenv`

### 2. Create env file in your project
- In your project root create `.env`

```dotenv
# .env file
JWT_SECRET='jwtsignature2024'
DB_DSN='mysql:host=myhost;dbname=database;charset=utf8' #mariadb connection string
```

- In the same manner create `.env.example` file

```dotenv
# .env.example file 
JWT_SECRET='secret'
DB_DSN='mysql:host=example;dbname=example;charset=utf8' #mariadb connection string
```

> Note: `.env.example` serves as template for creating real .env file, since `.env` file itself will be ignored by version controll system

### 3. Ignore env file
- If you haven't already, create `.gitignore` file in root of your php project
- Add following line to gitignore: `/.env`
- That's it, now your env file is being ignored, and all other users will create their own env based on `.env.example`

### 4. Load env variables into project
- In your main project file, `app.php`, `index.php` , add following two lines
```php
$dEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/project/root/');
$dEnv->safeLoad();
```
- `/project/root/` is just a path to root of your project, where `.env` file is typically stored
- That's it, your vars from env file are now available through `$_ENV` , `$_SERVER` super-globals and `getenv()` function

> Note: `phpdotenv` loads env vars globally, it really doesn't matter where you load it from, root or subdirs in your project

> Note: For more information about loading and usage of `phpdotenv` package, visit [phpdotenv github repo](https://github.com/vlucas/phpdotenv)

<hr>

[< Go Back](../README.md)