<?php

if ('cli' != php_sapi_name()) {
?>
	<div class="school">
		<h2><?php echo $school[School::NAME]; ?></h2>
		<p><?php echo $school[School::STREET]; ?></p>
		<p><?php echo $school[School::TOWN]; ?></p>
		<p><?php echo $school[School::POSTCODE]; ?></p>
		<p><?php echo $school[School::TYPE]; ?></p>
		<p><?php echo 'Age Range: ' . $school[School::AGE_LO] . ' to ' . $school[School::AGE_HI]; ?></p>
		<p><?php echo 'Distance: ' . $school[School::DIST] . ' miles'; ?></p>
	</div>
<?php
}
else {
	// test CLI mode
	print_r($school);
}
