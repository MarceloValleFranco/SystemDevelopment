<?php

// EXCELQUERY.PHP 1.0 (2019/05/10)

	// SQL QUERY DEFAULT
	//$d = select("*", $appTable); 

    // ----------- MOBILITY -----------
    
    if ($appTable == "appservices") {
        
        $iniDate = date("Y-m-d"); if (isset($_GET['i'])) { $iniDate = $_GET['i']; }
        
        $endDate = date("Y-m-d"); if (isset($_GET['e'])) { $endDate = $_GET['e']; }
        
        $d = select("*", $appTable, "where StartTime >= '" . $iniDate . " 00:00:00' and EndTime <= '" . $endDate . " 23:59:59'");        
        
    } else { 

        $d = select("*", $appTable);
        
    }
    
    // ----------- /MOBILITY -----------						