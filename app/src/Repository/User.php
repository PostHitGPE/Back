<?php

/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 13/11/2017
 * Time: 22:48
 */

namespace Repository;

use Entities\Database;
use Entities\User as U;
use Entities\DataChecker as Data;
use Entities\Error as Err;
use PDO;
use PDOException;


class User extends Database
{

    const USER_ALREADY_EXIST = "EMAIL OR PSEUDO ALREADY EXIST";

    /*
     ** Repository\User::isExistingEmailOrPseudo check if
     ** users already exists with the provided email or pseudo.
     ** Return true if users exists, false if email AND pseudo are free.
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
    }

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

    /*
     ** Repository\User::postUser prepare and send an insert query on user
     ** $data from json_decode($request->getBody(), true)["data"] contain a user as Json
     ** $status from Entities\StatusType::STATUS_TYPE_VALIDATED or any
     ** $admin boolean allowing to define an admin role calling this function.
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

    /*
     ** Repository\User::postUser prepare and send an insert query on user
     ** $data from json_decode($request->getBody(), true)["data"] contain a user as Json
     ** $status from Entities\StatusType::STATUS_TYPE_VALIDATED or any
     ** $admin boolean allowing to define an admin role calling this function.
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
}
