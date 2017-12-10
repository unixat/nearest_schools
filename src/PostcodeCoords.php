<?php
//
// PostcodeCoords
// Handles UK postcode info and calculations.
//
// Gets postcode, lat, long using Open Data API.
// Helper methods to calculate distance between two points.
//

class PostcodeCoords
{
	static protected $apiUrl = 'https://api.postcodes.io/postcodes/';

	//-----------------------------------------------------------------
	// Info()
	// Uses Open Data API to get all info on one individual postcode.
	//-----------------------------------------------------------------
	static public function Info($postcode)
	{
		$postcode = htmlspecialchars(strtoupper(trim($postcode)));
		$postcodeLen = strlen($postcode);
		// basic length check
		if ($postcodeLen >= 6 && $postcodeLen <= 8)
		{
			$url = self::$apiUrl . $postcode;
			// NOTE - requires runtime setting allow_url_fopen boolean = true
			try {
				$apiResponse = file_get_contents($url);
			}
			catch (Exception $e) {
				error_log(self::$apiUrl . ' not accessible');
				return false;
			}

			if ($apiResponse) {
				$apiResponse = json_decode($apiResponse);
				return $apiResponse->result;
			}
		}
		return false;
	}

	static public function distance(float $lat1, float $lon1, float $lat2, float $lon2)
	{
		static $radConversion = M_PI / 180;	// radian conversion factor
		static $earthRad = 6372.797;		// mean radius of Earth in km.

		if ($lat1 == 0 || $lat1 == 0 || $lat2 == 0 || $lon2 == 0) {
			return 0;
		}

		$lat1 *= $radConversion;
		$lon1 *= $radConversion;
		$lat2 *= $radConversion;
		$lon2 *= $radConversion;
		$diffLat = $lat2 - $lat1;
		$diffLon = $lon2 - $lon1;

		$angle = sin($diffLat / 2) * sin($diffLat / 2) + cos($lat1) * cos($lat2) * sin($diffLon / 2) * sin($diffLon / 2);
		$c = 2 * asin(sqrt($angle));
		return $earthRad * $c; 	// km 
	}

	static public function distanceMiles(float $lat1, float $lon1, float $lat2, float $lon2)
	{
		return 0.6214 * self::distance($lat1, $lon1, $lat2, $lon2);
	}
}
