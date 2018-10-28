<<<<<<< Updated upstream
<?php
/**
 * Created by PhpStorm.
 * User: Notwak
 * Date: 27/10/2018
 * Time: 14:24
 */

namespace Repository;


use Entities\DataBase;

class Reporting extends DataBase
{
    const REPORTING_ALREADY_EXISTS = "REPORTING ALREADY EXIST";

    function addReporting($data, $user)
    {
        if($this->reportingAlreadyExists($data["reporting"]["post_hit_id"], $user->getId()) == self::REPORTING_ALREADY_EXISTS) {
            return (self::REPORTING_ALREADY_EXISTS);
        }
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO reporting (post_hit_id, user_id, comment) values (?, ?, ?)");
            $stmt->bindParam(1, $data["reporting"]["post_hit_id"], PDO::PARAM_INT);
            $stmt->bindParam(2, $user->getId(), PDO::PARAM_STR);
            $stmt->bindParam(3, $data["reporting"]["comment"], PDO::PARAM_STR);
            try {
                $stmt->execute();
                $lii = $db->lastInsertId();
                return $lii;
            } catch (\PDOException $exception) {
                return ($exception);
            }
        }

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
            } catch (\PDOException $exception) {
                return ($exception);
            }
        }


=======
<?php
/**
 * Created by PhpStorm.
 * User: Notwak
 * Date: 27/10/2018
 * Time: 14:24
 */

namespace Repository;


use Entities\DataBase;

class Reporting extends DataBase
{
    const REPORTING_ALREADY_EXISTS = "REPORTING ALREADY EXIST";

    function addReporting($data, $user)
    {
        if($this->reportingAlreadyExists($data["reporting"]["post_hit_id"], $user->getId()) == self::REPORTING_ALREADY_EXISTS) {
            return (self::REPORTING_ALREADY_EXISTS);
        }
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO reporting (post_hit_id, user_id, comment) values (?, ?, ?)");
            $stmt->bindParam(1, $data["reporting"]["post_hit_id"], PDO::PARAM_INT);
            $stmt->bindParam(2, $user->getId(), PDO::PARAM_STR);
            $stmt->bindParam(3, $data["reporting"]["comment"], PDO::PARAM_STR);
            try {
                $stmt->execute();
                $lii = $db->lastInsertId();
                return $lii;
            } catch (\PDOException $exception) {
                return ($exception);
            }
        }

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
            } catch (\PDOException $exception) {
                return ($exception);
            }
        }


>>>>>>> Stashed changes
}