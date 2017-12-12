<?php
//
// SchoolOpenData
// Downloads Open data and creates school data file used by application.
//


use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

namespace NearestSchools;

class SchoolOpenData
{
	protected $schools = [];
	protected $tempFile;
	protected $dataPath;
	protected $coords;

	function __construct(string $schoolDataPath, array $coords=null)
	{
		$this->coords = $coords;
		$this->dataPath = $schoolDataPath;
		// if file exists and is not a directory then do not load from API - use local copy instead
		if (!file_exists($schoolDataPath) && !is_dir($schoolDataPath) && $this->load()) {
			$this->importAndSaveData($schoolDataPath);
		}
	}

	public function loadData()
	{
		$fp = fopen($this->dataPath, 'r');
		if ($fp) {
			while (($school = fgetcsv($fp, 512, ",")) !== FALSE) {
				$this->schools[$school[School::POSTCODE]] = $school;
			}
		}
		fclose($fp);
	}

	// remove temporary data file
	public function __destruct() { if (file_exists($this->tempFile)) unlink($this->tempFile); }

	// return schools data
	public function schools() { return $this->schools; }

	// for testing only
	public function displayData()
	{
		foreach ($this->schools as $s) {
			echo $s->name. "\t" . 
				$s->address->street . "\t" . 
				$s->address->town . "\t" . 
				$s->address->postcode . PHP_EOL;
		}
	}

	public function importAndSaveData($outputFile)
	{
		$sfp = fopen($outputFile, "w");
		if ($sfp) {
			$reader = ReaderFactory::create(Type::ODS);
			$reader->open($this->tempFile);

			foreach ($reader->getSheetIterator() as $sheet) {
				if ('open' == strtolower($sheet->getName())) {
					foreach ($sheet->getRowIterator() as $row) {
						if (is_numeric($row[0])) {
							$school = new School();
							$school->name = $row[4];
							$school->authority = $row[2];
							$school->type = $row[11];
							$school->age_lo = $row[13];
							$school->age_hi = $row[12];
							$school->street = $row[5];
							$school->town = $row[8];
							$school->postcode = str_replace(' ','',$row[10]);

							// retrieve co-ordinates from postcode data
							if ($this->coords) {
								if (array_key_exists($school->postcode, $this->coords)) {
									$latlong = explode(':', $this->coords[$school->postcode]);
									$school->lat = $latlong[0];
									$school->lon = $latlong[1];
								}
								else {
									echo "Cannot find coords for " . $school->postcode . PHP_EOL;
								}
							}

							// TODO - extend basic school info with additional data
							//$school->locality = $row[6];
							//$school->sixth_form = $row[15];
							//$school->gender = $row[18];
							//$school->status = $row[22];

							$this->schools[$school->postcode] = $school;
							//$serializedSchool = serialize($school);
							// fwrite($sfp, $serializedSchool);
							$school->writeCsvFormat($sfp);
						}
					}
				}
			}
			$reader->close();
			fclose($sfp);
		}
	}

	// returns FALSE if failed to get data
	function load()
	{
		static $openDataUrl = 'https://www.gov.uk/government/uploads/system/uploads/attachment_data/file/629350/EduBase_Schools_July_2017.ods';
		$this->tempFile = tempnam('/tmp', 'osd_');
		return file_put_contents($this->tempFile, fopen($openDataUrl, 'r'));
	}
}
