<?php
require __DIR__.'/../vendor/autoload.php';
// run PostHit App 
$app = (new App\PliApp())->get();
$app->run();
