<?php
/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 13/11/2017
 * Time: 22:50
 */

namespace Repository;

use Entities\DataBase;
use Repository\Tags;
use PDO;
use PDOException;

class PostHit extends DataBase
{
    const POST_HIT_RESPONSE_NOT_EXIST = "POST_HIT_RESPONSE_NOT_EXIST";
    const POST_HIT_RESPONSE_EXIST = "POST_HIT_RESPONSE_EXIST";
    const POST_HIT_MESSAGE_EXIST_ON_OTHER = "POST_HIT_MESSAGE_EXIST_ON_OTHER";
    
    /**
     * used in $app->post('/delete/post_hit' ...
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
     * used in $app->put('/post_hit' ...
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
     * used in $app->post('/delete/user' ...
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
     * Used in $app->put('/post_hit' ...
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

    /*
    ** used in insertNewPostHit
    **
    ** return self::POST_HIT_RESPONSE_EXIST || self::POST_HIT_RESPONSE_NOT_EXIST
    ** depend on: 
    **  1 - display board && message == exist
    **  2 - display board && user == exist
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


    /*
    ** used in $app->post('/add/post_hit' ...
    **
    ** insertNewPostHit reject the request if:
    **  1 - display board && message
    **  2 - display board && user
    ** see postItExistByUserIdAndBoardId    
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
            $stmt->bindParam(4, $data["post_hit"]["axeXYZ"], PDO::PARAM_STR);
            $stmt->bindParam(5, $data["post_hit"]["message"], PDO::PARAM_STR);
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
     * used in updateMessagePostHit
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
     * used in $app->post('/post_hits' ...
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
     * used in tests
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
}