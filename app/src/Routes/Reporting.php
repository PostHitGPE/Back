<?php
namespace Routes;

header('Access-Control-Allow-Origin: *');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Repository\PostHit;
use Entities\DataChecker as Data;
use Entities\Error as Err;

$app->group('/api', function () use ($app) {

    new \Entities\DataBase();

    $app->post('/add/post_hit', function (Request $request, Response $response) {
    }
}