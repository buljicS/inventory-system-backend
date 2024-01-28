<?php

//generate swagger docs
require("../vendor/autoload.php");
$openapi = \OpenApi\Generator::scan([[$_SERVER['DOCUMENT_ROOT'].'/inventory-system/backend/controllers']]);

//write swagger docs to json file
$jsonDoc = fopen("swaggerJSON.json", "w");
fwrite($jsonDoc, $openapi->toJson());
fclose($jsonDoc);
echo 'Done, check root folder of this script for .json docs';
