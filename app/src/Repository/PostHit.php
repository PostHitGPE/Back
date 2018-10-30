<?php

namespace Repository;

use Entities\DataBase;
use Repository\Tags;
use PDO;
use PDOException;

/**
 * Class PostHit
 * @package Repository
 * Repository of PostHit where all the methods linked directly to PostHit are codded
 */
class PostHit extends DataBase
{
    const POST_HIT_RESPONSE_NOT_EXIST = "POST_HIT_RESPONSE_NOT_EXIST";
    const POST_HIT_RESPONSE_EXIST = "POST_HIT_RESPONSE_EXIST";
    const POST_HIT_MESSAGE_EXIST_ON_OTHER = "POST_HIT_MESSAGE_EXIST_ON_OTHER";
    const POST_HIT_DO_NOT_EXIST = "THIS POST HIT DO NOT EXIST";


    /**
     * @param int $postHitId
     * @return PDO::FETCH_OBJ postHit
     * @throws \Exception
     * This function find a postHit in the Database searching by its ID
     */
    function getById($postHitId) {

        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT post_hit.id as id, axeXYZ, post_hit.latitude as latitude, post_hit.longitude as longitude, message, reputation, status.name as status, pseudo FROM post_hit INNER JOIN status ON post_hit.status_id = status.id INNER JOIN user ON post_hit.user_id = user.id INNER JOIN display_board ON post_hit.display_board_id = display_board.id WHERE post_hit.id = ? ");
        $stmt->bindParam(1, $postHitId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw ($e);
        }
    }


