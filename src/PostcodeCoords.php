<?php
//
// PostcodeCoords
// Gets postcode, lat, long data from Open source.
// Stores lat-long data in array keyed by postcode.
//

class PostcodeCoords
{
	protected static $openDataUrl = 'https://www.freemaptools.com/download/full-postcodes/ukpostcodes.zip';
	protected $localData;
	protected $localZip;
	protected $localDataDir;
	protected $coords;

	//-------------------------------------------------------------
	// the paths of the data file & zipped data file respectively.
	//-------------------------------------------------------------
	public function openDataUrl()		{ return self::$openDataUrl; }
	public function localDataFile() 	{ return $this->localData; }
	public function localDataZip()		{ return $this->localZip; }

	//------------------------------------------------------
	// postcode coords array,
	// this array populated by calling $this->importData().
	//------------------------------------------------------
	public function coords() 	{ return $this->coords; }

	function __construct($destDataDir = null)
	{
		// get the file name from the URL
		$this->localZip = basename(self::$openDataUrl);

		// optionally set the dest dir for the data file
		if ($destDataDir) {
	
			// check if param passed it is actually a directory
			if (!is_dir($destDataDir)) {
				throw new Exception($destDataDir . " is not a directory");
			}

			$this->localDataDir = $destDataDir;
			// ensure directory has path terminator
			if (substr(($this->localDataDir), -1) != '/') {
				$this->localDataDir .= '/';
			}
			$this->localData = $this->localDataDir . str_replace('.zip', '.csv', $this->localZip);
		}
		else {
			// default data path is current dir
			$this->localData = str_replace('.zip', '.csv', $this->localZip);
			$this->localDataDir = './';
		}
		$this->importData();
	}

	//-----------------------------------------------
	// import data from data file to array.
	// if data file not found download the data
	// from open data source.
	//-----------------------------------------------
	public function importData()
	{
		if (!file_exists($this->localData)) 
		{
			// file not there, try downloading it
			$this->download();
		}

		// there are more elegant solutions - this one attempts to minimise memory usage
		$ifp = fopen($this->localData, 'r');
		if (!$ifp) {
			throw new Exception("Cannot open data file " . $this->localData);
		}
		while (($line = fgetcsv($ifp, 26, ',')) !== false) {
			if (count($line) >= 3) {
				  $this->coords[$line[0]] = $line[1] . ':' . $line[2];
			}
			else {
				error_log('Missing coords for ' . $postcode);
			}
		}
		fclose($ifp);
	}

	//-----------------------------------------------------------
	// download()
	// Called if data file not found by importData().
	// Could also be called by scheduler to get update to data.
	//
	// returns false if failed to get data.
	// data is compressed so use ZipArchive to extract.
	//-----------------------------------------------------------
	public function download()
	{
		if (file_put_contents($this->localZip, fopen(self::$openDataUrl, 'r'))) 
		{
			$zip = new ZipArchive;
			if ($zip->open($this->localZip) === true) {
				$ret = $zip->extractTo($this->localDataDir);
				$this->filterDataFile();
				$zip->close();
				return $ret;
			}
		}
		return false;
	}

	//-------------------------------------------------------
	// filterDataFile()
	// remove unnecessary info from data file.
	// e.g. id field, excessive decimal places from coords.
	//-------------------------------------------------------
	public function filterDataFile()
	{
		$origFile = ($this->localDataDir ? $this->localDataDir . basename($this->localData) : $this->localData);
		$newFile = $origFile . 'x';
		if (rename($origFile, $newFile)) {
			$fpi = fopen($newFile, 'r');
			$fpo = fopen($origFile, 'w');
			$firstLine = true;
			while (($line = fgets($fpi)) !== false) {
				$field = explode(',', $line);
				if (!$firstLine) {
					$lat = number_format((double)$field[2],4,'.','');
					$lon = number_format((double)$field[3],4,'.','');
					fwrite($fpo, str_replace(' ','',$field[1]) . ',' . $lat . ',' . $lon . PHP_EOL);
				}
				$firstLine = false;
			}
			fclose($fpi);
			fclose($fpo);
			unlink($newFile);
		}
	}

	static public function radians(double $degrees) 
	{
		return $degrees * M_PI / 180;
	}

	static public function distance(double $lat1, double $lon1, double $lat2, double $lon2)
	{
		$rad = M_PI / 180;	// radian
		$r = 6372.797;		// mean radius of Earth in km.

		if ($lat1 == 0 || $lat1 == 0 || $lat2 == 0 || $lon2 == 0) {
			return 0;
		}

		$diffLat = self::radians($lat2 - $lat1);
		$diffLon = self::radians($lon2 - $lon1);

		$a = sin($diffLat / 2) * sin($diffLat / 2) + cos($lat1) * cos($lat2) * sin($diffLon / 2) * sin($dlon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return $r * $c; 	// km 
	}

	static public function distanceMiles(double $lat1, double $lon1, double $lat2, double $lon2)
	{
		return 0.6214 * self::distance($lat1, $lon1, $lat2, $lon2);
	}
}
