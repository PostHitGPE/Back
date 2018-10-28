<?php
/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 22/11/2017
 * Time: 14:36
 */

namespace Entities;


class Error
{
    const ERROR_MISSING_PARAMETERS = "Parameters are missing for the request";
    const ERROR_DATA_NOT_FOUND = "No data found";
    const ERROR_USER_DATA_NOT_FOUND = "No user data found";
    const ERROR_NO_ROWS_TO_DISPLAY = "No results found";
    const ERROR_MUST_BE_ADMIN = "Must be registered as an administrator of the service";
    const ERROR_PDO = "A PDO request failed";
    const ERROR_NO_POSTHIT_ID = "A Posthit ID is missing";
    const ERROR_NOTHING_HAPPENED = "Damn, nothing happened";
    const ERROR_MISSING_USERTODELETE_ID = "The id to the 'userToDelete' is missing";
}