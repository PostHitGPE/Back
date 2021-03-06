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

        $json = $request->getBody();
        $data = json_decode($json, true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "data not found", "data" => []], 404);

        $tagRepository = new \Repository\Tags();
        $userRepository = new \Repository\User();
        $displayBoardRepository = new \Repository\DisplayBoard();
        $postHitRepository = new \Repository\PostHit();

        $user = $userRepository->getUser($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if (empty($user) || $user instanceof PDOException) {
            $msg = "can't retrieve user with pseudo: " . $data["user"]["pseudo"] . " and password: " . $data["user"]["password"] . " !";
            $data = ($user instanceof PDOException) ? $msg . " PDOException coming with fail: " . $user->getMessage() : $msg;
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "User unknown from /add/post_hit", "data" => $data], 404);
        }
        $displayBoard = $displayBoardRepository->findBoardById($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if (empty($displayBoard) || $displayBoard instanceof PDOException)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "Display board not found", "data" => []], 404);
        $postHitId = $postHitRepository->insertNewPostHit($data, $user);
        if (is_null($postHitId))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "Invalid data and parameters", "data" => []], 404);
        if ($postHitId === \Repository\PostHit::POST_HIT_RESPONSE_EXIST)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "This post hit already exist or the user already posted on this display board", "data" => []], 404);
        if ($postHitId instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "The post hit could not be added : ERROR PDO error n°" . $postHitId->getCode() . " " . $postHitId->getMessage(), "data" => []], 409);
        // ERROR MANAGEMENT
        if (Data::hasTag($data)) {
            foreach ($data["tags"] as $index => $tag) {
                $dbTag = $tagRepository->findTagByName($tag);
                if (empty($dbTag)) {
                    $tagId = $tagRepository->insertNewTag($tag);
                } else {
                    $tagId = $dbTag->id;
                }
                if (!empty($tagId)) {
                    $postHitTag = $tagRepository->findPostItTagsByIds($tagId, $postHitId);
                    if (empty($postHitTag)) {
                        $tagRepository->insertPostItTag($postHitId, $tagId);
                    }
                }
            }
        }
        return $response->withJson(["code" => 200, "type" => "success", "message" => "Post hit added successfully", "data" => ["last_insert_id" => $postHitId]], 200);
    });

    $app->post('/post_hits', function (Request $request, Response $response) {

        $data = $request->getBody();
        $data = json_decode($data, true);
        $arrayPostHit = [];

        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "data not found", "data" => []], 404);

        $tagRepository = new \Repository\Tags();
        $userRepository = new \Repository\User();
        $displayBoardRepository = new \Repository\DisplayBoard();
        $postHitRepository = new \Repository\PostHit();

        $user = $userRepository->getUser($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if (empty($user) || $user instanceof PDOException) {
            $msg = "can't retrieve user with pseudo: " . $data["user"]["pseudo"] . " and password: " . $data["user"]["password"] . " !";
            $data = ($user instanceof PDOException) ? $msg . " PDOException coming with fail: " . $user->getMessage() : $msg;
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "User unknown from /post_hits", "data" => $data], 404);
        }
        $displayBoard = $displayBoardRepository->findBoardById($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if (empty($displayBoard) || $displayBoard instanceof PDOException)
            return $response->withJson(["code" => 404, "type" => "error", "message" => "Display board unknown", "data" => []], 404);
        $postHits = $postHitRepository->getAllPostHitFromDisplayBoard($displayBoard->id);
        if (empty($postHits))
            return $response->withJson(["code" => 404, "type" => "error", "message" => "No post-hit found for this display board", "data" => []], 404);
        foreach ($postHits as $postHit) {
            $tags = $tagRepository->getPostHitTags($postHit);
            if ($tags instanceof PDOException)
                return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°" . $tags->getCode() . " " . $tags->getMessage(), "data" => []], 409);
            $postHit->tags = $tags;
            $arrayPostHit["display_board"][] = ["post_hit" => $postHit];
        }
        return $response->withJson(["code" => 200, "type" => "success", "message" => "Post Hits returned successfully for the display board", "data" => $arrayPostHit], 200);
    });

    $app->put('/post_hit', function (Request $request, Response $response) {
        $data = $request->getBody();
        $data = json_decode($data, true);

        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "data not found", "data" => []], 404);

        $tagRepository = new \Repository\Tags();
        $userRepository = new \Repository\User();
        $displayBoardRepository = new \Repository\DisplayBoard();
        $postHitRepository = new \Repository\PostHit();

        $user = $userRepository->getUser($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if (empty($user) || $user instanceof PDOException) {
            $msg = "can't retrieve user with pseudo: " . $data["user"]["pseudo"] . " and password: " . $data["user"]["password"] . " !";
            $data = ($user instanceof PDOException) ? $msg . " PDOException coming with fail: " . $user->getMessage() : $msg;
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "User unknown from /post_hit", "data" => $data], 404);
        }

        $displayBoard = $displayBoardRepository->findBoardById($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);

        if (empty($displayBoard) || $displayBoard instanceof PDOException)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "Display board not found", "data" => []], 404);

        $postHit = $postHitRepository->getPostHitByBoardAndUser($displayBoard->id, $user->id);

        if (empty($postHit) || $postHit instanceof PDOException) {
            $msg = "can't retrieve posthit with display board: " . $displayBoard->id . " and user: " . $user->id . " !";
            $data = ($postHit instanceof PDOException) ? $msg . " PDOException coming with fail: " . $postHit->getMessage() : $msg;
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "Post Hit not found", "data" => $data], 404);
        }

        $result = $postHitRepository->updateMessagePostHit($data, $postHit);

        if ($result === false)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "The post hit could not be updated or parameters were missing", "data" => []], 404);
        if ($result instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°" . $result->getCode() . " " . $result->getMessage(), "data" => []], 409);
        if ($result === \Repository\PostHit::POST_HIT_MESSAGE_EXIST_ON_OTHER)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "The post hit could not be updated, the message already exist on this display board on an other post hit!", "data" => []], 404);

        $tags = $tagRepository->getPostHitTags($postHit);

        if ($tags instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°" . $tags->getCode() . " " . $tags->getMessage(), "data" => []], 409);

        $result = $tagRepository->removeAllPostHitTags($postHit);
        if ($result === false)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => "The post hit's tags could be updated, please contact the administrator", "data" => []], 404);
        if ($result instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°" . $result->getCode() . " " . $result->getMessage(), "data" => []], 409);

        if (Data::hasTag($data)) {
            foreach ($data["tags"] as $index => $tag) {
                if (!in_array($tag, $tags)) {
                    $dbTag = $tagRepository->findTagByName($tag);
                    if (empty($dbTag)) {
                        $tagId = $tagRepository->insertNewTag($tag);
                    } else {
                        $tagId = $dbTag->id;
                    }
                    if (!empty($tagId)) {
                        $postHitTag = $tagRepository->findPostItTagsByIds($tagId, $postHit->id);
                        if (empty($postHitTag)) {
                            $tagRepository->insertPostItTag($postHit->id, $tagId);
                        }
                    }
                }
            }
        }

        return $response->withJson(["code" => 200, "type" => "success", "message" => "Post Hit successfully updated!", "data" => ["last_insert_id" => $result]], 200);
    });

    /**
     * used in tests
     */
    $app->post('/delete/post_hit', function (Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        if (!Data::hasData($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => Err::ERROR_DATA_NOT_FOUND, "data" => []], 404);
        if (!Data::hasPostHitId($data))
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => Err::ERROR_NO_POSTHIT_ID, "data" => []], 404);
        $postHitRepository = new PostHit();
        if (!Data::isSendByAdmin($data))
            return $response = $response->withJson(["code" => 403, "type" => "error", "message" => Err::ERROR_MUST_BE_ADMIN, "data" => []], 404);;
        try {
            $res = $postHitRepository->deleteById($data["post_hit"]["id"]);
        } catch (PDOException $e) {
            return $response = $response->withJson(["code" => 500, "type" => Err::ERROR_PDO, "message" => "The user couldn't be deleted : ERROR PDO error n°" . $res->getCode() . " " . $res->getMessage(), "data" => []], 404);
        }
        if ($res === 0)
            return $response = $response->withJson(["code" => 404, "type" => "error", "message" => Err::ERROR_NOTHING_HAPPENED, "data" => []], 404);
        return $response->withJson(["code" => 200, "type" => "success", "message" => "User successfully deleted", "data" => []], 200);
    });
});
