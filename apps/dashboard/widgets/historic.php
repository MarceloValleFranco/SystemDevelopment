<?php

// HISTORIC.PHP 1.1 (2019/04/24)

require "../../../core/config.php";
require "../../../core/functions.php";
//require "../../../core/security.php";

// DATABASE OPEN
openDB();

	echo "<!DOCTYPE html>\n"; 
	echo "<html lang='en'>\n"; 
	 
	echo "	<head>\n"; 

	echo "		<title>" . translate("Historic") . "</title>\n"; 
	echo "		<meta charset='UTF-8'>\n"; 
	echo "		<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n"; 
	echo "		<meta name='viewport' content='width=device-width, initial-scale=1' />\n"; 
	 
	// CSS
	echo addCode("../../../core/vendor/limitness/css/components.min.css"); 
	echo addCode("../assets/css/historic.css");
	 
	echo "	</head>\n"; 
	 
	echo "	<body>\n"; 	
		
		echo "		<div id='line-adwords'></div>\n";

		$s = ''; if (isset($_GET['t'])) { $s = strtoupper($_GET['t']); if ($s == "BRASIL") { $s = "Brasil"; } } 
		 
		echo "		<div class='line-adwords-title'><h4>" . translate("Historic") . " - " . $s . "</h4><small>" . translate("Fines/Savings") . "</small></div>\n"; 

		// JS 
		echo addCode("../../../core/plugins/visualization/apexcharts/jquery.slim.min.js");
		echo addCode("../../../core/plugins/visualization/apexcharts/apexcharts.min.js");

		// CHART CODE
		echo "		<script>\n"; 
		 
		echo "			window.Apex = {\n"; 
		 
		echo "				chart: {\n"; 
		echo "					foreColor: '#B6B6B6',\n"; 
		echo "					toolbar: {\n"; 
		echo "						show: false\n"; 
		echo "					},\n"; 
		echo "				},\n"; 
		 
		echo "				stroke: {\n"; 
		echo "					width: 3\n"; 
		echo "				},\n"; 
		 
		echo "				dataLabels: {\n"; 
		echo "					enabled: false\n"; 
		echo "				},\n"; 
		 
?>

     tooltip: {
		theme: 'dark',
        shared: true,
        intersect: false,
        y: [{
          formatter: function (y) {
            if(typeof y !== "undefined") {
              return 'R$ ' + number_format(y, 0, ',', '.');
            }
            return y;
            
          }
        }, {
          formatter: function (y) {
            if(typeof y !== "undefined") {
              return 'R$ ' + number_format(y, 0, ',', '.');
            }
            return y;
            
          }
        }]
      },	

<?php	  
		 
		echo "				grid: {\n"; 
		echo "					borderColor: \"#535A6C\",\n"; 
		echo "					xaxis: {\n"; 
		echo "						lines: {\n"; 
		echo "							show: true\n"; 
		echo "						}\n"; 
		echo "					}\n"; 
		echo "				}\n"; 
		 
		echo "			};\n"; 
		 
		echo "			var optionsLine = {\n"; 
		 
		echo "				chart: {\n"; 
		echo "					height: 240,\n"; 
		echo "					type: 'line',\n"; 
		echo "					zoom: {\n"; 
		echo "						enabled: true\n"; 
		echo "					},\n"; 
		 
		echo "					dropShadow: {\n"; 
		echo "						enabled: false,\n"; 
		echo "						top: 3,\n"; 
		echo "						left: 2,\n"; 
		echo "						blur: 4,\n"; 
		echo "						opacity: 1,\n"; 
		echo "					}\n"; 
		echo "				},\n"; 
		 
		echo "				stroke: {\n"; 
		echo "					curve: 'smooth',\n"; 
		echo "					width: 2\n"; 
		echo "				},\n"; 
		 
		echo "				colors: [\"#FEB018\", '#2196F3'],\n"; 
		 
		echo "				series: [{\n"; 
		echo "						name: '" . translate("Fine") . "',\n"; 
		echo "						data: [" . $_GET['f'] . "]\n"; 
		echo "					},\n"; 
		echo "					{\n"; 
		echo "						name: '" . translate("Saving") . "',\n"; 
		echo "						data: [" . $_GET['s'] . "]\n"; 
		echo "					}\n"; 
		echo "				],\n"; 
		 
		echo "				markers: {\n"; 
		echo "					size: 6,\n"; 
		echo "					strokeWidth: 0,\n"; 
		echo "					hover: {\n"; 
		echo "						size: 9\n"; 
		echo "					}\n"; 
		echo "				},\n"; 
		 
		echo "				grid: {\n"; 
		echo "					show: false\n"; 
		echo "				},\n"; 
		 
		echo "				labels: [" . $_GET['d'] . "],\n"; 
		 
		echo "				xaxis: {\n"; 
		echo "					tooltip: {\n"; 
		echo "						enabled: false\n"; 
		echo "					}\n"; 
		echo "				},\n"; 
		 
		echo "				legend: {\n"; 
		echo "					position: 'top',\n"; 
		echo "					horizontalAlign: 'right',\n"; 
		echo "					offsetY: 0\n"; 
		echo "				}\n"; 
		echo "			}\n"; 
		 
		echo "			var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);\n"; 
		echo "			chartLine.render();	\n";

?>

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

<?php		
		 
		echo "		</script>\n"; 		
		
		// DEBUG
		// echo "<style>td { border: solid 1px #ccc; padding: 4px; font-size: 12px }</style>\n";
		// echo "<table class='table'>";
		// echo "<tr><td> DATE </td><td> FINE </td><td> SAVING </td></tr>"; $t = '';
		// foreach($y as $x) {
			// $t = "<tr><td> " . $x['date'] . " </td><td> " . $x['fine'] . " </td><td> " . $x['saving'] . " </td></tr>" . $t;
		// }
		// echo $t . "</table>";	
	 
	echo "	</body>\n"; 
	 
	echo "</html>\n";
	
// DATABASE CLOSE
closeDB();