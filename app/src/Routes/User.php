<?php
namespace Routes;

header('Access-Control-Allow-Origin: *');

use Entities\StatusType;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Repository\User as User;
use Entities\DataChecker as Data;
use Entities\Error as Err;
use Repository\PostHit;

/**
 * /api is the beginning of routes where it's being catched
 * Then if follows the type of request and the URL specified
 */
$app->group('/api', function () use ($app) {

    new \Entities\DataBase();

    /**
     * /api/reporting
     * Method HTTP: POST
     *
     * Application/Json expected in request:
     *
     * {
     *  "data":{
     *      "user":{
     *          "pseudo": string ,
     *          "password": string
     *      }
     *  }
     * }
     *
     * @return Application/Json
     * Successfull return:
     *(["code" => 200, "type" => "success", "message" => "User successfully added", "data" => ["last_insert_id" => $res]], 200)
     *  $res: int
     *
     * This route add a User a verify first if the pseudo is already taken by somebody else
     */
    $app->post('/add/user', function (Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "data not found", "data" => []], 404);
        $userRepository = new User();
        try {
            $res = $userRepository->post($data, StatusType::STATUS_TYPE_VALIDATED, Data::isSendByAdmin($data));
        } catch (PDOException $e) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be added : ERROR PDO error n°" . $e->getCode() . " " . $e->getMessage(), "data" => []], 404);
        }
        if ($res === User::USER_ALREADY_EXIST)
            return $response->withJson(["code" => 404, "type" => "error", "message" => User::USER_ALREADY_EXIST, "data" => []], 404);
        if (is_null($res))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "Invalid data and parameters", "data" => [$res => $data]], 404);
        if ($res instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "The user couldn't be added : ERROR PDO error n°" . $res->getCode() . " " . $res->getMessage(), "data" => []], 409);
        return $response->withJson(["code" => 200, "type" => "success", "message" => "User successfully added", "data" => ["last_insert_id" => $res]], 200);
    });


    /**
     * /api/reporting
     * Method HTTP: POST
     *
     * Application/Json expected in request:
     *
     * {
     *  "data":{
     *      "user":{
     *          "role": string
     *      },
     *      "userToDelete":{
     *      "id": string
     *      }
     *  }
     * }
     *
     * @return Application/Json
     * Successfull return:
     *(["code" => 200, "type" => "success", "message" => "User successfully added", "data" => ["last_insert_id" => $res]], 200)
     *  $res: int
     *
     * This route delete a User and verify first if it's an Admin Request
     * For the moment it was just a test route.
     */
    $app->post('/delete/user', function (Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error delete user", "message" => "data not found", "data" => []], 404);
        $userRepository = new User();
        $posthitRepository = new PostHit();
        if (!Data::isSendByAdmin($data))
            return $response = $response->withJson(["code" => 403, "type" => "error", "message" => Err::ERROR_MUST_BE_ADMIN, "data" => []], 403);
        if (!Data::hasUserToDeleteId($data))
            return $response = $response->withJson(["code" => 403, "type" => "error", "message" => Err::ERROR_MISSING_USERTODELETE_ID, "data" => []], 403);
        try {
            $posthits = $posthitRepository->getByUserId($data["userToDelete"]["id"]);
        } catch (PDOException $e) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be deleted : ERROR PDO error n°" . $e->getCode() . " " . $e->getMessage(), "data" => []], 500);
        }
        if (is_array($posthits) && sizeof($posthits) > 0) {
            foreach ($posthits as $p) {
                try {
                    $posthitRepository->deleteById($p->id);
                } catch (PDOException $e) {
                    return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be deleted : ERROR PDO error n°" . $e->getCode() . " " . $e->getMessage(), "data" => []], 500);
                }
            }
        } else if(is_object($posthits)) {
            try {
                $posthitRepository->deleteById($posthits->id);
            } catch (PDOException $e) {
                return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be deleted : ERROR PDO error n°" . $e->getCode() . " " . $e->getMessage(), "data" => []], 500);
            }
        }
        $data["user"] = $data["userToDelete"];
        try {
            $res = $userRepository->delete($data, StatusType::STATUS_TYPE_VALIDATED);
        } catch (PDOException $e) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be deleted : ERROR PDO error n°" . $e->getCode() . " " . $e->getMessage(), "data" => []], 500);
        }
        if ($res === Err::ERROR_USER_DATA_NOT_FOUND)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => Err::ERROR_USER_DATA_NOT_FOUND, "data" => []], 404);
            if ($res === 0)
            return $response = $response->withJson(["code" => 301, "type" => "error", "message" => Err::ERROR_NOTHING_HAPPENED, "data" => []], 301);
        return $response = $response->withJson(["code" => 200, "type" => "success", "message" => "User successfully deleted", "data" => []], 200);
    });
});
