<?php

namespace Src\Utils;

use DateTime;

class ReservationUtils
{
    public const string DATE_FORMAT = "Y-m-d H:i:s";

    public static function get_planning_hours(): array
    {
        return [8, 19];
    }

    public static function get_days_order(): array
    {
        return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    }

    /**
     * Summary of get_week_dates
     * @return array<DateTime>
     */
    public static function get_week_dates(): array
    {
        $days = [];
        $days_order = self::get_days_order();
        $today = new DateTime();
        $today_day = $today->format('N');
        $monday = $today->modify("-" . ($today_day - 1) . " days");

        foreach ($days_order as $i => $day) {
            $days[$day] = (clone $monday)->modify("+ $i days");
        }

        return $days;
    }
}
