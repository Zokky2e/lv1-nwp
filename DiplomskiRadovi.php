<?php
require_once 'iRadovi.php';
require_once "db_connect.php";
class DiplomskiRadovi implements iRadovi {
	public $rad_id;
    public $naziv_rada = "";
    public $tekst_rada = "";
    public $link_rada = "";
    public $oib_tvrtke = "";

	public function __construct() {
		$this->rad_id = uniqid();
	}

    public function create() {
        self::__construct();
    }

    public function save() {
		global $conn;
		$sql = "INSERT INTO diplomski_radovi 
		(rad_id, naziv_rada, oib_tvrtke, link_rada, tekst_rada) 
		VALUES (?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);

		// Bind parameters
		$stmt->bind_param("sssss", $this->rad_id, $this->naziv_rada, $this->oib_tvrtke, $this->link_rada, $this->tekst_rada);
	
		// Execute the statement
		if ($stmt->execute()) {
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	
		// Close the statement
		$stmt->close();
    }
	public function read() {
		$diplomskiRadovi = array();
		global $conn;
		$sql = "select * from diplomski_radovi";
		$result = $conn->query($sql);
		if ($result) {
			while($row = $result->fetch_assoc()) {
				$newRad = new DiplomskiRadovi();
				$newRad->naziv_rada  = $row["naziv_rada"];
				$newRad->oib_tvrtke  = $row["oib_tvrtke"];
				$newRad->tekst_rada = $row["tekst_rada"];
				$newRad->link_rada  = $row["link_rada"];
				$diplomskiRadovi[] = $newRad;
			}
			return $diplomskiRadovi;
		} else {
			echo "Error executing query: " . $conn->error;
		}
    }

	public function readRadInfo() {
		echo $this->naziv_rada . "<br>";
		echo $this->oib_tvrtke . "<br>";
		echo $this->tekst_rada . "<br>";
		echo $this->link_rada . "<br>";
	}
}
?>