    /**
     * @param int $boardId
     * @param int $userId
     * @return PDO::FETCH_OBJ postHit |PDOException
     * This function get postHit finding it by the board's id and the user's id
     */
    function getPostHitByBoardAndUser($boardId, $userId) {

        $db =  parent::$dbConnection;
        $stmt = $db->prepare("SELECT post_hit.id as id, display_board_id, axeXYZ, post_hit.latitude as latitude, post_hit.longitude as longitude, message, reputation, status.name as status FROM post_hit INNER JOIN status ON post_hit.status_id = status.id WHERE post_hit.display_board_id = ? AND post_hit.user_id = ? ");
        $stmt->bindParam(1, $boardId, PDO::PARAM_INT);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ); 
        } catch (PDOException $e) {
            return ($e);
        }
    }


    /**
     * @param int $userId
     * @return PDO::FETCH_ARRAY | PDO::FETCH_OBJ postHit | PDOException
     * This function get PostHits by User's id
     */
    function getByUserId($userId) {
        $db =  parent::$dbConnection;
        $stmt = $db->prepare("SELECT post_hit.id as id, axeXYZ, post_hit.latitude as latitude, post_hit.longitude as longitude, message, reputation, status.name as status FROM post_hit INNER JOIN status ON post_hit.status_id = status.id WHERE post_hit.user_id = ? ");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return ($stmt->rowCount() > 1) ? $stmt->fetch(PDO::FETCH_ARRAY) : $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return ($e);
        }
    }


    /**
     * @param array $data
     * @param PDO::FETCH_OBJ PostHit $postHit
     * @return bool false | PDOException | string | int
     * This function update the message of a postHit and return its id when it's successfully updated
     */
    function updateMessagePostHit($data, $postHit) {
        if (isset($data["post_hit"]["message"]) && !empty($postHit)) {
            $anotherPostHit = $this->getPostHitByMessageAndBoardId($data["post_hit"]["message"], $postHit->display_board_id);
            if ($anotherPostHit instanceof PDOException)
                return ($anotherPostHit);
            if (empty($anotherPostHit) || $anotherPostHit->id == $postHit->id) {
                $db = parent::$dbConnection;
                $stmt = $db->prepare("UPDATE post_hit SET message = ? WHERE id = ?");
                $stmt->bindParam(1, $data["post_hit"]["message"], PDO::PARAM_STR);
                $stmt->bindParam(2, $postHit->id);
                try {
                    $result = $stmt->execute();
                    return $db->lastInsertId();
                } catch (PDOException $exception) {
                    return ($exception);
                }
            } else {
                return (self::POST_HIT_MESSAGE_EXIST_ON_OTHER);
            }
        }
        return (false);
    }


    /**
     * @param int $userId
     * @param int $displayBoardId
     * @param string $message
     * @return PDOException | string | string
     * This function verify if a postHit exist in the database searching by its user's id, display board's id and message
     * and return a string depending if it has been found or not
     */
    function postItExistByUserIdAndBoardId($userId, $displayBoardId, $message) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT COUNT(*) FROM post_hit WHERE display_board_id = ? AND (user_id = ? OR message = ?)");
        $stmt->bindParam(1, $displayBoardId, PDO::PARAM_INT);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        $stmt->bindParam(3, $message, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if (isset($result[0]) && $result[0] > 0)
                return (self::POST_HIT_RESPONSE_EXIST);
            else
                return (self::POST_HIT_RESPONSE_NOT_EXIST);
        } catch (PDOException $exception) {
            return ($exception);
        }
    }


    /**
     * @param array $data
     * @param PDO::FETCH_OBJ User $user
     * @return null |PDOException | PDOException | string | int
     * This function insert a new postHit with a default reputation of value 50 depending of the selected board and user.
     * It returns the Id of the inserted Post Hit and the insertion is successful
     */
    function insertNewPostHit($data, $user) {
        $status = \Entities\StatusType::STATUS_TYPE_VALIDATED;
        if (isset($data["display_board"]) && isset($data["display_board"]["id"]) && isset($data["post_hit"]) && isset($data["post_hit"]["latitude"]) && isset($data["post_hit"]["longitude"]) && isset($data["post_hit"]["axeXYZ"]) && isset($data["post_hit"]["message"])) {
            $alreadyExist = $this->postItExistByUserIdAndBoardId($user->id, $data["display_board"]["id"], $data["post_hit"]["message"]);
            if ($alreadyExist === self::POST_HIT_RESPONSE_EXIST || $alreadyExist instanceof PDOException)
                return ($alreadyExist);
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO post_hit (display_board_id, latitude, longitude, message,axeXYZ, user_id, status_id, reputation) " .
                "VALUES(?,?,?,?,?,?,(SELECT id FROM status WHERE name = ?),50)");
            $stmt->bindParam(1, $data["display_board"]["id"], PDO::PARAM_INT);
            $stmt->bindParam(2, $data["post_hit"]["latitude"], PDO::PARAM_STR);
            $stmt->bindParam(3, $data["post_hit"]["longitude"], PDO::PARAM_STR);
            $stmt->bindParam(4, $data["post_hit"]["message"], PDO::PARAM_STR);
            $stmt->bindParam(5, $data["post_hit"]["axeXYZ"], PDO::PARAM_STR);
            $stmt->bindParam(6, $user->id, PDO::PARAM_INT);
            $stmt->bindParam(7, $status , PDO::PARAM_STR);
            try {
                $stmt->execute();
                $lii = $db->lastInsertId();
                return $lii;
            } catch (PDOException $exception) {
                return ($exception);
            }
        }
        return null;
    }


    /**
     * @param string $message
     * @param int $boardId
     * @return PDOException | PDO::FETCH_OBJ postHit
     * This function return a Posthit depending of its message and board's id
     */
    function getPostHitByMessageAndBoardId($message, $boardId) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT * FROM post_hit WHERE display_board_id = ? AND message = ?");
        $stmt->bindParam(1, $boardId, PDO::PARAM_INT);
        $stmt->bindParam(2, $message, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $postHit = $stmt->fetch(PDO::FETCH_OBJ);
                return ($postHit);
        } catch (PDOException $exception) {
            return ($exception);
        }
    }

    /**
     * @param int $displayBoardId
     * @return array PDO::FETCH_OBJ postHit
     * This function return an array of postHit depending of a displayBoard's id
     */
    function getAllPostHitFromDisplayBoard($displayBoardId) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("SELECT post_hit.id AS id, post_hit.latitude AS latitude, post_hit.longitude AS longitude, post_hit.axeXYZ, message, reputation, pseudo FROM post_hit INNER JOIN user ON post_hit.user_id = user.id WHERE post_hit.display_board_id = ?");
        $stmt->bindParam(1, $displayBoardId, PDO::PARAM_INT);
        $stmt->execute();
        $postHits = $stmt->fetchAll(PDO::FETCH_OBJ);
        return ($postHits);
    }


    /**
     * @param int $id
     * @return int
     * @throws \Exception
     * This function return a integer of the number of postHit deleted when the operation is successful.
     */
    function deleteById($id) {
        $db = parent::$dbConnection;
        try {
            $posthit = $this->getById($id);
        } catch (PDOException $e) {
            throw ($e);
        }
        $tags = new Tags();
        try {
            $tags->removeAllPostHitTags($posthit);
        } catch (PDOException $e) {
            throw ($e);
        }
        $stmt = $db->prepare("DELETE FROM post_hit WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw ($e);
        }
        $count = $stmt->rowCount();
        return ($count);
    }

    /**
     * @param int $postHitId
     * @param string $status
     * @return PDOException | int
     * This function update the postHit's status, used for example when there is a report on it or when it would be banned
     * It returns the id of the postHit when the operation is successful
     */
    function updatePostHitStatus($postHitId, $status) {
        $db = parent::$dbConnection;
        $stmt = $db->prepare("UPDATE post_hit SET status_id = (SELECT id FROM status WHERE `name` = ?) WHERE id = ?");
        $stmt->bindParam(1, $status, PDO::PARAM_STR);
        $stmt->bindParam(2, $postHitId, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            $id = $db->lastInsertId();
            return $id;
        } catch (PDOException $exception) {
            return ($exception);
        }
    }
}