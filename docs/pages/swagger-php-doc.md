[< Go Back](../README.md)
# Installation of Swagger-PHP step by step


### 1. Install dependencies
- Install swagger-php via composer to your project `composer require zircote/swagger-php`
- Install doctrine annotations `composer require doctrine/annotations`
- Download Swagger-UI https://swagger.io/tools/swagger-ui/download/

<hr>
	
### 2. Setup swagger documentation
- In your project root, create folder `documentation`
- Find downloaded Swagger-UI and copy everything from `dist` to `documentation/swagger` folder (create swagger folder if you didn't)
- Note that you need to change paths for all files inside `swagger/index.html` since it has been moved
- Now create `generate-docs.php` file inside of `documentation` folder
```php
<?php
  require("vendor/autoload.php");

  //Generate docs
  $openapi = \OpenApi\Generator::scan(['/path/to/project']);

  //Write new docs to .json file
  $jsonDoc = fopen("swagger-docs.json", "w");
  fwrite($jsonDoc, $openapi->toJson());
  fclose($jsonDoc);
  echo 'Done, check root folder of this script for .json docs';

```
> Note: `['/path/to/project']` is path where your `Controller.php` class is stored or where all of your controllers are stored, usually something like `src/App/Controllers` or `src/Controllers`

<hr>
	
### 3. Configure Controller.php
- First, add proper namespace to your class, for example, if your Controller.php is in `Controllers` folder namespace would be `namespace Controllers`
- Configure `using` for annotations like `use OpenApi\Annotations as OA;`
- Add `@OA\Info` annotation to your controller
- Same goes for every method in your controller, add proper annotations `@OA\Get` and `@OA\Response`
- Finally, you should end up with something similar to this

  ```php
  namespace Controller;
  use OpenApi\Annotations as OA;
  /**
   * @OA\Info(title="Inventory managment system API", version="1.0")
   */
  class Controller {
    	/**
	     * @OA\Get(
	     *     path="/api/resource/",
	     * 	   tags={"Student"},
	     *     @OA\Response(response="200", description="An example resource")
	     * )
	     */
	     public function returnInteger():int
	     {
		      return 5;
	     }
  }
  ```
  > Note: This is very basic setup of swagger-docs, thus not much info will be provided later in Swagger-UI
    For more information about annotations and options you have, please visit https://zircote.github.io/swagger-php/guide/annotations.html
    Also, if you are looking for references, you can check https://zircote.github.io/swagger-php/reference/annotations.html

<hr>

### 4. Setup autoloader
- Solution provided here won't work straight away
- We need to tell autoloader where to look for our controller
- In `composer.json` add following at the very top of the file
- Your composer.json should look similar to this (assuming that `controllers/` folder is in the root folder of your php project)
    
    ```json
    {
      "autoload": {
        "psr-4": {
          "Controllers\\": "./controllers/"
        }
    },
    "require": {
      "zircote/swagger-php": "4.7.*"
      }
    }
    ```

- Now open terminal from your php project root folder and type following command `composer dump-autoload`
- That should be it
- Open `generate-docs.php` from your web server, you should end up with message `Done, check root folder of this script for .json docs`
- Whenever you want to override current documentation (json) , just visit `generate-docs.php` page
- Now you should be able to see `swagger-docs.json` inside of `documentation` folder
- NOTE: You can tell autoloader to look for controllers in whole dir, for example `"": "./src/"`
> Note: In the time of writing this solution, latest stable version of swagger-php is `4.8.7` and Swagger-UI is at `5.11.0`, while PHP version used in this project is `PHP 8.2`

<hr>


### 5. Setup Swagger-UI
- Now when we have swagger docs generated, we need to show them in swagger-ui
- Open `index.html` file and look up for `<script src="./swagger-initializer.js" charset="UTF-8"></script>`
- Open `swagger-initializer.js` file and change location of .json documentation from `https://petstore.swagger.io/v2/swagger.json` to `../swagger-docs.json`
- Your `swagger-initializer.js` now should look like this

> Note: New location of json file is added while assuming that your `swagger-docs.json` file is in `documentation` folder

  ```js
  window.onload = function () {
    //<editor-fold desc="Changeable Configuration Block">

    // the following lines will be replaced by docker/configurator, when it runs in a docker-container
    window.ui = SwaggerUIBundle({
      url: "../swagger-docs.json",
      dom_id: "#swagger-ui",
      deepLinking: true,
      presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
      plugins: [SwaggerUIBundle.plugins.DownloadUrl],
      layout: "StandaloneLayout",
    });

    //</editor-fold>
  };
  ```
- That's it, now visit `localhost/yourproject/documentation/swagger/`

<hr>

### 6. Auto-generate new docs
- Until now, we had to visit `generate-docs.php` file if we wanted to generate or override .json docs
- This can be automatized
- First, rename `documentation` folder to `swagger`
- `swagger/swagger` folder rename to `ui`
- `generate-docs.php` rename to `index.php` and modify it

```php
<?php
  require("vendor/autoload.php");

  //Generate docs
  $openapi = \OpenApi\Generator::scan(['/path/to/project']);

  //Write new docs to .json file
  $jsonDoc = fopen("swagger-docs.json", "w");
  fwrite($jsonDoc, $openapi->toJson());
  fclose($jsonDoc);
  //echo 'Done, check root folder of this script for .json docs';
  header("Location: ./ui")

```

- Each time you visit `yourproject/swagger` new docs will be generated

<hr>

### 7. Possible errors

#### 0. Check if all dependencies are installed
- Some errors could be triggered if some dep are not installed
- In my case, I forgot to install `composer require doctrine/annotations` and even tho I had everything set up, annotations didn't work

#### 1. Warning: Skipping unknown \Class ... at line 31
- https://github.com/zircote/swagger-php/issues/1136
- http://zircote.github.io/swagger-php/guide/faq.html#skipping-unknown-someclass

#### 2. Warning: Required @OA\Info() not found
- http://zircote.github.io/swagger-php/guide/faq.html#warning-required-oa-info-not-found

#### 3. Unable to merge @OA\Get … Post
- This is happening probably because two methods have annotations with same path, check param path in these annotations

<hr>

### References

#### php-swagger:
 - https://github.com/zircote/swagger-php
 - https://zircote.github.io/swagger-php/

#### Swagger-UI:
 - https://swagger.io/
 - https://swagger.io/tools/swagger-ui/

#### Doctrine Annotations
 - https://github.com/doctrine/annotations
 - https://www.doctrine-project.org/projects/annotations.html

<hr>

[< Go Back](../README.md)