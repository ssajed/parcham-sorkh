<?php

namespace Tests\Unit;

use App\Support\JalaliDate;
use DateTimeImmutable;
use DateTimeZone;
use Tests\TestCase;

class JalaliDateTest extends TestCase
{
    public function test_it_formats_gregorian_date_as_jalali_and_preserves_clock_time(): void
    {
        $date = new DateTimeImmutable('2025-03-21 14:05:00', new DateTimeZone('UTC'));

        $this->assertSame('۱۴۰۴/۰۱/۰۱ ۱۴:۰۵', JalaliDate::format($date, 'Y/m/d H:i'));
    }

    public function test_it_returns_empty_string_for_null_date(): void
    {
        $this->assertSame('', JalaliDate::format(null));
    }
}
