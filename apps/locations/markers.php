<?php

// MARKERS.PHP 1.0 (2019/01/10)

require "../../core/config.php";
require "../../core/functions.php";
//require "../../core/security.php";

$t = "G"; if (isset($_GET["t"])) { $t = $_GET["t"]; }

openDB();

	$d = select("*", "appmarkers");
	
	if ($t == "G") {
		
		// XML FILE FOR GOOGLE MAPS
		header("Content-type: text/xml");
		
		echo "<?xml version='1.0' encoding='UTF-8'?>\n";
		echo "<markers>\n";
		
			foreach ($d as $r) {			
				echo "    <marker id='" . $r['ItemSignature'] . "' name='" . $r['ItemName'] . "' description='Sig: " . $r['ItemSignature'] . "' lat='" . $r['Latitude'] . "' lng='" . $r['Longitude'] . "' type='" . $r['ItemType'] . "' icon='assets/images/markers/" . $r['ItemType'] . ".png' />\n";
			}
		
		echo "</markers>\n";
		
	} else {

		// JSON FILE FOR MAPBOX
		echo "{\n"; 
		echo " 	\"type\": \"FeatureCollection\",\n"; 
		echo " 	\"features\": [\n\n";

			$markers = '';

			foreach ($d as $r) {

				$markers .= " 		{\n"; 
				$markers .= " 			\"type\": \"Feature\",\n"; 
				$markers .= " 			\"properties\": {\n"; 
				$markers .= "				\"sign\": \"" . $r['ItemSignature'] . "\", \n"; 
				$markers .= " 				\"type\": \"" . $r['ItemType'] . "\", \n"; 
				$markers .= " 				\"name\": \"<h7><strong title='" . $r['ItemSignature'] . "'>" . $r['ItemName'] . "</strong></h7>\",\n"; 
				
				$markers .= " 				\"icon\": {\n";
				$markers .= " 					\"iconUrl\": \"assets/images/markers/camera.png\",\n";
				$markers .= " 					\"iconSize\": [50, 50],\n";
				$markers .= " 					\"iconAnchor\": [25, 25],\n";
				$markers .= " 					\"popupAnchor\": [0, -25],\n";
				$markers .= " 					\"className\": \"dot\"\n";
				$markers .= " 				}\n";				
				
				$markers .= " 			},\n"; 
				$markers .= " 			\"geometry\": {\n"; 
				$markers .= " 				\"type\": \"Point\",\n"; 
				$markers .= " 				\"coordinates\": [" . $r['Longitude'] . ", " . $r['Latitude'] . "]\n"; 
				$markers .= " 			}\n"; 
				$markers .= " 		},\n\n";

			}

		echo substr($markers, 0, strlen($markers)-3) . "\n\n";

		echo "	]\n"; 
		echo "}\n";

	}

closeDB();