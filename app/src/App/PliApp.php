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

    public function __construct () 
    {
        $c = new \Slim\Container();
        $app = new \Slim\App($c);
        unset($app->getContainer()['errorHandler']);
        unset($app->getContainer()['phpErrorHandler']);
        require __DIR__.'/../Routes/Posthit.php';
        require __DIR__.'/../Routes/User.php';
        require __DIR__.'/../Routes/DisplayBoard.php';
        require __DIR__.'/../Routes/Reporting.php';
        $this->app = $app;
    }

    public function get()
    {
        return $this->app;
    }
}
