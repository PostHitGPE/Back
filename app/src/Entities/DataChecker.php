<?php
namespace Entities;

use Entities\User;


class DataChecker
{
    /**
     * test on 'data' key and go down to it
     */
    static function hasData(&$data)
    {
        if (isset($data["data"])) {
            $data = $data["data"];
            return ($data);
        }
        return (false);
    }

    static function hasUserToDeleteId($data)
    {
        return (isset($data["userToDelete"])
            && isset($data["userToDelete"]["id"]));
    }

    static function hasUser($data)
    {
        return (isset($data["user"]));
    }

    static function hasUserId($data)
    {
        return (self::hasUser($data) && isset($data["user"]["id"]));
    }

    static function isSendByAdmin($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["role"])
            && $data["user"]["role"] == User::ROLE_ADMIN);
    }

    static function hasUserEmailPseudo($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["email"])
            && isset($data["user"]["pseudo"]));
    }

    static function hasUserCredentials($data)
    {
        return (self::hasUser($data)
            && isset($data["user"]["password"])
            && isset($data["user"]["pseudo"]));
    }

    static function hasUserCredentialsAndEmail($data)
    {
        return (self::hasUser($data)
            && self::hasUserCredentials($data)
            && isset($data["user"]["email"]));
    }

    static function hasPostHit($data)
    {
        return isset($data["post_hit"]);
    }

    static function hasPostHitId($data)
    {
        return (self::hasPostHit($data)
            && isset($data["post_hit"]["id"]));
    }

    static function hasTag($data)
    {
        return (isset($data["tags"]) && isset($data["tags"][0]));
    }
}
