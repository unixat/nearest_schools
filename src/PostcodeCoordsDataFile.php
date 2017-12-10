<?php
//
// PostcodeCoordsDataFile
// Gets entire setup of UK postcode, lat, long data from Open source and stores in static data file.
// Coords() method returns lat-long data in array keyed by postcode.
//

class PostcodeCoordsDataFile
{
	protected static $openDataUrl = 'https://www.freemaptools.com/download/full-postcodes/ukpostcodes.zip';
	protected $localData;
	protected $localZip;
	protected $localDataDir;

	//------------------------------
	// the path of the data file.
	//------------------------------
	public function localDataFile() 	{ return $this->localData; }

	public function __construct($dataFileDir = null)
	{
		// get the file name from the URL
		$this->localZip = basename(self::$openDataUrl);

		// optionally set the dest dir for the data file
		if ($dataFileDir) {
	
			// check if param passed it is actually a directory
			if (!is_dir($dataFileDir)) {
				throw new Exception($dataFileDir . " is not a directory");
			}

			$this->localDataDir = $dataFileDir;
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
	}

	public static function Coords($destDataDir = null)
	{
		$pcd = new PostcodeCoordsDataFile($destDataDir);
		return $pcd->data();
	}

	//------------------------------------------------------------------
	// data()
	// if data file not found download the data from open data source.
	// import data from data file to an array and return the array.
	//-=----------------------------------------------------------------
	public function data()
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
				  $coords[$line[0]] = $line[1] . ':' . $line[2];
			}
			else {
				error_log('Missing coords for ' . $postcode);
			}
		}
		fclose($ifp);
		return $coords;
	}

	//-----------------------------------------------------------
	// download()
	// Called if data file not found by data().
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
	protected function filterDataFile()
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
					$lat = number_format((float)$field[2],4,'.','');
					$lon = number_format((float)$field[3],4,'.','');
					fwrite($fpo, str_replace(' ','',$field[1]) . ',' . $lat . ',' . $lon . PHP_EOL);
				}
				$firstLine = false;
			}
			fclose($fpi);
			fclose($fpo);
			unlink($newFile);
		}
	}

}
