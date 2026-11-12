<?php

namespace App\Support;

use DateTimeInterface;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

final class JalaliDate
{
    public static function format(?DateTimeInterface $date, string $format = 'Y/m/d'): string
    {
        if ($date === null) {
            return '';
        }

        return CalendarUtils::convertNumbers(Jalalian::fromDateTime($date)->format($format));
    }
}
