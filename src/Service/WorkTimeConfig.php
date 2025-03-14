<?php

namespace App\Service;

class WorkTimeConfig
{
    private const MONTHLY_STANDARD_HOURS = 40;
    private const RATE = 20; // PLN
    private const OVERTIME_RATE_MULTIPLIER = 2; // 200% of RATE

    public static function getMonthlyStandardHours(): int
    {
        return self::MONTHLY_STANDARD_HOURS;
    }

    public static function getRate(): float
    {
        return self::RATE;
    }

    public static function getOvertimeRate(): float
    {
        return self::RATE * self::OVERTIME_RATE_MULTIPLIER;
    }
}
