<?php
namespace Routes;

header('Access-Control-Allow-Origin: *');

use JsonSchema\Uri\Retrievers\AbstractRetriever;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * /api is the beginning of routes where it's being catched
 * Then if follows the type of request and the URL specified
 */

$app->group('/api', function () use ($app) {

    new \Entities\DataBase();

    /**
     * @api /api/add/board
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
     *      "display_board":{
     *          "name": string,
     *          "latitude": string,
     *          "longitude": string,
     *          "status" : string,
     *          "altitude" : string,
     *          "description" : string
     *      }
     *  }
     * }
     * @return Json
     * Successfull return:
     * ["code" => 200, "type" => "success", "message" => "Display Board added successfully", "data" => ""], 200)
     *
     * This route add a board into the database
     */
    $app->post('/add/board', function (Request $request, Response $response) {

        $json = $request->getBody();

        $data = json_decode($json, true);
        if (!isset($data["data"]))
            return $response = $response->withJson(["code"=> 404, "type"=>"error", "message" => "data not found", "data" => []], 404);

        $data = $data["data"];
        $displayBoardRepository = new \Repository\DisplayBoard();

        $displayBoard = $displayBoardRepository->findBoardById($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);
        if ($displayBoard instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°".$displayBoard->getCode()." ".$displayBoard->getMessage(), "data" => []], 409);
        if (!empty($displayBoard))
            return $response->withJson(["code" => 404, "type" => "error", "message" => "The display board already exist, it didn't get added", "data" => []], 404);

        $board = $displayBoardRepository->insertNewBoard($data, \Entities\StatusType::STATUS_TYPE_PENDING_VALIDATION);

        if ($board instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°".$board->getCode()." ".$board->getMessage(), "data" => []], 409);
        if (empty($board))
            return $response->withJson(["code" => 404, "type" => "error", "message" => "An error occurred. The display board could not be inserted", "data" => []], 404);

        return $response->withJson(["code" => 200, "type" => "success", "message" => "Display Board added successfully", "data" => ""], 200);
    });

    /**
     * /api/boards
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
     *      "display_board":{
     *          "name": string,
     *          "latitude": string,
     *          "longitude": string,
     *          "status" : string,
     *          "altitude" : string,
     *          "description" : string
     *      }
     *  }
     * }
     *
     * @return Json
     * Successfull return:
     * ["code" => 200, "type" => "success", "message" => "Display board found", "data" => $arrayDisplayBoard], 200);
     * $arrayDisplayBoard:
     * {
     *  "data":{
     *      "display_boards":[
     *          "display_board": {
     *          "id": int
     *          "name": string,
     *          "latitude": string,
     *          "longitude": string,
     *          "altitude" : string,
     *          },
     *          "display_board":{
     *              ...
     *          }...,
     *          ...
     *      ]
     *  }
     * }
     * This route find all boards by a perimeter calculated by the parameters sent in the request
     */
    $app->post('/boards', function (Request $request, Response $response) {

        $json = $request->getBody();
        $data = json_decode($json, true);

        $arrayDisplayBoard = [];

        if (!isset($data["data"]))
            return $response = $response->withJson(["code"=> 404, "type"=>"error", "message" => \Entities\Error::ERROR_DATA_NOT_FOUND, "data" => []], 404);

        $data = $data["data"];
        $displayBoardRepository = new \Repository\DisplayBoard();

        $displayBoards = $displayBoardRepository->getBoardByPerimeter($data, \Entities\StatusType::STATUS_TYPE_VALIDATED);

        if ($displayBoards instanceof PDOException)
            return $response->withJson(["code" => 409, "type" => "error", "message" => "ERROR PDO error n°".$displayBoards->getCode()." ".$displayBoards->getMessage(), "data" => []], 409);
        if ($displayBoards == \Entities\Error::ERROR_MISSING_PARAMETERS)
            return $response->withJson(["code" => 404, "type" => "error", "message" => $displayBoards, "data" => []], 404);
        if ($displayBoards == \Entities\Error::ERROR_NO_ROWS_TO_DISPLAY) {
            return $response->withJson(["code" => 200, "type" => "success", "message" => $displayBoards, "data" => []], 200);
        }
        foreach ($displayBoards as $displayBoard){
            $arrayDisplayBoard["display_boards"][] = ["display_board" => $displayBoard];
        }
        return $response->withJson(["code" => 200, "type" => "success", "message" => "Display board found", "data" => $arrayDisplayBoard], 200);
    });
});
