<?php
namespace App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class PliApp
{    
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * PliApp constructor.
     */
    public function __construct ()
    {
        $c = new \Slim\Container();
        $app = new \Slim\App($c);
        unset($app->getContainer()['errorHandler']);
        unset($app->getContainer()['phpErrorHandler']);
        require '../src/Routes/Posthit.php';
        require '../src/Routes/User.php';
        require '../src/Routes/DisplayBoard.php';
        $this->app = $app;
    }

    /**
     * @return \Slim\App
     */
    public function get()
    {
        return $this->app;
    }
}
