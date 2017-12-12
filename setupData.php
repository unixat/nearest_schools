<?php

namespace NearestSchools;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

// require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

// TODO - rm old schools data file
$schools = new SchoolOpenData('schools.dat', PostcodeCoordsDataFile::Coords());
