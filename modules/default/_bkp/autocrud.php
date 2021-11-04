<?php

// AUTOCRUD.PHP 1.0 (2019/01/10)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// REQUESTS
$appID = ""; if (isset($_GET['n'])) { $appID = clean($_GET["n"]); }
$itemID = ""; if (isset($_GET['i'])) { $itemID = clean($_GET["i"]); }
$action = ""; if (isset($_GET['a'])) { $action = clean($_GET["a"]); }
$message = ""; if (isset($_GET['m'])) { $message = clean($_GET["m"]); }
$origin = "sysapps"; if (isset($_GET['v'])) { $origin = clean($_GET["v"]); if ($origin == '1') { $origin = "sysmodules"; } }

// DATABASE OPEN
openDB();

	// PAGE HEADER
	pageHeader("autocrud");
	
		// APP DATA
		if ($appID == '') {
			
			// ERROR
			message("AutoCrudMissId", "danger", 1);			
		
		} else {
			$r = select("*", $origin, "where ItemID=" . $appID);
			if ($r) { 
					
				$appName = $r[0]["ItemName"];
				$appDescription = $r[0]["ItemDescription"];
				$appTable = $r[0]["ItemTable"];
				$appMarkers = $r[0]["SaveOnMarkers"];

			} else {
				
				// ERROR
				message("AutoCrudMissTable", "danger", 1);
			}
		}		

		switch ($action) {
			case "c": itemEdit('0', '','', $itemID); break;
			case "d": if (DEMO) { itemList($message); } else  { itemDelete($itemID); } break;
			case "e": itemEdit($itemID, $message); break;
			case "s": if (DEMO) { itemList($message); } else  { itemSave($itemID); } break;
			default: itemList($message); break;
		}

	// PAGE FOOTER
	pageFooter();

// DATABASE CLOSE
closeDB();

