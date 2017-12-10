<?php

use PHPUnit\Framework\TestCase;

class PostcodeCoordsTest extends TestCase
{
	// No. of UK postcodes >1.7M, test that import is at least this size.
	public function testCoordsLoaded()
	{
		$coords = PostcodeCoordsDataFile::Coords();
		$this->assertGreaterThan(1700000, count($coords));
	}

	public function testApiInfo()
	{
		$info = PostcodeCoords::Info('zz1x99');
		$this->assertFalse($info);

		$info = PostcodeCoords::Info('w1a1aa');
		//$this->assertFalse(!$info);
		$this->assertEquals($info->region, 'London');
		$this->assertEquals($info->incode, '1AA');
		$this->assertEquals($info->admin_district, 'Westminster');
	}

	public function testPostcode()
	{
		$coords = PostcodeCoordsDataFile::Coords();
		// canonical test postcode
		$this->assertTrue(array_key_exists('NW11AA', $coords));
	}

	public function testDistanceCalculation()
	{
		// A nice straight route, according to google the distance between these points is 8.8 miles.
		$dist = PostcodeCoords::distanceMiles(51.6217, -0.2920, 51.5206, -0.1705);
		// accurance of +/- 0.1 mile is acceptable
		$this->assertGreaterThan(8.7, $dist);
		$this->assertLessThan(8.9, $dist);
	}
}
