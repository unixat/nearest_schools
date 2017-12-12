<?php

if ('cli' != php_sapi_name()) {
?>
	<div class="school">
		<h2><?php echo $school[NearestSchools\School::NAME]; ?></h2>
		<p><?php echo $school[NearestSchools\School::STREET]; ?></p>
		<p><?php echo $school[NearestSchools\School::TOWN]; ?></p>
		<p><?php echo $school[NearestSchools\School::POSTCODE]; ?></p>
		<p><?php echo $school[NearestSchools\School::TYPE]; ?></p>
		<p><?php echo 'Age Range: ' . $school[NearestSchools\School::AGE_LO] . ' to ' . $school[NearestSchools\School::AGE_HI]; ?></p>
		<p><?php echo 'Distance: ' . $school[NearestSchools\School::DIST] . ' miles'; ?></p>
	</div>
<?php
}
else {
	// test CLI mode
	print_r($school);
}