function itemList($message = '') {

	global $app;
	global $appID;
	global $appName;
	global $appDescription;
	global $appTable;
	global $origin;
	
	// DEBUG
	//echo "[" . $appID . "] Name [" . $appName . "] Desc [" . $appDescription . "] Table [" . $appTable . "] <br />"; //exit; 	
		
	// PAGE BODY
	echo "	<body>\n";
	
    // CONFIRM BOX
    confirmBox(); 
	
	// PLUGINS
	echo addCode("../../core/plugins/forms/selects/select2.min.js");
	echo addCode("../../core/plugins/tables/datatables/datatables.min.js");
	echo addCode("../../core/plugins/extensions/leftcontextmenu.js");

	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	
	// PAGE HEADER	
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . translate($appName) . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . translate($appDescription) . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		// ADD NEW ITEM
		echo "							<button type='button' id='" . $origin . "#" . $appID . "' class='btn btn-success ml-2 add-button'><i class='icon-plus-circle2'></i><span class='button-text'>" . translate("AddItem") . "</span></button>\n"; 	
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item initial-page'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate($appName) . "</span>\n";  
	echo "							</div>\n";	

	// TOGGLER
	echo "							<a href='#' class='header-elements-toggle text-default d-md-none'><i class='icon-more'></i></a>\n";
	
	echo "						</div>\n"; 
 
	echo "						<div class='header-elements d-none'>\n";
	
	echo "							<div class='breadcrumb justify-content-center'>\n";
	
	// BREAD CRUMB RIGHT BUTTONS
	echo "								<a href='#' title='" . translate(getUserGroupName()) . " (" . getCompanyName() . ")' class='breadcrumb-elements-item' id='sysusers.php?a=e&p=1&i=" . USERID . "'><i class='icon-user mr-2'></i>" . translate("UserProfile") . "</a>\n";  			
	echo "								<a href='#' title='" . translate("UserPreferences") . "' class='breadcrumb-elements-item' id='syspreferences.php'><i class='icon-user-check mr-2'></i>" . translate("UserPreferences") . "</a>\n";  			
	
	// BREAD CRUMB RIGHT PULLDOWN
	// if (getPermission("View", "UsersReport")) { 
		// echo "								<a href='#' class='breadcrumb-elements-item dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2 mr-2'></i>" . translate("Reports") . "</a>\n"; 
		// echo "								<div class='dropdown-menu dropdown-menu-right'>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-printer'></i> " . translate("Print") . "</a>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-file-pdf'></i> " . translate("ExportPDF") . "</a>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-file-excel'></i> " . translate("ExportExcel") . "</a>\n"; 
		// echo "									<div class='dropdown-divider'></div>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-gear'></i> 123... 4</a>\n"; 
		// echo "								</div>\n";
	// }
	
	echo "							</div>\n"; // / breadcrumb
	
	echo "						</div>\n"; // / header-elements
	
	echo "					</div>\n"; // / breadcrumb-line

	echo "				</div>\n"; // / page-header	
	
	// MAIN CONTENT
	echo "				<div class='content'>\n";
	
    // MESSAGE
    if ($message != '') { 
        $mType = "success"; if (isset($_GET['t'])) { $mType = "danger"; }
		message($message, $mType);
    } 

	// GET TABLE COLUMNS
	$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')");
	
	// DEBUG
	//print_r($columns); exit;	

	// SQL QUERY
	$d = select("*", $appTable);
    if ($d) {
			
		// CARD HEAD
		echo "					<div class='card'>\n";
				
		echo "						<table class='table table-border ed table-hover datatable-highlight'>\n";

		// TABLE HEADER
		echo "							<thead>\n"; 
		echo "								<tr>\n"; 
        echo "                                  <tr>\n";
		
			for ($f = 1; $f <= count($columns)-1; $f++) {
				
				switch ($columns[$f]['COLUMN_NAME']) {
					
					// NO SHOW
					case "CompanyID":
					case "CustomerAddress":
					case "CustomerCity":
					case "CustomerState":
					case "CustomerPassword":
					case "CustomerPostalCode":
					case "CustomerNameOnCard":
					case "CustomerCardNumber":
					case "CustomerCardValidate":
					case "CustomerCardCVV":
					case "ItemColumns":
					case "ItemDescription":
					case "ItemLocations":
					case "ItemOrder":
					case "ItemScript";
					case "ItemScriptFile";
					case "ItemSignature":
					case "ItemTable":
					case "ItemURL":
					case "Latitude":
					case "Longitude":
					case "LocationType":
					case "LocationGroupID":				
					case "SaveOnMarkers":
						break;
						
					// NO SHOW ON MOBILE
					case "ItemOrder":
					case "ShowOnMobile":
					case "ShowOnTablet":
					case "ShowOnDesktop":
						echo "                                      <th class='table-th d-none d-sm-table-cell r-120'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;	

					// ITEM NAME
					case "ItemName":
						if (($appTable == "sysapps") || ($appTable == "sysmodules")) { echo "                                      <th class='table-th l-160'>" . translate("ItemCodeName") . "</th>\n"; }
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;							
						
					// ITEM ACTIVE
					case "CompanyActive":
					case "ItemActive":
						echo "                                      <th class='table-th r-50'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;

					// COMPANY CODE
					case "CompanyCode":
						echo "                                      <th class='table-td d-none d-sm-table-cell l-200'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;						
					
					default:
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;
				}					
			}
			
        echo "                                  </tr>\n";
		echo "								</tr>\n"; 
		echo "							</thead>\n";

		echo "							<tbody>\n"; 

		// RECORDS LOOP
		foreach ($d as $r) { 
		
			echo "								<tr class='table-tr' id='" . $r[$columns[0]['COLUMN_NAME']] . "#" . $r[$columns[2]['COLUMN_NAME']] . "#" . $origin . "#" . $appID . "'>\n"; 

				for ($f = 1; $f <= count($columns)-1; $f++) {
					
					switch ($columns[$f]['COLUMN_NAME']) {
						
						// NO SHOW
						case "CompanyID":
						case "CustomerAddress":
						case "CustomerCity":
						case "CustomerState":
						case "CustomerPassword":
						case "CustomerPostalCode":
						case "CustomerNameOnCard":
						case "CustomerCardNumber":
						case "CustomerCardValidate":
						case "CustomerCardCVV":
						case "ItemColumns":
						case "ItemLocations":
						case "ItemDescription":	
						case "ItemOrder":						
						case "ItemScript";
						case "ItemScriptFile";
						case "ItemSignature":
						case "ItemTable":
						case "ItemURL":
						case "ItemLocations":
						case "Latitude":
						case "Longitude":
						case "LocationType":
						case "LocationGroupID":
						case "SaveOnMarkers":
							break;
							
						// NO SHOW ON MOBILE
						case "ItemOrder":
							echo "                                      <td class='table-td d-none d-sm-table-cell r-50'>" . $r[$columns[$f]['COLUMN_NAME']] . "°</td>\n";
							break;							

						// SHOW ON
						case "ShowOnMobile":
						case "ShowOnTablet":
						case "ShowOnDesktop":
							$co = "danger"; $no = translate("Hidden"); $ic = "cross3";
							if ($r[$columns[$f]['COLUMN_NAME']] == '1') { $co = "primary"; $no = translate("Visible"); $ic = "checkmark"; }                
							echo "                                      <td class='table-td d-none d-sm-table-cell r-120'><span title='" . $no . "' class='icon-" . $ic . " text-" . $co . " item-icon'></span></td>\n";						
							break;
							
						// ITEM NAME
						case "ItemName":
							if (($appTable == "sysapps") || ($appTable == "sysmodules")) { echo "                                      <td class='table-td'>" . $r[$columns[$f]['COLUMN_NAME']] . "</td>\n"; }
							echo "                                      <td class='table-td'>" . translate($r[$columns[$f]['COLUMN_NAME']], 0) . "</td>\n";
							break;								
							
						// ITEM ACTIVE
						case "CompanyActive":
						case "ItemActive":
							$co = "danger"; $no = translate("Inactive");
							if ($r[$columns[$f]['COLUMN_NAME']] == '1') { $co = "success"; $no = translate("Active"); }                
							echo "                                      <td class='table-td r-50'><span title='" . $no . "' class='badge badge-" . $co . " text-uppercase w-50'>" . left($no, 1) . "</span></td>\n";						
							break;

						// ITEM ICON
						case "ItemIcon":
							echo "                                      <td class='table-td l-50'><span class='icon-" . $r[$columns[$f]['COLUMN_NAME']] . "'></span></td>\n";
							break;							
						
						// APP GROUP ID
						case "AppGroupID":
							echo "                                      <td class='table-td'>" . translate(getAppGroupName($r["AppGroupID"])) . "</td>\n";
							break;								
							
						// ACTIVE
						case "AppActive":
							switch($r["AppActive"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								case "2": $c = "warning"; $n = translate("Disabled"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;
							
						// COMPANY CODE
						case "CompanyCode":
							echo "                                      <td class='table-td d-none d-sm-table-cell l-200'>" . $r[$columns[$f]['COLUMN_NAME']] . "</span></td>\n";
							break;	
							
						// ITEM STATUS
						case "ItemStatus":
							switch($r["ItemStatus"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								case "2": $c = "warning"; $n = translate("Disabled"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;

						// MOBILITY -----------------
						
						case "ChargerActive":
							switch($r["ChargerActive"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;	
						
						case "ChargerStatus":
							switch($r["ChargerStatus"]) {
								case "0": $c = "danger"; $i = "cancel-square2"; $n = translate("Inactive"); break;
								case "2": $c = "warning"; $i = "warning2"; $n = translate("MalFunction"); break;
								default: $c = "success"; $i = "checkmark-circle"; $n = translate("Active"); break;                
							} 
							if ($r["ChargerActive"] == '0') { $c = "grey"; $i = "cancel-square2"; $n = translate("Inactive"); }
							echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";							
							break;
						
						case "ChargerID":
						echo "                                      <td class='table-td'>";
							$d = select("*", "appchargers", "where ChargerID=" . $r["ChargerID"]);
							if ($d) {
								echo $d[0]["ChargerName"];
							}
							echo "</span></td>\n";
							break;
							
						case "CustomerActive":
							switch($r["CustomerActive"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;
							
						case "CustomerID":
						echo "                                      <td class='table-td'>";
							$d = select("*", "appcustomers", "where CustomerID=" . $r["CustomerID"]);
							if ($d) {
								echo $d[0]["CustomerName"];
							}
							echo "</span></td>\n";
							break;

						case "ServicePrice":
						case "SocketPrice":
							$price = translate("NoDefined"); if ($r["SocketPrice"] != '') { $price = number_format(($r["SocketPrice"]/100),2,",","."); }
							echo "                                      <td class='table-td r-50'>R$ " .  $price . "</td>\n";							
							break;							
						
						case "SocketActive":
							switch($r["SocketActive"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;	
						
						case "SocketStatus":
							switch($r["SocketStatus"]) {
								case "0": $c = "danger"; $i = "cancel-square2"; $n = translate("Inactive"); break;
								case "2": $c = "warning"; $i = "warning2"; $n = translate("MalFunction"); break;
								default: $c = "success"; $i = "checkmark-circle"; $n = translate("Active"); break;                
							} 
							if ($r["SocketActive"] == '0') { $c = "grey"; $i = "cancel-square2"; $n = translate("Inactive"); }
							echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";							
							break;
							
						case "StationActive":
							switch($r["StationActive"]) {
								case "0": $c = "danger"; $n = translate("Inactive"); break;
								default: $c = "success"; $n = translate("Active"); break;                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";							
							break;	
						
						case "StationStatus":
							switch($r["StationStatus"]) {
								case "0": $c = "danger"; $i = "cancel-square2"; $n = translate("Inactive"); break;
								case "2": $c = "warning"; $i = "warning2"; $n = translate("MalFunction"); break;
								default: $c = "success"; $i = "checkmark-circle"; $n = translate("Active"); break;                
							} 
							if ($r["StationActive"] == '0') { $c = "grey"; $i = "cancel-square2"; $n = translate("Inactive"); }
							echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";							
							break;
						
						case "SocketVoltage":
							switch($r["SocketVoltage"]) {
								case "220": $c = "warning";
								default: $c = "info";                
							} 
							echo "                                      <td class='table-td r-50'><span title='" . $r[$columns[$f]['COLUMN_NAME']] . "V' class='badge badge-" . $c . " text-uppercase w-50'>" .  $r[$columns[$f]['COLUMN_NAME']] . "V</span></td>\n";							
							break;
							
						case "StationID":
						echo "                                      <td class='table-td'>";
							$d = select("*", "appstations", "where StationID=" . $r["StationID"]);
							if ($d) {
								echo $d[0]["StationName"];
							}
							echo "</span></td>\n";
							break;

						// MOBILITY -----------------
						
						// DEFAULT
						default:
							echo "                                      <td class='table-td'>" . $r[$columns[$f]['COLUMN_NAME']] . "</td>\n";
							break;
					}			
				}
			
			echo "								</tr>\n";			
		}
			
		echo "							</tbody>\n";

		echo "						</table>\n"; 
		
		echo "					</div>\n"; // /card		
        
    } else {
    
        // EMPTY GRID (NO RECORD FOUND)
	    echo "					<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";
        
    }	

	// DATA-TABLE RENDER
	dataTable();	
	
	// CONTEXT MENU
	echo "					<div id='OptionMenu' class='context-menu display-none'>\n";
	echo "						<ul>\n";	
	echo "							<li id='edit'><i class='icon-pencil7 context-icon'></i>" . translate('Edit') . "</li>\n";
	echo "							<li id='clone'><i class='icon-users2 context-icon'></i>" . translate('Clone') . "</li>\n"; 
	echo "							<li id='delete'><i class='icon-user-minus context-icon'></i>" . translate('Delete') . "</li>\n";
	echo "						</ul>\n";
	echo "					</div>\n"; 
	
	echo "					<script>\n"; 
	echo "						if ($('.table').length) {\n"; 
	echo "							$('tr.table-tr').contextMenu('OptionMenu', {\n"; 
	echo "								bindings: {\n"; 
	echo "									'edit': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										block('body');\n"; 
	echo "										window.location.href = ('?a=e&i=' + r[0] + '&v=' + r[2] + '&n=' + r[3])\n"; 
	echo "									},\n"; 
	echo "									'delete': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										confirmBox(r[0], r[1])\n"; 
	echo "									},\n"; 
	echo "									'clone': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										block('body');\n"; 
	echo "										window.location.href = ('?a=c&i=' + r[0] + '&v=' + r[2] + '&n=' + r[3])\n"; 
	echo "									}\n"; 
	echo "								}\n"; 
	echo "							});\n"; 
	echo "						}\n"; 
	echo "					</script>\n";	

	echo "				</div>\n"; // /content
	
	echo "			</div>\n"; // /page-wrapper
	echo "		</div>\n"; // /page-content
	
	echo "	</body>\n";	
	
	// SAVE EVENT LOG
	if (!isset($_GET['a'])) { logWrite("View", $appName, $appTable); }	
	
}

function itemEdit($itemID, $message = '', $required = '', $cloneID = '', $t = '') {

	global $app;
	global $appID;
	global $appName;
	global $appDescription;
	global $appTable;
	global $origin;
   
	// PAGE BODY
    echo "	<body>\n";
    
    // PLUGINS
    echo addCode("../../core/plugins/forms/selects/select2.min.js");
    echo addCode("../../core/plugins/forms/styling/uniform.min.js");
    echo addCode("../../core/plugins/forms/styling/switchery.min.js");
	
	echo addCode("../../core/plugins/ui/moment/moment.min.js");
	echo addCode("../../core/plugins/pickers/daterangepicker.js");
	echo addCode("../../core/plugins/pickers/anytime.min.js");
	echo addCode("../../core/plugins/pickers/pickadate/picker.js");
	echo addCode("../../core/plugins/pickers/pickadate/picker.date.js");
	echo addCode("../../core/plugins/pickers/pickadate/picker.time.js");
	echo addCode("../../core/plugins/pickers/pickadate/legacy.js");
    
    // CONFIRM BOX
    confirmBox();  
	
	// DEFINE PAGE TITLE
    $title = translate($appName); $description = translate($appDescription);   
	
	// GET TABLE COLUMNS
	$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')"); 
	if ($itemID != '0' || $cloneID != '0') { $e = $itemID; if ($cloneID != '') { $e = $cloneID; } else { if ($itemID != '0') { $description = translate("ItemEdit"); } } }
	
	// GET EXISTENT DATA
	if ($e != 0) { $column = array(); if ($r = select("*", $appTable, "where " . $columns[0]['COLUMN_NAME'] . "=" . $e)) { for ($f = 1; $f <= count($columns)-1; $f++) { $column[$f] = $r[0][$columns[$f]['COLUMN_NAME']]; } } }
	
	// PAGE CONTENT
	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	
	// PAGE HEAD	
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . $title . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . $description . "</small></h4>\n";
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		// CANCEL
		echo "                              <button type='button' id='" . $origin . "#" . $appID . "' class='btn btn-default cancel-button d-none d-md-block'><i class='icon-chevron-left pl-0 pr-0'></i><span class='button-text'>" . translate("Cancel") . "</span></button>\n";
	
		// DELETE
		if ($e != 0) { echo "                              <button type='button' id='" . $origin . "#" . $itemID . "#" . $column[1] . "' class='btn btn-danger ml-2 delete-button'><i class='icon-minus-circle2'></i><span class='button-text'>" . translate("Delete") . "</span></button>\n"; }
	
		// SAVE / UPDATE
		$act = "Edit"; $caption = "Update"; if ($itemID == '0') { $act = "Add"; $caption = "Save"; }
		echo "                              <button type='button' class='btn btn-success ml-2 save-button'><i class='icon-check'></i><span class='button-text'>" . translate($caption) . "</span></button>\n";
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item initial-page'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<a href='?v=" . $origin . "&n=" . $appID ."' class='breadcrumb-item'>" . translate($appName) . "</a>\n"; 
	echo "								<span class='breadcrumb-item active d-none d-lg-block'>" . $title . "</span>\n"; 
	echo "							</div>\n";	

	// TOGGLER
	echo "							<a href='#' class='header-elements-toggle text-default d-md-none'><i class='icon-more'></i></a>\n";
	
	echo "						</div>\n"; 
 
	echo "						<div class='header-elements d-none'>\n";
	
	echo "							<div class='breadcrumb justify-content-center'>\n";
	
	// BREAD CRUMB RIGHT BUTTONS
	//if (getPermission("View", "UsersGroupsManager")) { echo "								<a href='#' class='breadcrumb-elements-item'><i class='icon-users4 mr-2'></i>" . translate("UsersGroupsManager") . "</a>\n"; }
	//if (getPermission("View", "UsersPermissions")) { echo "								<a href='#' class='breadcrumb-elements-item'><i class='icon-user-lock mr-2'></i>" . translate("UsersPermissions") . "</a>\n"; }
	
	// BREAD CRUMB RIGHT PULLDOWN
	// if (getPermission("View", "UsersReport")) { 
		// echo "								<a href='#' class='breadcrumb-elements-item dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2 mr-2'></i>" . translate("Reports") . "</a>\n"; 
		// echo "								<div class='dropdown-menu dropdown-menu-right'>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-printer'></i> " . translate("Print") . "</a>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-file-pdf'></i> " . translate("ExportPDF") . "</a>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-file-excel'></i> " . translate("ExportExcel") . "</a>\n"; 
		// echo "									<div class='dropdown-divider'></div>\n"; 
		// echo "									<a href='#' class='dropdown-item'><i class='icon-gear'></i> 123... 4</a>\n"; 
		// echo "								</div>\n";
	// }
	
	echo "							</div>\n"; // / breadcrumb
	
	echo "						</div>\n"; // / header-elements
	
	echo "					</div>\n"; // / breadcrumb-line

	echo "				</div>\n"; // / page-header
	
	// MAIN CONTENT
	echo "				<div class='content'>\n";	
    
    // MESSAGE
    if ($message != '') { 
        $mType = "success"; if ($t == '1') { $mType = "danger"; }
		message($message, $mType);
    } 
    
    echo "					<div class='card'>\n";
	
	echo "						<div class='card-body'>\n";
	
	echo "							<div class='row'>\n";
	
	echo "								<div class='col-md-6'>\n";
	
		// FORM MAKER
		makeForm($appID, $itemID, $cloneID, $required, $message, $origin);
		
	echo "								</div>\n"; // /col-md-6
	
	echo "							</row>\n";
	
    echo "						</div>\n"; // /card-body
    
    echo "					</div>\n"; // /card
	
    // DEMO MESSAGE
    if (DEMO) { echo "					<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; } 	
	
    echo "				</div>\n"; // /content
	
    echo "			</div>\n"; // /content-wrapper
    echo "		</div>\n"; // /page-container
	   
    echo "	</body>\n";       
}

function itemSave($itemID) {
	
	global $appID;
	global $appName;
	global $appDescription;
	global $appTable;
	global $appMarkers;
	global $origin;

	// GET TABLE COLUMNS
	$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')");	

    $t = array();
    $error = '';
    $field = '';
    
    // DEBUG
    //foreach(array_reverse($_GET) as $key=>$value) { echo ">" . $key . " = [" . $value . "]<br />\n"; }; //exit;    
    
    foreach(array_reverse($_GET) as $key=>$value) {
    
        if (strlen($key) > 1) {
        
			// REMOVE '
            $value = str_replace("'", "´", $value);
            
            if (is_array($value)) {
            
				// ARRAY VALUE
                $t[$key] = "#". implode("#", $value) . "#";
                
            } else {
            
                switch ($key) {
   
                    default: $t[$key] = $value; break;
                } 
            }
        }    
    }
   
    // DEBUG
    //print_r($t); exit;
    
    if ($error != '') {
    
		// ERROR: RETURN TO ITEM EDITION
        itemEdit($itemID, $error, $field, '', '1');
        
    } else {
    
        if ($itemID == '0') {

            // INSERT NEW ITEM
            if (insert($appTable, $t)) {
				
				// DEBUG
				//echo "<br />Insert:<br />"; print_r($t); echo "<br />"; //exit;
				
				// SAVE LOG EVENT
                logWrite("Add", $appName, $appTable);
			
				// SAVE ON MARKERS
				if ($appMarkers) {
					
					if ((isset($_GET["Latitude"])) && (isset($_GET["Longitude"]))) {
				
						$y = array();
						if (isset($_GET["Name"])) { $y["ItemName"] = $_GET["Name"]; }
						if (isset($_GET["ItemName"])) { $y["ItemName"] = $_GET["ItemName"]; }
						if (isset($_GET["Status"])) { $y["DeviceStatus"] = $_GET["Status"]; }
						if (isset($_GET["ItemStatus"])) { $y["DeviceStatus"] = $_GET["ItemStatus"]; }
						$y["ItemSignature"] = $_GET["ItemSignature"];
						$y["Latitude"] = $_GET["Latitude"];
						$y["Longitude"] = $_GET["Longitude"];
						if (isset($_GET["ItemLocations"])) { if ($_GET["ItemLocations"] != '') { $y["ItemLocations"] = "#". implode("#", $_GET["ItemLocations"]) . "#"; } }
						$y["ItemType"] = right($appTable, strlen($appTable) - 3);
						
						// DEBUG
						//echo "<br />Markers Insert:<br />"; print_r($y); echo "<br />"; //exit;						
						
						$w = insert("appmarkers", $y);					
						if ($w) {
							
							// SAVE LOG EVENT
							logWrite("Add", $appName, "appmarkers");						
							
						} else {
							
							// ERROR: RETURN TO ITEM EDITION
							itemEdit($itemID, "ErrorSavingOnMarkers", "Form", "", "1"); exit;

						}					
					}
				}				

				// RETURN TO ITEM LIST
                header("Location: ?a=x&m=RecordedOk&v=" . $origin . "&n=" . $appID);

            } else {

                // ERROR: RETURN TO ITEM EDITION
				itemEdit($itemID, "ErrorSavingData", "Form", "", "1");

            }          
        
        } else {    

            // UPDATE EXISTING ITEM
            if (update($appTable, $t, "where " . $columns[0]['COLUMN_NAME'] . "=" . $itemID)) {
				
				// DEBUG
				echo "<br />Update:<br />"; print_r($t); echo "<br />"; //exit;				

				// SAVE LOG EVENT
                logWrite("Update", $appName, $appTable, $itemID);
				
				// UPDATE ON MARKERS
				if ($appMarkers) {
				
					if ((isset($_GET["Latitude"])) && (isset($_GET["Longitude"]))) {
				
						$y = array();
						if (isset($_GET["Name"])) { $y["ItemName"] = $_GET["Name"]; }
						if (isset($_GET["ItemName"])) { $y["ItemName"] = $_GET["ItemName"]; }
						if (isset($_GET["Status"])) { $y["DeviceStatus"] = $_GET["Status"]; }
						if (isset($_GET["ItemStatus"])) { $y["DeviceStatus"] = $_GET["ItemStatus"]; }
						$y["ItemSignature"] = $_GET["ItemSignature"];
						$y["Latitude"] = $_GET["Latitude"];
						$y["Longitude"] = $_GET["Longitude"];
						if (isset($_GET["ItemLocations"])) { if ($_GET["ItemLocations"] != '') { $y["ItemLocations"] = "#". implode("#", $_GET["ItemLocations"]) . "#"; } }
						$y["ItemType"] = right($appTable, strlen($appTable) - 3);
	
						// DEBUG
						echo "<br />Markers Update:<br />"; print_r($y); echo "<br />Error: " . translate($error); //exit;	

						$w = update("appmarkers", $y, "where ItemSignature='" . $_GET["ItemSignature"] . "'");
						if ($w) {
	
							// SAVE LOG EVENT
							logWrite("Update", $appName, "appmarkers");						
							
						} else {
							
							// ERROR: RETURN TO ITEM EDITION
							itemEdit($itemID, "ErrorUpdatingOnMarkers", "Form", "", "1"); exit;

						}
					}
				}					

				// RETURN TO ITEM LIST
                header("Location: ?a=x&m=UpdatedOk&v=" . $origin . "&n=" . $appID);

            } else {

				// ERROR: RETURN TO ITEM EDITION
                itemEdit($itemID, "ErrorUpdatingData", "Form", "", "1"); 

            }        
        
        }    
    
    }
}

function itemDelete($itemID) {
	
	global $appID;
	global $appTable;
	global $origin;

	// GET TABLE COLUMNS
	$columns = select("*", "INFORMATION_SCHEMA.COLUMNS", "WHERE  (TABLE_SCHEMA='" . DATABASENAME . "' AND TABLE_NAME='" . $appTable . "')");		

	if (delete($appTable, "where " . $columns[0]['COLUMN_NAME'] . "=" . $itemID)) { 

		// SAVE LOG EVENT
		logWrite("Delete", $appName, $appTable, $itemID);

		// RETURN TO USER LIST
		header("Location: ?a=x&m=DeletedOk&v=" . $origin . "&n=" . $appID);

	} else {

		// ERROR: RETURN TO USER LIST
		header("Location: ?a=x&m=DeleteError&v=" . $origin . "&t=1&n=" . $appID);

	}
}

function confirmBox() {
	
	global $appID;
	global $origin;

    // CONFIRM DELETE BOX
    echo addCode("../../core/plugins/notifications/bootbox.min.js");
    
    echo "		<script>\n";
    echo "			function confirmBox(i, o) {\n";
    echo "              bootbox.dialog({ message: '" . translate('ConfirmDelete') . " \"' + o + '\" ?', title: '<b>" . translate('Delete') . "</b>', buttons: {\n";
    echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i> " . translate('Cancel') . "', className: 'btn-default' },\n";
    echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i> " . translate('Confirm') . "', className: 'btn-danger', callback: function() { window.location.href=('?a=d&n=" . $appID . "&v=" . $origin  . "&i=' + i); } }\n";
    echo "              }});\n";
    echo "			}\n";
    echo "		</script>\n";

}

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
				case "ChargerStatus":
				case "SocketStatus":
				case "StationStatus":
					echo $head;				
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
					echo "														<option value='0'"; if ($column[$f] == '0') { echo " selected"; } echo "> " . translate("Disabled") . "</option>\n";
					echo "														<option value='1'"; if ($column[$f] == '1') { echo " selected"; } echo "> " . translate("Enabled") . "</option>\n";
					echo "														<option value='2'"; if ($column[$f] == '2') { echo " selected"; } echo "> " . translate("Suspended") . "</option>\n";
					echo "													</select>\n";
					echo $foot;
					break;
					
				// APP GROUP ID
				case "AppGroupID":
					$d = select("*", "sysappsgroups", "where (CompanyID=" . COMPANYID . " and ItemActive='1')");
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
						echo "													<select multiple='multiple' class='form-control form-control-select2 select2multiple' name='" . $columns[$f]['COLUMN_NAME'] . "[]' data-fouc>\n";
						//echo "														<optgroup label='Mountain Time Zone'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ItemID"] . "'"; if ((strpos($column[$f], "#" . $r["ItemID"] . "#")) === false) {} else { echo " selected"; } echo "> " . $r["ItemName"] . " </option>\n";	
						}
						//echo "														</optgroup>\n";
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
					$d = select("*", "appchargers", "where (CompanyID=" . COMPANYID . " and ChargerActive='1')");
					if ($d) {
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
						foreach ($d as $r) {
							echo "														<option value='" . $r["ChargerID"] . "'"; if ($column[$f] == $r["ChargerID"]) { echo " selected"; } echo "> " . $r["ChargerName"] . " </option>\n";	
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
					echo "													</select>\n";
					echo $foot;
					break;
					
				// CUSTOMER BIRTHDATE format: '%d/%m/%Z'
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
						echo "										<script>$('#" . $columns[$f]['COLUMN_NAME'] . "').AnyTime_picker({  });</script>\n";
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
					
				// SERVICE STATUS
				case "ServiceStatus":
					echo $head;				
					echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
					echo "													<select class='form-control form-control-select2 select2combo' name='" . $columns[$f]['COLUMN_NAME'] . "'>\n";
					echo "														<option value='1'"; if ($column[$f] == '1') { echo " selected"; } echo "> " . translate("Waiting") . "</option>\n";
					echo "														<option value='2'"; if ($column[$f] == '2') { echo " selected"; } echo "> " . translate("Charging") . "</option>\n";
					echo "														<option value='3'"; if ($column[$f] == '3') { echo " selected"; } echo "> " . translate("Completed") . "</option>\n";
					echo "														<option value='4'"; if ($column[$f] == '4') { echo " selected"; } echo "> " . translate("Interrupted") . "</option>\n";
					echo "														<option value='5'"; if ($column[$f] == '5') { echo " selected"; } echo "> " . translate("Paid") . "</option>\n";
					echo "														<option value='6'"; if ($column[$f] == '6') { echo " selected"; } echo "> " . translate("Unpaid") . "</option>\n";
					echo "														<option value='7'"; if ($column[$f] == '7') { echo " selected"; } echo "> " . translate("FinalizedWithSuccess") . "</option>\n";
					echo "														<option value='7'"; if ($column[$f] == '8') { echo " selected"; } echo "> " . translate("FinalizedWithoutSuccess") . "</option>\n";
					echo "													</select>\n";
					echo $foot;
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
						echo $head;				
						echo "													<label>" . translate($columns[$f]['COLUMN_NAME']) . "</label>\n";
						echo "													<div class='input-group'>\n";
						echo "														<span class='input-group-prepend'>\n";
						echo "															<button type='button' class='btn btn-light btn-icon' id='ButtonCreationDemoButton'><i class='icon-calendar3'></i></button>\n";
						echo "														</span>\n";
						echo "														<input type='text' class='form-control l-160' id='Button" . $columns[$f]['COLUMN_NAME'] . "' name='" . $columns[$f]['COLUMN_NAME'] . "' placeholder='" . translate("SelectAdate") . "...'>\n";
						echo "													</div>\n";
						echo $foot;
						echo "										<script>$('#Button" . $columns[$f]['COLUMN_NAME'] . "').on('click', function (e) { $('#Button" . $columns[$f]['COLUMN_NAME'] . "').AnyTime_noPicker().AnyTime_picker().focus(); e.preventDefault(); });</script>\n";
					break;			

				// MOBILITY -----------------					
				
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