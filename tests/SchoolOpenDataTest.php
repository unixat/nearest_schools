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
		$this->sObject = new NearestSchools\SchoolOpenData('schools.dat');
		$this->sObject->loadData();
		// test postcode data
		$this->postcode = 'NW61TF';
		$this->lat = 51.5519;
		$this->lon = -0.1948;
	}

	public function testSchoolDataLookup()
	{
		$this->assertEquals($this->sObject->schools()[$this->postcode][NearestSchools\School::POSTCODE], $this->postcode);
	}

	// test of object member referencing and data stored/retrieved is correct
	public function testLatLonCoords()
	{
		$this->assertEquals($this->sObject->schools()[$this->postcode][NearestSchools\School::LAT], $this->lat);
		$this->assertEquals($this->sObject->schools()[$this->postcode][NearestSchools\School::LON], $this->lon);
	}
}
