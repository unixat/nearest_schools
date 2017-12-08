<?php

use PHPUnit\Framework\TestCase;

class PostcodeCoordsTest extends TestCase
{
	protected $pObject;

	protected function Setup()
	{
		$this->pObject = new PostcodeCoords();
	}

	protected function tearDown() 
	{
		if (file_exists($this->pObject->localDataFile())) {
			unlink($this->pObject->localDataFile());
		}
	}

	public function testZipPath()
	{
		$this->assertEquals($this->pObject->localDataZip(), basename($this->pObject->openDataUrl()));
	}

	// do not use Setup() object, test non-default path passed to ctor.
	// NOTE this uses linux /tmp folder in pathname.
	public function testNonDefaultPath()
	{
		$testFilePath = '/tmp/postcodeCoords.csv';

		// first remove test file if it exists
		if (file_exists($testFilePath)) {
			unlink($testFilePath);
		}
		// instantiate object with specified test file path.
		$pc = new PostcodeCoords(dirname($testFilePath));

		// download the data from source
		$this->assertTrue($pc->download());

		$this->assertFileExists($pc->localDataFile());
	}

	// No. of UK postcodes >1.7M, test that import is at least this size.
	public function testImport()
	{
		$this->pObject->importData();
		$this->assertGreaterThan(1700000, count($this->pObject->coords()));
	}

	public function testPostcode()
	{
		$this->pObject->importData();
		// canonical postcode
		$this->assertTrue(array_key_exists('NW11AA', $this->pObject->coords()));
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
