<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HafasClient\Helper\Time;

final class HelperTest extends TestCase {

    public function testTimestampParserWithoutOffset(): void {
        $timestamp = Time::parseDatetime('20210203', '123456');
        $this->assertEquals(2021, $timestamp->year);
        $this->assertEquals(2, $timestamp->month);
        $this->assertEquals(3, $timestamp->day);
        $this->assertEquals(12, $timestamp->hour);
        $this->assertEquals(34, $timestamp->minute);
        $this->assertEquals(56, $timestamp->second);
        //$this->assertEquals(null, $timestamp->timezone); //TODO: support timezones
    }

    public function testTimestampParserWithOffset(): void {
        $timestamp = Time::parseDatetime('20210203', '01020304');
        $this->assertEquals(2021, $timestamp->year);
        $this->assertEquals(2, $timestamp->month);
        $this->assertEquals(4, $timestamp->day);
        $this->assertEquals(2, $timestamp->hour);
        $this->assertEquals(3, $timestamp->minute);
        $this->assertEquals(4, $timestamp->second);
        //$this->assertEquals(null, $timestamp->timezone); //TODO: support timezones
    }

    public function testDateParsing(): void {
        $timestamp = Time::parseDate('20210203');
        $this->assertEquals(2021, $timestamp->year);
        $this->assertEquals(2, $timestamp->month);
        $this->assertEquals(3, $timestamp->day);
    }
}
