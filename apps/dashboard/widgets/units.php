<?php

// UNITS.PHP 1.1 (2019/04/24)

require "../../../core/config.php";
require "../../../core/functions.php";
require "../../../core/security.php";

// DATABASE OPEN
openDB();

	echo "<!DOCTYPE html>\n"; 
	echo "<html lang='en'>\n"; 
	 
	echo "	<head>\n"; 

	echo "		<title>" . translate("ProposalsByUnits") . "</title>\n";
	echo "		<meta charset='utf-8' />\n"; 
	echo "		<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n"; 
	echo "		<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />\n"; 

	// CSS
	echo addCode("../../../core/vendor/limitness/css/components.min.css"); 
	echo addCode("../assets/css/units.css");

	// JS
	echo addCode("../../../core/vendor/jquery/jquery.min.js"); 
	echo addCode("../../../core/plugins/visualization/echarts/echarts.min.js");

	// CHART CODE
	echo "		<script>\n"; 
	 
	echo "			var EchartsPiesDonuts = function() {\n"; 
	 
	echo "				var _piesDonutsExamples = function() {\n"; 
	
	echo "					if (typeof echarts == 'undefined') {\n"; 
	echo "						console.warn('Warning - echarts.min.js is not loaded.');\n"; 
	echo "						return;\n"; 
	echo "					}\n"; 
	 
	echo "					var pie_donut_element = document.getElementById('pie_donut');\n"; 
	 
	echo "					if (pie_donut_element) {\n"; 
	 
	echo "						var pie_donut = echarts.init(pie_donut_element);\n"; 
	 
	echo "						pie_donut.setOption({\n"; 
	 
	echo "							color: [\n"; 
	echo "								'#008FFB', '#F87C25', '#69AF23', '#FEB018', '#d87a80',\n"; 
	echo "								'#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',\n"; 
	echo "								'#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',\n"; 
	echo "								'#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'\n"; 
	echo "							],\n"; 
	 
	echo "							textStyle: {\n"; 
	echo "								fontFamily: 'Roboto, Arial, Verdana, sans-serif',\n"; 
	echo "								fontSize: 12,\n"; 
	echo "								color: '#888'\n"; 
	echo "							},\n"; 
	 
	echo "							tooltip: {\n"; 
	echo "								trigger: 'item',\n"; 
	echo "								backgroundColor: 'rgba(0,0,0,0.75)',\n"; 
	echo "								padding: [10, 15],\n"; 
	echo "								textStyle: {\n"; 
	echo "									fontSize: 12,\n"; 
	echo "									fontFamily: 'Roboto, sans-serif'\n"; 
	echo "								},\n"; 
	echo "								formatter: '{b} ({d}%)'\n"; 
	echo "							},\n"; 
	 
	echo "							legend: {\n"; 
	echo "								orient: 'vertical',\n"; 
	echo "								top: 'bottom',\n"; 
	echo "								left: 16,\n"; 
	
	// LEGEND
	if (isset($_GET["d"])) {

		$t = explode("-", $_GET["d"]);	
		echo "								data: [";
		$u = '';
		if ($t[0] != '') { $u .= "'" . translate("NotPresented") . ": " . $t[0] . "', "; }
		if ($t[1] != '') { $u .= "'" . translate("Waiting") . ": " . $t[1] . "', "; }
		if ($t[2] != '') { $u .= "'" . translate("Acepted") . ": " . $t[2] . "', "; }
		if ($t[3] != '') { $u .= "'" . translate("Recused") . ": " . $t[3] . "', "; }
		if ($u != '') { echo left($u, strlen($u) - 2); }
		echo "],\n";
	}	
	 
	echo "								itemHeight: 8,\n"; 
	echo "								itemWidth: 8,\n"; 
	echo "								textStyle: {\n"; 
	echo "									fontSize: 12,\n"; 
	echo "									fontWeight: 400,\n"; 
	echo "									color: '#B6B6B6'\n"; 
	echo "								}\n"; 
	echo "							},\n"; 
	 
	echo "							series: [{\n";
	echo "								name: 'Propostas',\n";	
	echo "								label: { show: false },\n"; 
	echo "								type: 'pie',\n"; 
	echo "								radius: ['50%', '80%'],\n"; 
	echo "								center: ['70%', '54%'],\n";
	echo "								itemStyle: {\n"; 
	echo "									normal: {\n"; 
	echo "										borderWidth: 2,\n"; 
	echo "										borderColor: '#2D2D2D'\n"; 
	echo "									}\n"; 
	echo "								},\n"; 
	
	// DATA 
	if (isset($_GET["d"])) {

		$t = explode("-", $_GET["d"]);	
		echo "								data: [";
		$u = '';		
		if ($t[0] != '') { $u .= "{ value: " . $t[0] . ", name: '" . translate("NotPresented") . ": " . $t[0] . "' }, "; }
		if ($t[1] != '') { $u .= "{ value: " . $t[1] . ", name: '" . translate("Waiting") . ": " . $t[1] . "' }, "; }
		if ($t[2] != '') { $u .= "{ value: " . $t[2] . ", name: '" . translate("Acepted") . ": " . $t[2] . "' }, "; }
		if ($t[3] != '') { $u .= "{ value: " . $t[3] . ", name: '" . translate("Recused") . ": " . $t[3] . "' }, "; }
		if ($u != '') { echo left($u, strlen($u) - 2); }
		echo "]\n";
	
	}
	
	echo "							}]\n"; 
	echo "						});\n"; 
	echo "					}\n"; 
	 
	echo "					var triggerChartResize = function() {\n"; 
	echo "						pie_donut_element && pie_donut.resize();\n"; 
	echo "					};\n"; 
	 
	echo "					var resizeCharts;\n"; 
	echo "					window.onresize = function() {\n"; 
	echo "						clearTimeout(resizeCharts);\n"; 
	echo "						resizeCharts = setTimeout(function() {\n"; 
	echo "							triggerChartResize();\n"; 
	echo "						}, 200);\n"; 
	echo "					};\n"; 
	echo "				};\n"; 
	 
	echo "				return {\n"; 
	echo "					init: function() {\n"; 
	echo "						_piesDonutsExamples();\n"; 
	echo "					}\n"; 
	echo "				}\n";
	
	echo "			}();\n"; 
	 
	echo "			document.addEventListener('DOMContentLoaded', function() {\n"; 
	echo "				EchartsPiesDonuts.init();\n"; 
	echo "			});\n"; 
	 
	echo "		</script>\n"; 
	 
	echo "	</head>\n"; 
	 
	echo "	<body>\n"; 
	 
	echo "		<div id='pie_donut'></div>\n"; 
	
	$s = translate("Brazil"); if (isset($_GET["s"])) { $s = strtoupper($_GET["s"]); }
	 
	echo "		<div class='pie-donut-title'><h4>" . translate("ProposalsStatus") . " - " . $s . "</h4><small>" . translate("UnitsCount") . "</small></div>\n"; 
	 
	echo "	</body>\n"; 
	 
	echo "</html>\n";
	
// DATABASE CLOSE
closeDB();