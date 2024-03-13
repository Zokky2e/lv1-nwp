<?php 

require './htmlScrapper.php';
include 'db_connect.php';

$checkIfExistsQuery = "SELECT COUNT(*) as total FROM diplomski_radovi";
$result = $conn->query($checkIfExistsQuery);
if ($result && $result->num_rows > 0) {
	$row = $result->fetch_assoc();
    $totalRows = $row['total'];

	if ($totalRows <= 0) { 
		$diplomski_radovi = array();
		$temp = array();
		
		for($x = 2; $x <=6; $x++) {
			$temp = array_merge($diplomski_radovi, getRadoviOnPage($x));
			$diplomski_radovi = $temp;
		}

		foreach($diplomski_radovi as $dipl_rad){
			$dipl_rad->save();
		}
		echo "Diplomski radovi inserted into diplomski_radovi table!";
	} else {
		echo "Table already contains Diplomski Radovi!";
	}
} else {
	echo "Error executing query: " . $conn->error;
}

?>