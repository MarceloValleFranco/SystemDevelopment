<?php

// LABELS.PHP 1.1 (2019/05/06)

require "../../../core/config.php";
require "../../../core/functions.php";
require "../../../core/security.php";

$state = ''; $uf = 'Brasil';
if (isset($_GET['s'])) { if ($_GET['s'] != "Brasil") { $state = "/" . strtoupper($_GET['s']); $uf = strtoupper($_GET['s']); } }

    // GET DATA
    $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://engiedigital.com.br/api/bank-capacitor/metrics" . $state,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic RGlnaXRhbDpFODg0V3MyYmFq",
                "Postman-Token: 83b46791-cea9-4961-a63e-62fcae4de47d",
                "cache-control: no-cache"
            ),
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $vals = curl_exec($curl); $err = curl_error($curl);

    curl_close($curl);
    
    // DEBUG
    //print_r($vals); //exit;
    
    // WORK WITH DATA
	$c = json_decode($vals); $c = object2array($c);
    
echo "<!DOCTYPE html>\n"; 
echo "<html lang='en'>\n"; 
 
echo "	<head>\n"; 
 
echo "		<meta charset='UTF-8'>\n"; 
echo "		<meta http-equiv='X-UA-Compatible' content='IE=edge'>\n"; 
echo "		<meta name='viewport' content='width=device-width, initial-scale=1'>\n"; 
echo "		<title>Labels</title>\n"; 

// CSS
echo addCode("../../../core/vendor/bootstrap/bootstrap.min.css"); 
echo addCode("../../../core/vendor/limitness/css/components.min.css"); 
echo addCode("../assets/css/labels.css"); 
 
echo "	</head>\n"; 
 
echo "	<body>\n"; 
 
echo "		<div id='wrapper'>\n"; 
 
echo "			<div class='content-area' >\n"; 
 
echo "					<table style='width:100%'>\n"; 
 
echo "						<tr>\n"; 
 
echo "							<td>\n"; 
echo "								<div class='box1'>\n"; 

$units = $c['number_units'];
if ($units == '') { $units = "API Failure!"; }

echo "									<div><h3>Total Clientes - " . $uf . "</h3><br /><h2>" . $units . "</h2></div>\n"; 
echo "								</div>\n"; 
echo "							</td>\n";
 
echo "							<td style='width:20px'>&nbsp;</td>\n"; 

echo "							<td>\n"; 
echo "								<div class='box2'>\n"; 

$capex = (intval($c['capex']/1000000000)) + 0;
if ($capex > 0) { $capex = "R$ " . $capex . "M"; }
if ($units == '') { $capex = "API Failure!"; }

echo "									<div><h3>Potencial de Venda - " . $uf . "</h3><br /><h2>" . $capex . "</h2></div>\n"; 
echo "								</div>\n"; 
echo "							</td>\n"; 
 
echo "						</tr>\n"; 
 
echo "					</table>\n"; 
 
echo "			</div>\n"; 
 
echo "		</div>\n"; 
 
echo "	</body>\n"; 
 
echo "</html>\n";