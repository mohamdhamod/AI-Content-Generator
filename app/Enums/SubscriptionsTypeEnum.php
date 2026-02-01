<?php

namespace App\Enums;


class SubscriptionsTypeEnum
{
    const BASIC = 'Basic';
    const THREE_MONTHS = 'Three Months';
    const BEST = 'Best';
    const YEARLY  = 'Yearly';

    const ENTERPRISE = 'Enterprise';


    const ALL = [
        self::BASIC,
        self::THREE_MONTHS,
        self::BEST,
        self::YEARLY,
        self::ENTERPRISE,
    ];
}
