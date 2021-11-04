<?php

// MAKEFORM.PHP 1.0 (2019/01/29)

function makeForm ($appID, $itemID, $cloneID, $required, $message, $origin) {
	
	// APP DATA
	$r = select("*", $origin, "where ItemID=" . $appID);
	if ($r) { 
			
		$appName = $r[0]["ItemName"];
		$appDescription = $r[0]["ItemDescription"];
		$appTable = $r[0]["ItemTable"];

	}	
	
	// GET APP TABLE COLUMNS
	$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE  (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')");
	
	// DEBUG
	//print_r($columns); exit;
    
	$column = array();
	
    // NEW BLANK FORM
	for ($f = 1; $f <= count($columns)-1; $f++) {
		
		switch ($columns[$f]['COLUMN_NAME']) {
			
			// CREATE SIGNATURE
			case "ItemSignature":
				$column[$f] = strtoupper(dechex(date("ymdhis") + 1 + USERID + COMPANYID));
				break;
			
			case "AppActive":
			case "CompanyActive":			
			case "ItemActive":
			case "ShowOnMobile":
			case "ShowOnTablet":
			case "ShowOnDesktop":
				$column[$f] = "1";
				break;					

			case "ItemColumns":
				$column[$f] = "6";
				break;	

			case "ItemIcon":
				$column[$f] = "cog";
				break;					
				
			case "ItemOrder":
				$column[$f] = "99";
				break;		

			case "ItemURL":
				$column[$f] = "apps/default/index.php";
				break;					
				
			case "CompanyID":
				$column[$f] = COMPANYID;
				break;
                            
					
				// MORE CASES GOES HERE...
				
			
			default:
				$column[$f] = '';
				break;
		}
	
	}
    
    if ($itemID != '0' || $cloneID != '0') {
    
        $e = $itemID; if ($cloneID != '') { $e = $cloneID; }
    
		// GET FORM DATA FROM DATABASE
        if ($r = select("*", $appTable, "where " . $columns[0]['COLUMN_NAME'] . "=" . $e)) { 
        		
			for ($f = 1; $f <= count($columns)-1; $f++) {
				$column[$f] = $r[0][$columns[$f]['COLUMN_NAME']];
			}			

        }
        
    }
    
	// GET FORM DATA FROM PAGE REQUESTS
	for ($f = 1; $f <= count($columns)-1; $f++) {
		
		if (isset($_GET[$columns[$f]['COLUMN_NAME']])) { $column[$f] = $_GET[$columns[$f]['COLUMN_NAME']]; }
		
	}
	
	// DEBUG
	//echo "[" . $columns[$f]['COLUMN_NAME'] . "]<br />";	
	
	// FORM
	echo "									<form method='get' class='form-vertical' accept-charset='UTF-8' autocomplete='off'>\n";
	
		// HIDDEN FIELDS 
        echo "										<input type='hidden' name='a' value='s' />\n";
        echo "										<input type='hidden' name='i' value='" . $itemID ."' />\n";
		echo "										<input type='hidden' name='n' value='" . $appID ."' />\n";
		echo "										<input type='hidden' name='v' value='" . $origin ."' />\n";
		
		// FIELDS LOOP
		for ($f = 1; $f <= count($columns)-1; $f++) {
			
			$head = "										<div class='row'>\n";
			$head .= "											<div class='col-md-12 col-lg-12'>\n";
			$head .= "												<div class='form-group'>\n";
			
			$foot = "												</div>\n";
			$foot .= "											</div>\n";
			$foot .= "										</div>\n";			
			
			switch ($columns[$f]['COLUMN_NAME']) {
				
				// AUTO FIELDS
				case "LastExecution":
					break;
				
				// NO EDITABLE DATA
				case "CompanyID":
				case "ItemSignature":				
					echo "										<input type='hidden' name='" . $columns[$f]['COLUMN_NAME'] . "' value='" . $column[$f] . "' />\n";	
					break;
					
				// ACTIVE (ON/OFF)
				case "ChargerActive":
				case "CompanyActive":
				case "CustomerActive":
				case "ItemActive":
				case "SaveOnMarkers":
				case "ServiceActive":
				case "ShowOnMobile":
				case "ShowOnTablet":
				case "ShowOnDesktop":
				case "SocketActive":
				case "StationActive":
				case "ChargerOperatorActive":
				case "RfidActive":
				case "StationOwnerActive":
					echo $head;
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<div class='checkbox-left switchery-sm'>\n";
					echo "														<input type='checkbox' class='switchery switch" . $columns[$f]['COLUMN_NAME'] . "' "; if ($column[$f] == '1') { echo " checked='checked'"; } echo " />\n";
					echo "														<input type='hidden' id='" . $columns[$f]['COLUMN_NAME'] . "' name='" . $columns[$f]['COLUMN_NAME'] . "' value='" . $column[$f] . "' />\n";
					echo "													</div>\n";
					echo $foot;
					echo "										<script>$(function () { $('.switch" . $columns[$f]['COLUMN_NAME'] . "').change(function() { var v = $('.switch" . $columns[$f]['COLUMN_NAME'] . "').prop('checked'); if (v) { v = '1'; } else { v = '0'; } $('#" . $columns[$f]['COLUMN_NAME'] . "').val(v); }).change(); })</script>\n";										
					break;							
				
				// ACTIVE (COMBO)
				case "AppActive":
				//case "SocketStatus":
				case "StationStatus":
					break;
				
				case "SocketGrid":
				    $d = select("*", "appsocketsgrid");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo  "														<option value='" . $r["SocketGridID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketGridID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketGrid"] . " </option>\n";
                                            echo  "                                                                                                             <option value='" . $r["SocketGridID"] . "'"; if ($column[$f] == $r["SocketGridID"]) { echo " selected"; } echo "> " . $r["SocketGrid"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
					
				
				case "SocketPhase":
				    $d = select("*", "appsocketsphase");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketPhaseID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketPhaseID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketPhase"] . " </option>\n";
                                            echo  "                                                                                                             <option value='" . $r["SocketPhaseID"] . "'"; if ($column[$f] == $r["SocketPhaseID"]) { echo " selected"; } echo "> " . $r["SocketPhase"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketAmp":
				    $d = select("*", "appsocketsamp");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketAmpID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketAmpID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketAmp"] . " </option>\n";
                                            echo  "                                                                                                             <option value='" . $r["SocketAmpID"] . "'"; if ($column[$f] == $r["SocketAmpID"]) { echo " selected"; } echo "> " . $r["SocketAmp"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketPower":
				    $d = select("*", "appsocketspower");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketPowerID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketPowerID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketPower"] . " </option>\n";
                                            echo "                                                                                                              <option value='" . $r["SocketPowerID"] . "'"; if ($column[$f] == $r["SocketPowerID"]) { echo " selected"; } echo "> " . $r["SocketPower"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketCableType":
				    $d = select("*", "appsocketscabletype");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketCableTypeID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketCableTypeID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketCableType"] . " </option>\n";
                                            echo "                                                                                                              <option value='" . $r["SocketCableTypeID"] . "'"; if ($column[$f] == $r["SocketCableTypeID"]) { echo " selected"; } echo "> " . $r["SocketCableType"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketCableLenght":
				    $d = select("*", "appsocketscablelenght");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketCableLenghtID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketCableLenghtID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketCableLenght"] . " </option>\n";
                                            echo "                                                                                                              <option value='" . $r["SocketCableLenghtID"] . "'"; if ($column[$f] == $r["SocketCableLenghtID"]) { echo " selected"; } echo "> " . $r["SocketCableLenght"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketType":
				    $d = select("*", "appsocketstype");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            //echo "														<option value='" . $r["SocketTypeID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketTypeID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketType"] . " </option>\n";
                                            echo "                                                                                                              <option value='" . $r["SocketTypeID"] . "'"; if ($column[$f] == $r["SocketTypeID"]) { echo " selected"; } echo "> " . $r["SocketType"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				// APP GROUP ID
				case "AppGroupID":
				    $d = select("*", "appstatus");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["StatusID"] . "'"; if ($column[$f] == $r["StatusID"]) { echo " selected"; } echo "> " . $r["StatusName"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
					
				// MODULE GROUP ID
				case "ModuleGroupID":
					$d = select("*", "sysmodulesgroups", "where (CompanyID=" . COMPANYID . " and ItemActive='1')");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ItemID"] . "'"; if ($column[$f] == $r["ItemID"]) { echo " selected"; } echo "> " . $r["ItemName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;
					
				// ITEM LOCATIONS
					case "ItemLocations":
					$d = select("*", "applocations", "where (CompanyID=" . COMPANYID . " and ItemActive='1')");
					if ($d) {					
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ItemID"] . "'"; if ((strpos($column[$f], "#" . $r["ItemID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["ItemName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;
					
				// LOCATION GROUP ID
				case "LocationGroupID":
					$d = select("*", "applocationsgroups", "where (CompanyID=" . COMPANYID . " and ItemActive='1')");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ItemID"] . "'"; if ($column[$f] == $r["ItemID"]) { echo " selected"; } echo "> " . $r["ItemName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;
					
				// LOCATION TYPE
				case "LocationType":
					echo $head;				
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
					echo "														<option value='GD2'"; if ($column[$f] == 'GD2') { echo " selected"; } echo "> GoogleMaps</option>\n";
					echo "														<option value='GG2'"; if ($column[$f] == 'GG2') { echo " selected"; } echo "> GoogleMaps Styled Grey</option>\n";
					echo "														<option value='GB2'"; if ($column[$f] == 'GB2') { echo " selected"; } echo "> GoogleMaps Styled</option>\n";
					echo "														<option value='GH2'"; if ($column[$f] == 'GH2') { echo " selected"; } echo "> GoogleMaps Hybrid</option>\n";
					echo "														<option value='GS2'"; if ($column[$f] == 'GS2') { echo " selected"; } echo "> GoogleMaps Sattelite</option>\n";
					echo "														<option value='GT2'"; if ($column[$f] == 'GT2') { echo " selected"; } echo "> GoogleMaps Terrain</option>\n";
					echo "														<option value='MD2'"; if ($column[$f] == 'MD2') { echo " selected"; } echo "> MapBox</option>\n";
					echo "														<option value='MD3'"; if ($column[$f] == 'MD3') { echo " selected"; } echo "> MapBox 3D</option>\n";
					echo "														<option value='MG3'"; if ($column[$f] == 'MG3') { echo " selected"; } echo "> MapBox 3D Grey</option>\n";					
					echo "													</select>\n";
					echo $foot;
					break;
					
				// MOBILITY -----------------	
				
				// CHARGER ID
                                    
				case "ChargerID":    
					$d = select("*", "appchargers");
					if ($d) {    
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
                                                    
							echo "														<option value='" . $r["ChargerID"] . "'"; if ($column[$f] == $r["ChargerID"]) { echo " selected"; } echo "> " . "Cód.:".$r["ChargerID"]." | ".$r["ChargerName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
                                        }
					break;
                                         
                                
                                //CHARGER NAME
                                case "ChargerName":    
                                    
					$d = select("*", "appchargerstype");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ChargerName"] . "'"; if ($column[$f] == $r["ChargerName"]) { echo " selected"; } echo "> " . $r["ChargerName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
                                        }
					break;   
				
				// CHARGER STATUS
				case "ChargerStatus":
					echo $head;				
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
					echo "														<option value='1'"; if ($column[$f] == '1') { echo " selected"; } echo "> " . translate("ReadyToUse-Green") . "</option>\n";
					echo "														<option value='2'"; if ($column[$f] == '2') { echo " selected"; } echo "> " . translate("Charging-Blue") . "</option>\n";
					echo "														<option value='3'"; if ($column[$f] == '3') { echo " selected"; } echo "> " . translate("ScheduledCharge-DarkBlue") . "</option>\n";
					echo "														<option value='4'"; if ($column[$f] == '4') { echo " selected"; } echo "> " . translate("EmergencyButton-Yellow") . "</option>\n";
					echo "														<option value='5'"; if ($column[$f] == '5') { echo " selected"; } echo "> " . translate("InternalProblem-Orange") . "</option>\n";
					echo "														<option value='6'"; if ($column[$f] == '6') { echo " selected"; } echo "> " . translate("ChargerOff-Grey") . "</option>\n";
					echo "														<option value='7'"; if ($column[$f] == '7') { echo " selected"; } echo "> " . translate("ChargerUnknow-DarkGrey") . "</option>\n";
					echo "														<option value='8'"; if ($column[$f] == '8') { echo " selected"; } echo "> " . translate("ChargerManut-DarkGrey") . "</option>\n";
					echo "													</select>\n";
					echo $foot;
					break;
					
				// CUSTOMER BIRTHDATE
				case "CustomerBirthDate":
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<div class='input-group'>\n";
						echo "														<span class='input-group-prepend'>\n";
						echo "															<span class='input-group-text'><i class='icon-calendar3'></i></span>\n";
						echo "														</span>\n";
						$date = date("d/m/Y"); if ($column[$f] != '') { $date = $column[$f]; }
						echo "														<input type='text' class='form-control l-200' id='" . $columns[$f]['COLUMN_NAME'] . "' name='" . $columns[$f]['COLUMN_NAME'] . "' placeholder='" . translate("SelectAdate") . "...' value='" . $date . "'>\n";
						echo "													</div>\n";
						echo $foot;
						echo "										<script>$('#" . $columns[$f]['COLUMN_NAME'] . "').AnyTime_picker({ format: '%d/%m/%Z' });</script>\n";
					break;	
					
				// CUSTOMER ID
				case "CustomerID":
					$d = select("*", "appcustomers", "where (CompanyID=" . COMPANYID . " and CustomerActive='1')");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["CustomerID"] . "'"; if ($column[$f] == $r["CustomerID"]) { echo " selected"; } echo "> " . $r["CustomerName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;
					
				// CUSTOMER TYPE					
				case "CustomerType":
					echo $head;				
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<select class='form-control form-control-select2 select2combo l-200' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
					echo "														<option value='S'"; if ($column[$f] == 'S') { echo " selected"; } echo "> " . translate("Stations") . "</option>\n";
					echo "														<option value='C'"; if ($column[$f] == 'C') { echo " selected"; } echo "> " . translate("Customer") . "</option>\n";
					echo "													</select>\n";
					echo $foot;
					break;
					
				// SERVICE STATUS
				case "ServiceStatus":
						$d = select("*", "appservicestatus");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ServiceStatus"] . "'"; if ($column[$f] == $r["StatusID"]) { echo " selected"; } echo "> " . translate($r["ServiceStatusName"]) . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;
					
					// SOCKETS
				case "SocketID":
				    $d = select("*", "appsockets", "where (CompanyID=" . COMPANYID . " and SocketActive='1')");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketID"] . "'"; if ($column[$f] == $r["SocketID"]) { echo " selected"; } echo "> " . $r["SocketName"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketGrid":
				    $d = select("*", "appsocketsgrid");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketGridID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketGridID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketGrid"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				    
				case "SocketPhase":
				    $d = select("*", "appsocketsphase");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketPhaseID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketPhaseID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketPhase"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketAmp":
				    $d = select("*", "appsocketsamp");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketAmpID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketAmpID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketAmp"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketPower":
				    $d = select("*", "appsocketspower");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketPowerID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketPowerID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketPower"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketCableType":
				    $d = select("*", "appsocketscabletype");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketCableTypeID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketCableTypeID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketCableType"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketCableLenght":
				    $d = select("*", "appsocketscablelenght");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketCableLenghtID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketCableLenghtID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketCableLenght"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				case "SocketType":
				    $d = select("*", "appsocketstype");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["SocketTypeID"] . "'"; if ((strpos($column[$f], "#" . $r["SocketTypeID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["SocketType"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
					
				// STATIONS
				case "StationID":
					$d = select("*", "appstations", "where (CompanyID=" . COMPANYID . " and StationActive='1')");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["StationID"] . "'"; if ($column[$f] == $r["StationID"]) { echo " selected"; } echo "> " . $r["StationName"] . " </option>\n";	
						}
						echo "													</select>\n";
						echo $foot;
					}
					break;	
					

				// START TIME / END TIME
				case "StartTime":
				case "EndTime":
				case "ServiceChargerTime":
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<div class='input-group'>\n";
						echo "														<span class='input-group-prepend'>\n";
						echo "															<button type='button' class='btn btn-light btn-icon' id='ButtonCreationDemoButton'><i class='icon-calendar3'></i></button>\n";
						echo "														</span>\n";
						$xDate = $column[$f]; if ($xDate == '') { $xDate = date("Y-m-d h:i:s"); }
						echo "														<input type='text' class='form-control l-160' id='Button" . $columns[$f]['COLUMN_NAME'] . "' name='" . $columns[$f]['COLUMN_NAME'] . "' value='" . $xDate . "' placeholder='" . translate("SelectAdate") . "...'>\n";
						echo "													</div>\n";
						echo $foot;
						echo "										<script>$('#Button" . $columns[$f]['COLUMN_NAME'] . "').AnyTime_picker({ format: '%Z-%m-%d %h:%i:%s' });</script>\n";
					break;		
				
				case "ChargerOperatorPassword":
				case "CustomerPassword":
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "                                                      <input type='password' class='form-control user-password' name='".$columns[$f]['COLUMN_NAME']."' value='' placeholder='" . translate("Max12Chars") . "' maxlength='12' autocomplete='off' readonly  onfocus=\"if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus(); }\" />\n";
				        echo "                                                  <br /> ";
				    break;

				// MOBILITY -----------------	
					
				// 
                //case "CustomerCity": @TODO REMOVER
				case "ChargerOperatorCity":
				case "StationOwnerCity":
				    $cityState = 19;
				    $d = select("*", "appcities", "where CityState = $cityState AND CityActive = 1");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["CityID"] . "'"; if ($cityState == $r["CityID"]) { echo " selected"; } echo "> " . $r["CityName"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				//case "CustomerState": @TODO REMOVER
				case "ChargerOperatorState":
				case "StationOwnerState":
				    $stateCountry = 32;
				    $d = select("*", "appstates", "where StateCountry = $stateCountry AND StateActive = 1");
				    if ($d) {
				        echo $head;
				        echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
				        echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
				        foreach ($d as $r) {
				            echo "														<option value='" . $r["StateID"] . "'"; if ($cityState == $r["StateID"]) { echo " selected"; } echo "> " . $r["StateName"] . " </option>\n";
				        }
				        echo "													</select>\n";
				        echo $foot;
				    }
				    break;
				    
				//HIDING FIELDS
				case "Latitude":
				case "Longitude":
				case "CustomerPasswordFlag":
				case "SocketStatus":
				    break;
				    
				// DEFAULT
				default:
					$maxlength = $columns[$f]['CHARACTER_MAXIMUM_LENGTH'];
					$fieldSize = '';
					if ($maxlength <= 49) { $fieldSize = " l-400"; }
					if ($maxlength <= 39) { $fieldSize = " l-300"; }
					if ($maxlength <= 29) { $fieldSize = " l-200"; }
					if ($maxlength <= 19) { $fieldSize = " l-160"; }
					if ($maxlength <= 12) { $fieldSize = " l-120"; }
					if ($maxlength <= 2) { $fieldSize = " l-50"; }			
					if ($maxlength == null) { $fieldSize = " l-100"; }
					echo $head;
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<input type='text' class='form-control" . $fieldSize . "' name='" . $columns[$f]['COLUMN_NAME'] . "' value='" . $column[$f] . "' maxlength='" . $maxlength . "' placeholder='" . translate($columns[$f]['COLUMN_NAME']) . "' />\n";
					if ($required == $columns[$f]['COLUMN_NAME']) { echo "													<span class='help-block text-danger'>" . $message . "</span>\n"; }
					echo $foot;
					break;
			}			
			
		}

	echo "									</form>\n";	
	
}