<?php
//
// Postcode
// Uses postcode param in call postcode API to find postcode's coordinates.
//

namespace NearestSchools;

class Postcode
{
	protected $apiUrl = 'https://api.postcodes.io/postcodes/';
	protected $apiResponse;

	public function openApiInfo($postcode)
	{
		$postcode = htmlspecialchars(strtoupper(trim($postcode)));
		$postcodeLen = strlen($postcode);
		// basic length check
		if ($postcodeLen >= 6 && $postcodeLen <= 8)
		{
			$url = $this->apiUrl . $postcode;
			// NOTE - requires runtime setting allow_url_fopen boolean = true
			try {
				$this->apiResponse = file_get_contents($url);
			}
			catch (Exception $e) {
				error_log($this->apiUrl . ' not accessible');
				return false;
			}

			if ($this->apiResponse) {
				$this->apiResponse = json_decode($this->apiResponse);
				return $this->apiResponse->result;
			}
		}
		return false;
	}
}
