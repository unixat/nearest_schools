<?php

namespace NearestSchools;

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('log_errors', true);


//require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

// get the coords of the postcode entered by user
$pcode = new Postcode();
if (array_key_exists('postcode', $_REQUEST)) {
	$postcode = $_REQUEST['postcode'];
	$maxDistance = $_REQUEST['maxDistance'];
	if (!$maxDistance) {
		$maxDistance = 2; // default is 2 miles
	}
}

// support web service or cli
if ('cli' == php_sapi_name()) {
	$postcode = readline('Postcode: ');
	$maxDistance = (float)readline('Max Distance (miles): ');
}

// get coordinates from API
$postcodeCoords = $pcode->openApiInfo($postcode);

if ($postcodeCoords && $postcodeCoords->latitude && $postcodeCoords->longitude) {

//  got coords (lat, long) so now search all schools
	$fp = fopen('schools.dat', 'r');
	if ($fp) {
		readfile('views/header.php');
		$lat = $postcodeCoords->latitude;
		$lon = $postcodeCoords->longitude;
		while (($school = fgetcsv($fp, 512, ",")) !== FALSE) {
	
			$schoolLat = $school[School::LAT];
			$schoolLon = $school[School::LON];
			if ($schoolLat && $schoolLon) {
				try {
					$distance = PostcodeCoords::distanceMiles($lat, $lon, $schoolLat, $schoolLon);
					// float key automatically truncated to int - rounded to nearest mile
					if ($distance > 0 && $maxDistance > $distance) {
						$school[School::DIST] = number_format($distance, 2);

						//View::results($school);
						include 'views/results.php';
					}
				}
				catch (TypeError $e) {
					error_log($school[School::NAME] . ' has missing coords');
				}
			}
		}
		fclose($fp);
	}
}
// error occurred - postcode invalid or no coords for that postcode
else {
	View::formWithError();
}
