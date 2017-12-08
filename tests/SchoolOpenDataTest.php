<?php

use PHPUnit\Framework\TestCase;

class SchoolOpenDataTest extends TestCase
{
	protected $sObject;
	protected $postcode;
	protected $lat;
	protected $lon;

	public function Setup()
	{
		$this->sObject = new SchoolOpenData();
		// test postcode data
		$this->postcode = 'NW61TF';
		$this->lat = 51.5519;
		$this->lon = -0.1948;
	}

	public function testSchoolDataLookup()
	{
		$this->assertEquals($this->sObject->schools()[$this->postcode]->address->postcode, $this->postcode);
	}

	// test of object member referencing and data stored/retrieved is correct
	public function testLatLonCoords()
	{
		$postcodes = new PostcodeCoords();
		$postcodes->importData(); // TODO
		echo "countfor coords1=" . count($postcodes->coords()) . PHP_EOL;
		$schools = new SchoolOpenData($postcodes->coords());

		print_r($this->sObject->schools()[$this->postcode]);
		$this->assertEquals($this->sObject->schools()[$this->postcode]->address->lat, $this->lat);
		$this->assertEquals($this->sObject->schools()[$this->postcode]->address->lon, $this->lon);
	}
}
