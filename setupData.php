<?php

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

$postcodes = new PostcodeCoords();
$schools = new SchoolOpenData('schools.dat', $postcodes->coords());
