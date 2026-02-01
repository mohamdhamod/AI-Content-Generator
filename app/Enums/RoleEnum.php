<?php

namespace App\Enums;

/**
 * Role definitions for Medical AI Content Generator
 */
class RoleEnum
{
    const ADMIN = "Admin";
    const MANAGER = "Manager";
    const SUBSCRIBER = "Subscriber";

    const ALL = [
        self::ADMIN,
        self::MANAGER,
        self::SUBSCRIBER,
    ];
}
