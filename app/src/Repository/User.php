<?php

/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 13/11/2017
 * Time: 22:48
 */

namespace Repository;

use Entities\Database;
use Entities\Error;
use Entities\User as U;
use Entities\DataChecker as Data;
use Entities\Error as Err;
use PDO;
use PDOException;


/**
 * Class User
 * @package Repository
 * Repository of User where all the methods linked directly to tags for the user are codded
 */

class User extends Database
{

    const USER_ALREADY_EXIST = "EMAIL OR PSEUDO ALREADY EXIST";
    const USER_DO_NOT_EXIST = "USER DO NOT EXIST";
    const USER_BAD_CREDENTIALS = "USER_BAD_CREDENTIALS";


    /**
     * @param array $data
     * @return bool| PDOException| string
     * This function verify in the database if a user is corresponding to pseudo and mail given in the data
     */
    private function isExistingEmailOrPseudo($data)
    {
        if (Data::hasUserEmailPseudo($data)) {
            $db = parent::$dbConnection;
            $stmt = $db->prepare("SELECT user.id as id FROM user  WHERE email = ? OR pseudo = ? ");
            $stmt->bindParam(1, $data["user"]["email"], PDO::PARAM_STR);
            $stmt->bindParam(2, $data["user"]["pseudo"], PDO::PARAM_STR);
            try {
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_OBJ);
                if (empty($user)) {
                    return false;
                } else {
                    return true;
                }
            } catch (PDOException $exception) {
                return ($exception);
            }
        }
        return (Error::ERROR_USER_DATA_NOT_FOUND);
    }

    /**
     * @param DataBase::$dbConnection $db
     * @param $data
     * @return string
     * @throws \Exception
     * This function prepare a query to delete a user that correspond to a pseudo, mail and password and return the query
     */
    private function deleteByUser($db, $data)
    {
        try {
            if (self::isExistingEmailOrPseudo($data))
                return (self::USER_ALREADY_EXIST);
        } catch (PDOException $e) {
            throw ($e);
        }
        $stmt = $db->prepare("DELETE FROM user WHERE pseudo = ? AND email = ? AND password = ?");
        $stmt->bindParam(1, $data["user"]["pseudo"], PDO::PARAM_STR);
        $stmt->bindParam(2, $data["user"]["email"], PDO::PARAM_STR);
        $stmt->bindParam(3, $data["user"]["password"], PDO::PARAM_STR);
        return $stmt;
    }

    /**
     * @param array $data
     * @param string $status
     * @param string $admin
     * @return string | int | PDOException
     * @throws \Exception
     * This function insert a new User in the database verifying first that it doesn't already exists.
     */
    function post($data, $status, $admin)
    {
        $role = ($admin) ? U::ROLE_ADMIN : U::ROLE_USER;
        if (Data::hasUserCredentialsAndEmail($data)) {
            try {
                if (self::isExistingEmailOrPseudo($data))
                    return (self::USER_ALREADY_EXIST);
            } catch (PDOException $e) {
                throw ($e);
            }
            $db = parent::$dbConnection;
            $stmt = $db->prepare("INSERT INTO user (`pseudo`, `email`, `password`, `role_id`, `status_id`) VALUES (?, ?, ?, (SELECT id FROM role WHERE name = ?), (SELECT id FROM status WHERE name = ?))");
            $stmt->bindParam(1, $data["user"]["pseudo"], PDO::PARAM_STR);
            $stmt->bindParam(2, $data["user"]["email"], PDO::PARAM_STR);
            $stmt->bindParam(3, $data["user"]["password"], PDO::PARAM_STR);
            $stmt->bindParam(4, $role, PDO::PARAM_INT);
            $stmt->bindParam(5, $status, PDO::PARAM_STR);
            try {
                $stmt->execute();
                return ($db->lastInsertId());
            } catch (PDOException $exception) {
                throw ($exception);
            }
        }
    }


    /**
     * @param array $data
     * @param string $status
     * @return string | int
     * @throws \Exception
     * This function delete a user by id or by pseudo, mail and password if id is not given
     */
    function delete($data, $status)
    {
        if (!Data::hasUser($data)) {
            return Err::ERROR_USER_DATA_NOT_FOUND;
        }
        $db = parent::$dbConnection;
        if (Data::hasUserId($data)) {
            $stmt = $db->prepare("DELETE FROM user WHERE id = ?");
            $stmt->bindParam(1, $data["user"]["id"]);
        } else if (Data::hasUserCredentialsAndEmail($data)) {
            try {
                $stmt = $this->deleteByUser($db, $data);
            } catch (PDOException $e) {
                throw $e;
            }
        }
        try {
            $stmt->execute();
            return ($stmt->rowCount());
        } catch (PDOException $exception) {
            throw ($exception);
        }
    }


    /**
     * @param array $data
     * @param string $status
     * @return null | PDOException | null | PDO::FETCH_OBJ User
     * This function return a User finding it by pseudo and password
     */
    function getUser($data, $status)
    {
        if (Data::hasUserCredentials($data)) {

            $db = parent::$dbConnection;
            $stmt = $db->prepare("SELECT user.id as id, pseudo, email, password, role.name as role, status.name as status " .
                "FROM user INNER JOIN status ON user.status_id = status.id INNER JOIN role ON user.role_id = role.id WHERE pseudo = ? and password = ? and status.name = ?");
            $stmt->bindParam(1, $data["user"]["pseudo"], PDO::PARAM_STR);
            $stmt->bindParam(2, $data["user"]["password"], PDO::PARAM_STR);
            $stmt->bindParam(3, $status);
            try {
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_OBJ);
                if (empty($user)) {
                    return null;
                } else {
                    return $user;
                }
            } catch (PDOException $exception) {
                return ($exception);
            }
        } else {
            return null;
        }
    }
}
