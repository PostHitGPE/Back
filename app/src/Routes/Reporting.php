<?php
namespace Routes;

header('Access-Control-Allow-Origin: *');

use Entities\StatusType;
use Entities\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Repository\PostHit;
use Entities\DataChecker as Data;
use Entities\Error as Err;

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
     *          "id": int,
     *          "pseudo": string ,
     *          "password": string
     *      },
     *      "reporting":{
     *          "post_hit_id": string,
     *          "message": string
     *      }
     *  }
     * }
     *
     * @return Application/Json
     * Successfull return:
     *(["code" => 200, "type" => "success", "message" => "Report done successfully", "data" => []], 200);
     *
     * This route add a reporting in the database when a post hit is not appropriate by a user.
     * It also changes the status of the post hit to target it when we look in the database
     */

    $app->post('/reporting', function (Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 400, "type" => "error", "message" => "data not found", "data" => $data], 400);
        if (!Data::hasReportingData($data)) {
            return $response = $response->withJson(["code" => 400, "type" => "error", "message" => "comment or post hit id not found", "data" => $data], 400);
        }
        $userRepository = new \Repository\User();
        $postHitRepository = new \Repository\PostHit();
        $reportingRepository = new \Repository\Reporting();
        $user = $userRepository->getUser($data, StatusType::STATUS_TYPE_VALIDATED);
        if ($user == null) {
            return $response->withJson(["code" => 401, "type" => "error", "message" => \Repository\User::USER_BAD_CREDENTIALS . " OR " . \Repository\User::USER_DO_NOT_EXIST, "data" => $data], 401);
        }
            $post_hit = $postHitRepository->getById($data["reporting"]["post_hit_id"]);
        if (empty($post_hit)) {
            return $response->withJson(["code" => 400, "type" => "error", "message" => \Repository\PostHit::POST_HIT_DO_NOT_EXIST, "data" => []], 400);
        }

        if ($post_hit instanceof \Exception) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be added : ERROR PDO error n°" . $post_hit->getCode() . " " . $post_hit->getMessage(), "data" => []], 404);
        }

        $result = $reportingRepository->addReporting($data, $user);

        if ($result instanceof \Exception) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be added : ERROR PDO error n°" . $result->getCode() . " " . $result->getMessage(), "data" => []], 404);
        }

        if ($result == $reportingRepository::REPORTING_ALREADY_EXISTS) {
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => $result, "data" => $data], 404);
        }

        $result = $postHitRepository->updatePostHitStatus($data["reporting"]["post_hit_id"], StatusType::STATUS_TYPE_WAITING_VALIDATION_REPORT);
        if ($result instanceof \Exception) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The post hit's status couldn't be updated : ERROR PDO error n°" . $result->getCode() . " " . $result->getMessage(), "data" => []], 404);
        }
        return $response->withJson(["code" => 200, "type" => "success", "message" => "Report done successfully", "data" => []], 200);
    });
});