<?php

namespace Src\Utils;

class ReservationUtils
{
    public const string DATE_FORMAT = "Y-m-d H:i:s";

    public static function get_planning_hours(): array
    {
        return [8, 19];
    }
}
