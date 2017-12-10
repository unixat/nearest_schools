<?php

use PHPUnit\Framework\TestCase;

class PostcodeCoordsDataFileTest extends TestCase
{
	protected $pc;

	protected function Setup()
	{
		$this->pc = new PostcodeCoordsDataFile();
	}

	protected function tearDown() 
	{
		if (file_exists($this->pc->localDataFile())) {
			unlink($this->pc->localDataFile());
		}
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
		$pc = new PostcodeCoordsDataFile(dirname($testFilePath));

		// download the data from source
		$this->assertTrue($pc->download());

		$this->assertFileExists($pc->localDataFile());
	}

	// No. of UK postcodes >1.7M, test that import is at least this size.
	public function testImport()
	{
		$coords = PostcodeCoordsDataFile::Coords();
		$this->assertGreaterThan(1700000, count($coords));
	}

	public function testPostcode()
	{
		$coords = PostcodeCoordsDataFile::Coords();
		// canonical postcode
		$this->assertTrue(array_key_exists('NW11AA', $coords));
	}
}
