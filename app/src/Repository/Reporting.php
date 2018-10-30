<?php
/**
 * Created by PhpStorm.
 * User: Notwak
 * Date: 27/10/2018
 * Time: 14:24
 */

namespace Repository;

use Entities\DataBase;
use PDO;
use PDOException;

/**
 * Class Reporting
 * @package Repository
 * Repository of Reporting where all the methods linked directly to Reporting are codded
 */
class Reporting extends DataBase
{
    const REPORTING_ALREADY_EXISTS = "REPORTING ALREADY EXIST";

    /**
     * @param array $data
     * @param PDO::FETCH_OBJ $user
     * @return string | PDOException | int
     * This function insert a report if it does not already exist in the DB for the same postHit
     * depending of the message inserted and the ID of the postHit.
     * It returns the Id of the reporting inserted when the operation is successful
     */
    function addReporting($data, $user)
    {
        if($this->reportingAlreadyExists($data["reporting"]["post_hit_id"], $user->id) == self::REPORTING_ALREADY_EXISTS) {
            return (self::REPORTING_ALREADY_EXISTS);
        }
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO reporting (post_hit_id, user_id, comment) values (?, ?, ?)");
            $stmt->bindParam(1, $data["reporting"]["post_hit_id"], PDO::PARAM_INT);
            $stmt->bindParam(2, $user->id, PDO::PARAM_STR);
            $stmt->bindParam(3, $data["reporting"]["comment"], PDO::PARAM_STR);
            try {
                $stmt->execute();
                $lii = $db->lastInsertId();
                return $lii;
            } catch (PDOException $exception) {
                return ($exception);
            }
        }

    /**
     * @param int $postHitId
     * @param int $userId
     * @return string | bool | PDOException
     * This function verify if a reporting exists depending of the user's id and the postHit's id
     * It returns a string if it has been found, or a boolean false if not.
     */
    function reportingAlreadyExists($postHitId, $userId){
            $db = parent::$dbConnection;
            $stmt = $db->prepare("SELECT COUNT(*) FROM reporting WHERE post_hit_id = ? and user_id = ?");
            $stmt->bindParam(1, $postHitId, PDO::PARAM_INT);
            $stmt->bindParam(2, $userId, PDO::PARAM_INT);
            try {
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_NUM);
                if (isset($result[0]) && $result[0] > 0)
                    return (self::REPORTING_ALREADY_EXISTS);
                else
                    return (false);
            } catch (PDOException $exception) {
                return ($exception);
            }
        }
}