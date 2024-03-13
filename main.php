<?php

require './DiplomskiRadovi.php';
$index = 0;
$dipl_rad = new DiplomskiRadovi();
$diplomski_radovi = $dipl_rad->read();

if($diplomski_radovi != null && count($diplomski_radovi) > 0) {
	foreach($diplomski_radovi as $rad){
		$index++;
		echo $index . ". rad<br>";
		echo $rad->readRadInfo();
	}
} else {
	echo "No diplomski radovi in database. Go to /first_run.php to fetch and save diplomski radovi.";
}

?>