<?php

namespace App\Service;

class WorkTimeConfig
{
    private const MONTHLY_STANDARD_HOURS = 40;
    private const RATE = 20; // PLN
    private const OVERTIME_RATE_MULTIPLIER = 2; // 200% of RATE
    private const ROUND_FACTOR = 30; // round work time per day to 30 minutes

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

    public static function roundDailyWorkTimeToHours(int $realWorkTimeMinutes): float
    {
        $roundTemp = round($realWorkTimeMinutes / self::ROUND_FACTOR);
        return $roundTemp * self::ROUND_FACTOR / 60;
    }
}
