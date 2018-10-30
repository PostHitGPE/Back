<?php
namespace Entities;

use Entities\User;


/**
 * Class DataChecker
 * @package Entities
 * Tool class to verify data received in the api
 */
class DataChecker
{

    /**
     * @param $data
     * @return bool
     * Check if the data column exist
     */
    static function hasData(&$data)
    {
        if (isset($data["data"])) {
            $data = $data["data"];
            return ($data);
        }
        return (false);
    }

    /**
     * @param $data
     * @return bool
     * Check if the data of userToDelete
     */
    static function hasUserToDeleteId($data)
    {
        return (isset($data["userToDelete"])
            && isset($data["userToDelete"]["id"]));
    }

    /**
     * @param $data
     * @return bool
     * Check if needed data for reporting is in the array
     */
    static function hasReportingData($data)
    {
        return (isset($data["reporting"]["post_hit_id"])
                && isset($data["reporting"]["comment"]));
    }

    /**
     * @param $data
     * @return bool
     * Check if the key user is in the data's array
     */
    static function hasUser($data)
    {
        return (isset($data["user"]));
    }

    /**
     * @param $data
     * @return bool
     * Check is the data array contain user key and user id key
     */
    static function hasUserId($data)
    {
        return (self::hasUser($data) && isset($data["user"]["id"]));
    }

    /**
     * @param $data
     * @return bool
     * check if the role of the user is ADMIN in the data array
     */
    static function isSendByAdmin($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["role"])
            && $data["user"]["role"] == User::ROLE_ADMIN);
    }

    /**
     * @param $data
     * @return bool
     * Check if user is present in data with email and pseudo keys
     */
    static function hasUserEmailPseudo($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["email"])
            && isset($data["user"]["pseudo"]));
    }

    /**
     * @param $data
     * @return bool
     * Check if user is present in data with password and pseudo keys
     */
    static function hasUserCredentials($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["password"])
            && isset($data["user"]["pseudo"]));
    }

    /**
     * @param $data
     * @return bool
     * Check if user is present in data with email, password, pseudo keys
     */
    static function hasUserCredentialsAndEmail($data)
    {
        return (self::hasUser($data)
            && self::hasUserCredentials($data)
            && isset($data["user"]["email"]));
    }

    /**
     * @param $data
     * @return bool
     * Check is post_hit is present in data
     */
    static function hasPostHit($data)
    {
        return isset($data["post_hit"]);
    }

    /**
     * @param $data
     * @return bool
     * check if post_hit is present in data with id key
     */
    static function hasPostHitId($data)
    {
        return (self::hasPostHit($data)
            && isset($data["post_hit"]["id"]));
    }

    /**
     * @param $data
     * @return bool
     * check if data contain tags key and has entries
     */
    static function hasTag($data)
    {
        return (isset($data["tags"]) && isset($data["tags"][0]));
    }
}
