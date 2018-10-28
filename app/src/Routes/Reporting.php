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
use Repository\Reporting;

$app->group('/api', function () use ($app) {

    new \Entities\DataBase();

    $app->post('/reporting', function (Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 400, "type" => "error", "message" => "data not found", "data" => $data], 400);
        if (!Data::hasReportingData($data)) {
            return $response = $response->withJson(["code" => 400, "type" => "error", "message" => "comment or post hit id not found", "data" => $data], 400);
        }
        $userRepository = new User();
        $postHitRepository = new PostHit();
        $reportingRepository = new Reporting();
        $user = $userRepository->getUser($data);
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

        $postHitRepository->updatePostHitStatus($data["reporting"]["post_hit_id"], StatusType::STATUS_TYPE_WAITING_VALIDATION_REPORT);
        try {
            $res = $userRepository->post($data, Entities\StatusType::STATUS_TYPE_VALIDATED, Data::isSendByAdmin($data));
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
});