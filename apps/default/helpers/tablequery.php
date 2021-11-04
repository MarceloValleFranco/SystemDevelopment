<?php

// TABLEQUERY.PHP 1.0 (2019/05/08)

	// SQL QUERY DEFAULT
	//$d = select("*", $appTable); 

    // ----------- MOBILITY -----------
    
    if ($appTable == "appservices") {
        
        $iniDate = date("y-m-d"); if (isset($_GET['IniDate'])) { $iniDate = $_GET['IniDate']; }
        
        $endDate = date("y-m-d"); if (isset($_GET['EndDate'])) { $endDate = $_GET['EndDate']; }
        
        $d = select("*", $appTable, "where StartTime >= '" . $iniDate . " 00:00:00' and EndTime <= '" . $endDate . " 23:59:59'");        
        
    } else { 

        $d = select("*", $appTable);
        
    }
    
    // ----------- /MOBILITY -----------						