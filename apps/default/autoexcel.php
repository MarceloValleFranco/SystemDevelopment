<?php

// AUTOEXCEL.PHP 1.0 (2019/05/09)

require "../../core/config.php";
require "../../core/functions.php";
//require "../../core/security.php";

// GET REQUESTS
$appTable = ''; if (isset($_GET['t'])) { $appTable = $_GET['t']; }
$query = ''; if (isset($_GET['q'])) { $query = $_GET['q']; }
$apost = ''; if (isset($_GET['a'])) { $apost = "'"; }

// DATABASE OPEN
openDB();

	$title = translate("Spreadsheet"); if (isset($_GET['n'])) { $title = $_GET['n']; }

	$fileName = $title . "_" . date("Y-m-d") . ".csv";

	header('Content-disposition: attachment; filename=' . $fileName);
	header('Content-type: text/plain; charset=UTF-8');

	if ($appTable != '') {
	
		// GET TABLE COLUMNS
		$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')");
        
		// DEBUG
        //print_r($columns); exit;        

		// GET DATA
		if ($query == '') {
			   
            // TABLE QUERY
            $include = "helpers/excelquery-template.php";
            if (file_exists("helpers/excelquery.php")) { $include = "helpers/excelquery.php"; }
            require $include;
		
		} else {
			
			$d = query($query);
			
		}
		
		if ($d) {	
		
			// LIST HEAD
			$include = "helpers/excelhead-template.php";
			if (file_exists("helpers/excelhead.php")) { $include = "helpers/excelhead.php"; }
			require $include;
			
			// LIST BODY
			$include = "helpers/excelbody-template.php";
			if (file_exists("helpers/excelbody.php")) { $include = "helpers/excelbody.php"; }
			require $include;

		}
	}
	
// DATABASE CLOSE
closeDB();