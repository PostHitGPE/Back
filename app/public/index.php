<?php
require __DIR__.'/../vendor/autoload.php';

/**
 * Entry point of the API, it initialise the APP where it stores and dispatch the routes
 */

$app = (new App\PliApp())->get();
$app->run();
