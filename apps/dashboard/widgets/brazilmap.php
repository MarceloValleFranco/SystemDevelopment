<?php

// BRAZILMAP.PHP 1.1 (2019/04/24)

require "../../../core/config.php";
require "../../../core/functions.php";
//require "../../../core/security.php";

if (isset($_GET['x'])) {

    $x = array();
    $x = unserialize($_GET['x']);
    
    // DEBUG
    //print_r($x); exit;

    $min = $x[0]['capex'];
    $min = "R$ " . number_format($min/100, 2, ',', '.');

    $max = $x[26]['capex'];
    $max = "R$ " . number_format($max/100, 2, ',', '.');

    // DATABASE OPEN
    openDB();

        // PAGE HEADER
        echo "<!DOCTYPE html>\n"; 
        echo "<html>\n"; 
     
        echo "	<head>\n"; 
        
            echo "		<title>" . translate("BrazilMap") . "</title>\n"; 
            echo "		<meta charset='utf-8' />\n"; 
            echo "		<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1' />\n";
            
            echo addCode("../../../core/vendor/limitness/css/components.min.css");
            echo addCode("../assets/css/brazilmap.css");
            echo addCode("../../../core/vendor/jquery/jquery.min.js");
            echo addCode("../../../core/plugins/maps/jvectormap/jvectormap.min.js");
            echo addCode("../assets/js/brazilmap.js");	
        
        echo "	</head>\n";
        
        echo "	<body>\n";
            
            echo "		<div class='page-content'>\n";
            
            echo "			<div class='content-wrapper'>\n";
            
            // MAIN CONTENT
            echo "				<div class='content'>\n";		

            // GET STATE
            $s = translate("Brazil"); if (isset($_GET['s'])) { $s = $_GET['s']; }
            
            echo "					<div class='brazil-map-title'><h4>" . translate("PotentialSales") . "</h4><small>" . translate("NonPresentedProposals") . "</small></div>\n"; 

            // MAP LAYER
            echo "					<div id='brazil-map'></div>\n"; 
            
            // BACK TO BRAZIL
            if ($s != translate("Brazil")) { echo "					<div class='right-box'><div class='sigla'>" . strtoupper($s) . "</div><div onclick=\"parent.location.href=('../dashboard.php')\" class='back-brazil'>" . translate("backToBr") . "</div></div>\n"; }
            
            // MAP SCRIPT
            echo "					<script>\n";
             
            echo "						$(function() {\n";
             
            echo "							var map_settings = {\n"; 
            echo "								map: 'brazil',\n"; 
            echo "								zoomButtons: false,\n"; 
            echo "								zoomMax: 1,\n"; 
            echo "								regionStyle: {\n"; 
            echo "									initial: {\n"; 
            echo "										'fill-opacity': 0.9,\n"; 
            echo "										'stroke': '#2D2D2D',\n"; 
            echo "										'stroke-width': 200,\n"; 
            echo "										'stroke-opacity': 1\n"; 
            echo "									},\n";
            
            // HIGHLIGHT COLOR ON HOVER
            //echo "								hover: {\n"; 
            //echo "									fill: '#f00'\n"; 
            //echo "								}\n"; 
            
            echo "								},\n"; 
            echo "								backgroundColor: '#2D2D2D',\n"; 
            echo "								series: {\n"; 
            echo "									regions: [{\n"; 
            echo "										values: {"; 
            
            //if ($s == translate("Brazil")) {
                
                // COLOR REGIONS
                
                // NORTE
                // echo "								ac: '#fff9c2',\n"; 
                // echo "								am: '#fff9c2',\n"; 
                // echo "								ap: '#fff9c2',\n"; 
                // echo "								pa: '#fff9c2',\n"; 
                // echo "								ro: '#fff9c2',\n"; 
                // echo "								rr: '#fff9c2',\n"; 
                // echo "								to: '#fff9c2',\n";
                
                // NORDESTE 
                // echo "								al: '#fcdeeb',\n"; 
                // echo "								ba: '#fcdeeb',\n"; 
                // echo "								ce: '#fcdeeb',\n"; 
                // echo "								ma: '#fcdeeb',\n"; 
                // echo "								pb: '#fcdeeb',\n"; 
                // echo "								pe: '#fcdeeb',\n"; 
                // echo "								pi: '#fcdeeb',\n"; 
                // echo "								rn: '#fcdeeb',\n"; 
                // echo "								se: '#fcdeeb',\n"; 
                
                // CENTRO-OESTE 
                // echo "								df: '#feb83d',\n"; 
                // echo "								go: '#feb83d',\n"; 
                // echo "								ms: '#feb83d',\n"; 
                // echo "								mt: '#feb83d',\n"; 
                
                // SUDESTE		
                // echo "								es: '#e8ec9b',\n"; 
                // echo "								mg: '#e8ec9b',\n"; 
                // echo "								rj: '#e8ec9b',\n"; 
                // echo "								sp: '#e8ec9b',\n"; 
                
                // SUL 
                // echo "								pr: '#fef56c',\n"; 
                // echo "								rs: '#fef56c',\n"; 
                // echo "								sc: '#fef56c'\n"; 
                
            //} else {				
                
                // COLOR STATES
                $z = ''; 
                
                for ($i = 0; $i <= 26; $i++) { 

                
                    if ($x[$i]['sigla'] == $s) { 
                        
                        $z .= $x[$i]['sigla'] . ":'#FEB018', ";
                        
                    } else {
                        
                        if ($s == translate("Brazil")) { $z .= $x[$i]['sigla'] . ":'" . $x[$i]['color'] . "', "; } else { $z .= $x[$i]['sigla'] . ":'#666', "; }
                        
                    }
                
                } 
                
                echo left($z, strlen($z)-2);		
            
            //}
            
            echo "},\n"; 
            echo "										attribute: 'fill'\n"; 
            echo "									}]\n"; 
            echo "								},\n"; 
            echo "							    container: $('#brazil-map'),\n"; 
            echo "							    onRegionClick: function(event, code) {\n";
            echo "								    top.block('body'); parent.location.href=('../dashboard.php?s=' + code);\n";		
            echo "							    },\n"; 
            echo "						    };\n"; 

            echo "						    map = new jvm.WorldMap($.extend(true, {}, map_settings));\n"; 

            echo "					    });\n"; 

            echo "					</script>\n"; 
            
            // LEGEND
            if ($s == translate("Brazil")) { 
                echo "					<div class='legend'>\n";
                echo "						<table><tr><td class='td-image' rowspan='2'><img src='../assets/images/nuance.png' alt='" . translate("Legend") . "' /></td><td class='td-max'>" . translate("MajorPotential") . "<br />" . $max . "</td></tr><tr><td class='td-min'>" . translate("MinorPotential") . "<br />" . $min . "</td></tr></table>\n";
                echo "					</div>\n";
            }

            echo "				</div>\n"; // /content
            
            echo "			</div>\n"; // /page-wrapper
            
            echo "		</div>\n"; // /page-content		
            
        echo "	</body>\n";	
            
        // SAVE EVENT LOG
        logWrite("View", "BrazilMap");	

        // PAGE FOOTER
        echo "</html>\n";

    // DATABASE CLOSE
    closeDB();

}