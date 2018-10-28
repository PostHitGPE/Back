<?php
namespace App;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Class PliApp
 * @package App
 * This class is used to initialize SLIM PHP (the API framework)
 */
class PliApp
{    
    /**
     * @var \Slim\App
     * which contain the SLIM framework
     */
    private $app;

    /**
     * PliApp constructor.
     * init the SLIM PHP and the routes which will be used for the api
     */
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

    /**
     * @return \Slim\App
     * accessor Get which return the local app variable
     */
    public function get()
    {
        return $this->app;
    }
}
