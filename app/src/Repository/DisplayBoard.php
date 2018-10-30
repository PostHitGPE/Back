<?php
/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 13/11/2017
 * Time: 22:51
 */

namespace Repository;

use Entities\DataBase;
use PDO;
use PDOException;

/**
 * Class DisplayBoard
 * @package Repository
 * Repository of DisplayBoard where all the methods linked directly to Displayboards are codded
 */
class DisplayBoard extends DataBase
{
    /**
     * @param array $data
     * @param string $status
     * @return \Exception|PDO::Fetch OBJ DisplayBoard| \PDOException | null
     * This function will find a displayboard by its ID and a validated Status in the database
     */
    function findBoardById($data, $status)
    {
        if (isset($data["display_board"]) && isset($data["display_board"]["id"])) {
            $db = parent::$dbConnection;
            $stmt = $db->prepare("SELECT display_board.* FROM display_board INNER JOIN status ON display_board.status_id = status.id WHERE display_board.id = ? and status.name = ?");
            $stmt->bindParam(1, $data["display_board"]["id"], PDO::PARAM_INT);
            $stmt->bindParam(2, $status, PDO::PARAM_STR);
            try {
                $stmt->execute();
                $displayBoard = $stmt->fetch(PDO::FETCH_OBJ);
                return ($displayBoard);
            } catch (PDOException $exception) {
                return ($exception);
            }
        } else {
            return null;
        }
    }

    /**
     * @param array $data
     * @param string $status
     * @return \Exception|int|PDOException|PDOException
     * This function insert a new Board
     */
    function insertNewBoard($data, $status)
    {
        if (isset($data["board"]["longitude"]) && isset($data["board"]["latitude"]) && isset($data["board"]["name"])) {
            $alreadyExist = $this->findBoardById($data);
            if ($alreadyExist instanceof PDOException)
                return ($alreadyExist);
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO display_board(name, latitude, longitude, status_id, altitude, description) VALUES (?,?,?,?,?,?)");
            $stmt->bindParam(1, $data["board"]["name"], PDO::PARAM_STR);
            $stmt->bindParam(2, $data["board"]["latitude"], PDO::PARAM_STR);
            $stmt->bindParam(3, $data["board"]["longitude"], PDO::PARAM_STR);
            $stmt->bindParam(4, $data["board"]["status"], PDO::PARAM_STR);
            $stmt->bindParam(5, $data["board"]["altitude"], PDO::PARAM_STR);
            $stmt->bindParam(6, $data["board"]["description"], PDO::PARAM_STR);
            $stmt->bindParam(7, $status, PDO::PARAM_STR);
            try {
                $stmt->execute();
                return ($db->lastInsertId());
            } catch (PDOException $exception) {
                return ($exception);
            }
        }
        return null;
    }

    /**
     * @param array $data
     * @param string $status
     * @return string | array PDO::FETCH_OBJ displayboard | \PDOException| string
     * This function get display board depending the perimeter selected in the mobile application
     */
    function getBoardByPerimeter($data, $status)
    {
        if (isset($data["display_board"]) && isset($data["display_board"]["latitude"]) && isset($data["display_board"]["longitude"])) {
            $db = parent::$dbConnection;
            $longitudeLess = $data["display_board"]["longitude"] - 1;
            $longitudePlus = $data["display_board"]["longitude"] + 1;
            $latitudeLess = $data["display_board"]["latitude"] - 1;
            $latitudePlus = $data["display_board"]["latitude"] + 1;

            $stmt = $db->prepare("SELECT display_board.id as id, display_board.longitude as longitude, display_board.latitude as latitude, display_board.altitude as altitude, display_board.name as name ".
                "FROM display_board INNER JOIN status ON display_board.status_id = status.id ".
                "WHERE longitude BETWEEN ? AND ? ".
                "AND latitude BETWEEN ? AND ? ".
                "AND status.name = ?");
            $stmt->bindParam(1, $longitudeLess, PDO::PARAM_STR);
            $stmt->bindParam(2, $longitudePlus, PDO::PARAM_STR);
            $stmt->bindParam(3, $latitudeLess, PDO::PARAM_STR);
            $stmt->bindParam(4, $latitudePlus, PDO::PARAM_STR);
            $stmt->bindParam(5, $status, PDO::PARAM_STR);
            try {
                $stmt->execute();
                $boardPerimeter = $stmt->fetchAll(PDO::FETCH_OBJ);
                if (count($boardPerimeter) == 0)
                    return (\Entities\Error::ERROR_NO_ROWS_TO_DISPLAY);
                return ($boardPerimeter);
            } catch (PDOException $exception) {
                return ($exception);
            }
        }

        return (\Entities\Error::ERROR_MISSING_PARAMETERS);
    }

}