# Installation of Swagger-PHP step-by-step

### 1. Install dependencies
- Install swagger-php via composer to your project `composer require zircote/swagger-php`
- Download Swagger-UI https://swagger.io/tools/swagger-ui/download/
<hr>

### 2. Setup swagger documentation
- In your project root, create folder `documentation`
- Find downloaded Swagger-UI and copy everything from `dist` to `documentation/swagger` (create swagger folder if you didn't)
- Note that you need to change paths to all files inside `swagger/index.html` since it has been moved
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
> Note: `['/path/to/project']` is path where your `Controller.php` class is stored, you can enter relative path here

<hr>

### 3. Configure Controler.php
- First, add proper namespace to your class, for example, for Controler.php namespace would be `namespace Controller`
- Configure using for annotations `use OpenApi\Annotations as OA;`
- Add `@OA\Info` annotation to your controller
- Same goes for every method in your controller, add proper annotatations `@OA\Get` and `@OA\Response`
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
    For more informations about annotations and options you have, please visit https://zircote.github.io/swagger-php/guide/annotations.html
    Also, if you are looking for refernces, you can check https://zircote.github.io/swagger-php/reference/annotations.html

<hr>

### 4. Setup autoloader
- Solution provided here won't work straight away
- We need to tell autoloader where to look for our controller
- In `composer.json` add following at the very top of the file
- Your composer.json should look similar to this (assuming that `controllers/` folder is in root folder of your php project)
    
    ```json
    {
      "autoload": {
        "psr-4": {
          "Controller\\": "./controllers/"
        }
    },
    "require": {
      "zircote/swagger-php": "4.7.*",
      }
    }
    ```

- Now open terminal from your php project root folder and type followning command `composer dump-autoload`
- That should be it
- Open `generate-docs.php` from your web server, you should end up with message `Done, check root folder of this script for .json docs`
- Whenever you want to override current documentation (json) , just visit 
- You should be able to see `swagger-docs.json` inside of `documentation`
  
> Note: In the time of writing this solution, latest stable version of swagger-php is `4.8.3` and Swagger-UI is at `5.11.0`, while PHP version used in this project is `PHP 8.2`

<hr>

### 4. Setup Swagger-UI
- Now when we have swagger docs generated, we need to show them in swagger-ui
- Open `index.html` file and look up for `<script src="./swagger-initializer.js" charset="UTF-8"></script>`
- Open `swagger-initializer.js` file and change location of .json documentation from `https://petstore.swagger.io/v2/swagger.json` to `../swagger-docs.json`
- Your `swagger-initializer.js` now should look like this

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
  
![image](https://github.com/buljicS/inventory-system/assets/124562282/0349de62-8cea-4b3e-8407-f9a8bac2f0d1)
