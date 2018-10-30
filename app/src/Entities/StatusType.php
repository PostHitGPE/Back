<?php

namespace Entities;

/**
 * Class StatusType
 * @package Entities
 * These constants are used to easily code and find or use the status we have in database
 */

class StatusType
{
    const STATUS_TYPE_WAITING_VALIDATION_REPORT = "WAITING VALIDATION REPORT";
    const STATUS_TYPE_REPORTED = "REPORTED";
    const STATUS_TYPE_DELETED = "DELETED";
    const STATUS_TYPE_BANISHED = "BANISHED";
    const STATUS_TYPE_VALIDATED = "VALIDATED";
    const STATUS_TYPE_PENDING_VALIDATION = "PENDING VALIDATION";
}