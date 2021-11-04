<?php

// SECTORS.PHP 1.1 (2019/04/24)

require "../../../core/config.php";
require "../../../core/functions.php";
require "../../../core/security.php";

echo "<!DOCTYPE html>\n"; 
echo "<html lang='en'>\n"; 

echo "	<head>\n"; 
 
echo "      <meta charset='utf-8' />\n"; 
echo "      <meta http-equiv='X-UA-Compatible' content='IE=edge' />\n"; 
echo "      <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />\n"; 
echo "      <title>Sectors</title>\n"; 

// CSS
echo addCode("../../../core/vendor/limitness/css/components.min.css"); 
echo addCode("../assets/css/sectors.css"); 

// CHART CODE 
echo "		<script>\n"; 

	echo "			window.onload = function () {\n"; 

	echo "			var chart = new CanvasJS.Chart('columns_basic', {\n";
	echo "				toolTip: { borderThickness: 0, cornerRadius: 4, fontSize: 12, fontFamily: 'Arial, Helvetica, Monospaced, sans-serif' },\n"; 
	echo "				animationEnabled: true,\n"; 
	echo "				theme: 'dark2',\n"; 
	echo "				axisY: { labelFontSize: 12, labelFontFamily: 'Arial, Helvetica, Monospaced, sans-serif', labelFontColor: '#AAAAAA', gridThickness: 1, gridColor: '#444444' },\n";
	echo "				axisX: { labelFontSize: 12, labelFontFamily: 'Arial, Helvetica, Monospaced, sans-serif', labelFontColor: '#AAAAAA', lineColor: '#2D2D2D', lineThickness: 10 },\n";		
	echo "				data: [{\n";         
	echo "					type: 'bar',\n";    
	echo "					legendMarkerColor: 'red',\n"; 
	echo "					dataPoints: [\n"; 

	// DATA 
	if (isset($_GET["r"])) {
		
		$t = explode("-", $_GET["r"]);
		
		$sudeste = $t[0];
		$sul = $t[1];
		$centro = $t[2];
		$nordeste = $t[3];
		$norte = $t[4];
		
	}
	 
	echo "						{ y: 100, label: 'Agronegócio' },\n"; 
	echo "						{ y: 200,  label: 'Comércio' },\n"; 
	echo "						{ y: 300,  label: 'Datacenter' },\n"; 
	echo "						{ y: 200,  label: 'Educacional' },\n"; 
	echo "						{ y: 400,  label: 'Saúde' },\n"; 
	echo "						{ y: 500,  label: 'Outros' }\n"; 
	
	echo "					]\n"; 
	echo "				}]\n"; 
	echo "			});\n"; 
	echo "			chart.render();\n"; 

	echo "			}\n"; 
 
echo "		</script>\n"; 
 
echo "	</head>\n"; 
 
echo "	<body>\n"; 
 
echo "		<div id='columns_basic'></div>\n";
	
echo addCode("../../../core/plugins/visualization/canvasjs/canvasjs.min.js"); 
 
echo "		<div class='bars_basic-title'><h4>Propostas Não Apresentadas - Brasil</h4><small>Por Setor</small></div>\n"; 

echo "		<div class='bars_basic-footer'><small>CAPEX em R$</small></div>\n";

echo "		<div class='columns_basic-footer'></div>\n"; 

echo "	</body>\n"; 
 
echo "</html>\n";