<?php

use PHPUnit\Framework\TestCase;

class SchoolTest extends TestCase
{
	public function testObjectToArray()
	{
		$s = new School;
		$s->name = 'Grange Hill';
		$s->type = 'Comprensive Academy';
		$s->authority = 'London Borough of Billingsley';
		$s->age_lo = 11;
		$s->age_hi = 18;
		$s->town = 'Northam';
		$s->postcode = 'SE17 2BL';
		$s->lat = 51.1441;
		$s->lon = 0.0378;

		$schoolArray = get_object_vars($s);
		$this->assertTrue(is_array($schoolArray));
		$this->assertEquals($schoolArray['age_hi'], 18);
	}
}
