<?php
/**
 * Depuis RootDir: 
 * $ ./vendor/phpunit/phpunit/phpunit test/PosthitTest.php
 */
namespace Test;

use Httpful\Request;
use Httpful\Http;
use Httpful\Mime;

class PosthitTest extends \PHPUnit_Framework_TestCase
{
    private $rootDir;

    private function getRootDir()
    {
        $this->rootDir = dirname(__DIR__);
        return $this->rootDir;
    }
    private function getRootUrl($c9 = false)
    {
        return ($c9) ? "https://post-hit-posthit.c9users.io/app/public/api/" : "http://localhost/pli/PostHitAPI/public/api/";
    }

    private function ExecuteTest($method, $uri, $data) {
        static $counter = 0;
        ++$counter;
        echo "\n\n--------- TEST N°" . $counter . " -----------\n\nCALL ON: " . $uri;
        $r = $this->sendRequestTo($method, $uri, $data);
        echo "\nTEST RESPONSE: " . $r;
        return $r;
    }

    private function setUserToDeleteId($json, $id)
    {
        $model = json_decode($json);
        $data = $model->data;
        $data->userToDelete->id = $id;
        $model->data = $data;
        return json_encode($model);
    }

    private function setUserId($json, $id)
    {
        $model = json_decode($json);
        $data = $model->data;
        $data->user->id = $id;
        $model->data = $data;
        return json_encode($model);
    }

    private function setPostHitId($json, $id)
    {
        $model = json_decode($json);
        $data = $model->data;
        $data->post_hit->id = $id;
        $model->data = $data;
        return json_encode($model);
    }

    private function parseModelToTestData($json) 
    {
        $model = json_decode($json);
        $data = $model->data;
        $data->user->pseudo = "Test";
        $data->user->password = "Test";
        $data->user->email = "Test@etna-alternance.net";
        $data->user->status = "ADMIN";
        $data->userToDelete->pseudo = "Test";
        $data->userToDelete->password = "Test";
        $data->userToDelete->email = "Test@etna-alternance.net";
        $data->userToDelete->status = "ADMIN";
        $data->post_hit->message = "test message";
        $model->data = $data;
        return json_encode($model);
    }

    private function sendRequestTo($method, $uri, $body)
    {
        $template = Request::init()      // Alternative to Request::post/get/put/post/delete
            ->withoutStrictSsl()        // Ease up on some of the SSL checks
            //->expectsHtml()             // Expect HTML responses
            ->expectsJson()
            //->sendsType(Mime::JSON)
            ;    // http://phphttpclient.com/docs/class-Httpful.Mime.html
        Request::ini($template);
        switch ($method) {
            case "GET":
                return Request::get($uri)->send();
            case "PUT":
                return Request::put($uri)->body($body)->send();
            case "DELETE":
                return Request::delete($uri)->body($body)->send();
            default:
                return Request::post($uri)->body($body)->send();
        };
    }

    public function testPosthitApp()
    {
        // set to true to test distant api
        $c9 = false;
        $json = file_get_contents($this->getRootDir() . '/model.json');
        echo "\nTEST MODEL:\n" . $json;
        $data = $this->parseModelToTestData($json);
        $data = $this->setUserToDeleteId($data, 10);
        echo "\nTEST DATA:\n" . $data;

        // USER
        // ADD TMP TEST USER IN DB
        $uri = $this->getRootUrl($c9) . "add/user";
        $response = $this->ExecuteTest("POST", $uri, $data);
        $this->assertEquals("200", $response->code);
        $data = $this->setUserId($data, $response->body->data->last_insert_id);
        $data = $this->setUserToDeleteId($data, $response->body->data->last_insert_id);

        // POSTHITS
        // GET
        $uri = $this->getRootUrl($c9) . "post_hits";
        $this->assertEquals(200, $this->ExecuteTest("POST", $uri, $data)->code);

        // POST
        $uri = $this->getRootUrl($c9) . "add/post_hit"; 
        $response = $this->ExecuteTest("POST", $uri, $data);
        $data = $this->setPostHitId($data, $response->body->data->last_insert_id); 
        $this->assertEquals(200,  $response->code);

        // PUT
        $uri = $this->getRootUrl($c9) . "post_hit"; 
        $response = $this->ExecuteTest("PUT", $uri, $data);
        $this->assertEquals(200,  $response->code);
        
        // DELETE
        $data = $this->setPostHitId($data, 14); //$response->body->data->last_insert_id
        $uri = $this->getRootUrl($c9) . "delete/post_hit";
        $this->assertEquals(200, $this->ExecuteTest("POST", $uri, $data)->code);

        // USER
        // DELETE TMP TEST USER IN DB
        $uri = $this->getRootUrl($c9) . "delete/user";
        $this->assertEquals(200, $this->ExecuteTest("POST", $uri, $data)->code);
 
    }
}
