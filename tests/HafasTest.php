<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HafasClient\Hafas;
use Carbon\Carbon;
use HafasClient\Models\Line;

final class HafasTest extends TestCase {

    private static int    $exampleIbnr = 8000152;
    private static string $exampleName = 'Hannover Hbf';

    public function testDeparturesAndJourney(): void {
        $departures = Hafas::getDepartures(
            lid:         self::$exampleIbnr,
            timestamp:   Carbon::tomorrow()->setHour(8)->setMinute(0),
            maxJourneys: 1
        );

        $this->assertCount(1, $departures);
        $this->assertEquals('journey', $departures[0]['type']);
        $this->assertEquals(Line::class, get_class($departures[0]['line']));

        $journey = Hafas::getJourney($departures[0]['id']);
        $this->assertEquals($departures[0]['id'], $journey->journeyId);
    }

    public function testArrivals(): void {
        $departures = Hafas::getArrivals(
            lid:         self::$exampleIbnr,
            timestamp:   Carbon::tomorrow()->setHour(8)->setMinute(0),
            maxJourneys: 1
        );

        $this->assertCount(1, $departures);
        $this->assertEquals('journey', $departures[0]['type']);
        $this->assertEquals(Line::class, get_class($departures[0]['line']));
    }

    public function testHafasLocation(): void {
        $data = Hafas::getLocation(self::$exampleName);
        $this->assertEquals(self::$exampleIbnr, $data[0]->id);
        $this->assertEquals(self::$exampleName, $data[0]->name);
    }

    public function testNearby(): void {
        $data = Hafas::getNearby(52.376954, 9.741574);
        $this->assertEquals(self::$exampleIbnr, $data[0]->id);
        $this->assertEquals(self::$exampleName, $data[0]->name);
    }
}
