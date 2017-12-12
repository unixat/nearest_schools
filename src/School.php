<?php
//
// School
// Represents a school data record which is saved in CSV format.
//

namespace NearestSchools;

class School
{
	public $name;
	public $type;
	public $authority;
	public $age_lo;
	public $age_hi;
	public $street;
	public $town;
	public $postcode;
	public $lat;
	public $lon;

	const NAME=0;
	const TYPE=1;
	const AGE_LO=3;
	const AGE_HI=4;
	const STREET=5;
	const TOWN=6;
	const POSTCODE=7;
	const LAT=8;
	const LON=9;
	const DIST=10;

	// $fp should be a valid open file pointer
	public function writeCsvFormat($fp)
	{
		if ($fp) {
			$dataArray = get_object_vars($this);	
			if ($dataArray) {
				return fputcsv($fp, $dataArray, ',');
			}
		}
		return false;
	}
}
