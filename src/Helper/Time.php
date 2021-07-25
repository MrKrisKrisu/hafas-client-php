<?php

namespace HafasClient\Helper;

use Carbon\Carbon;

abstract class Time {

    public static function parseDatetime(string $rawDate, string $rawTime): Carbon {
        $year  = substr($rawDate, 0, 4);
        $month = substr($rawDate, 4, 2);
        $day   = substr($rawDate, 6, 2);

        $hour   = substr($rawTime, -6, 2);
        $minute = substr($rawTime, -4, 2);
        $second = substr($rawTime, -2, 2);

        //TODO: support timezones
        $timestamp = Carbon::create($year, $month, $day, $hour, $minute, $second);

        if(strlen($rawTime) > 6) {
            $dayOffset = (int)substr($rawTime, -8, 2);
            $timestamp->addDays($dayOffset);
        }

        return $timestamp;
    }

    public static function parseDate(string $rawDate): Carbon {
        $year  = substr($rawDate, 0, 4);
        $month = substr($rawDate, 4, 2);
        $day   = substr($rawDate, 6, 2);

        return Carbon::create($year, $month, $day);
    }

}