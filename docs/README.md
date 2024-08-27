![header.png](./images/docsHeading.png)

## Table of Contents

### 1. General setup documentation
- [Swagger-PHP](pages/swagger-php-doc.md)
- [Apache virtual hosts](pages/virhost.md)
- [Environment variables](pages/env-vars.md)

### 2. Basic SlimPHP setup
- [Project structure](pages/project-structure.md)
- [SlimPHP basic setup](pages/slimphp.md)
- [Decoupling]()
- [Serving Swagger-PHP from Slim](pages/slimswaggerphp.md)
- [CORS setup](pages/cors.md)
- [Authorization setup](pages/auth.md)

#### 2.1. Error handling
- [Introduction](pages/introduction.md)
- [Set default error handler for PHP](pages/defaultErrorHandler.md)
- [Set default error handler for Slim error middleware](pages/slimerrormiddleware.php)

### 3. Swagger-PHP setup
- [GET, POST, PUT, PATCH, DELETE Annotations](pages/writing-annotations)
- [Add multiple servers](pages/swagger-servers.md)
- [Authorize api calls](pages/autorize-swagger.md)

### 4. Firebase-PHP setup
- [Firebase setup](pages/firebase.md)
- [Firebase-PHP setup](pages/firebase-php.md)

### 5. Read more 
- Documentations
  - [PHP Exceptions](https://www.php.net/manual/en/reserved.exceptions.php)
  - [Slim Skeleton](https://odan.github.io/slim4-skeleton/)
  - [Swagger-PHP](https://zircote.github.io/swagger-php/)

- Other useful links
  - [Set default error handler in PHP]()
---

## Overview

Here you can find complete technical documentation for setting up things like swagger-php, slim framework, connecting your project to firebase and more. <br/>
Look up table of contents and see if there is anything interesting for you. <br/>
This documentation is based of final exam for advanced web development course @ VTS Subotica

> Note: Recommended way for reading markdown docs is either trough GitHub or PHPStorm markdown parser. <br/>
Things like Obsidian and others have their own parser and some of the markdown rules don't match with GitHub and PHP Storm parsers

---

### Technical specs (in the moment of writing this documentation)

##### Core modules

- `PHP Version:` 8.2
- `Slim Framework:` v4.13

##### Dependencies
- `Swagger-PHP:` 4.9.2
- `SwaggerUI:` 5.11.0
- `Firebase-PHP:` 7.10.0

---

### Upgrading

If you want to try out project, I suggest to install all dep from `composer.lock` file with `> composer install` and then follow <br/>
`General setup documentation` to setup and have all up and running

However, if you wish to setup project and upgrade everything, I suggest you to make a copy of the project and go step by step, upgrading single dependency at the time. <br/>
- For PHP check [PHP Watch](https://php.watch/versions)
- For possible Slim upgrade, check [SlimPHP Docs (Upgrade Guide)](https://www.slimframework.com/docs/v4/start/upgrade.html)
- For all others dependencies, check `packagist` or official git repo

---