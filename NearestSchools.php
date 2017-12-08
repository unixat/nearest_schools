<?php

require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

// get the coords of the postcode entered by user
$pcode = new Postcode();
$postcodeCoords = $pcode->openApiInfo($_GET['postcode']);

if ($postcodeCoords && $postcodeCoords->latitude && $postcodeCoords->longitude) {

//  got coords (lat, long) so now search all schools
	$fp = fopen('schools.dat', 'r');
	if ($fp) {
		readfile('views/header.php');
		$lat = $postcodeCoords->latitude;
		$lon = $postcodeCoords->longitude;
		$maxDistance = $_GET['maxDistance'];
		while (($school = fgetcsv($fp, 512, ",")) !== FALSE) {

			try {
				$distance = PostcodeCoords::distanceMiles($lat, $lon, $school[School::LAT], $school[School::LON]);
				// float key automatically truncated to int - rounded to nearest mile
				if ($distance > 0 && $maxDistance > $distance) {
					$school[School::DIST] = number_format($distance, 2);

					include 'views/SchoolsFound.php';
				}
			}
			catch (TypeError $e) {
				error_log($school[School::NAME] . ' has missing coords');
			}
		}
		fclose($fp);
	}
}
// error occurred - postcode invalid or no coords for that postcode
else {
	View::formWithError();
}
