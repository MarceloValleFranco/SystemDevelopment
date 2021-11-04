<?php

// AUTOCRUD.PHP 1.0 (2019/05/09)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// REQUESTS
$appID = ""; if (isset($_GET['n'])) { $appID = clean($_GET["n"]); }
$itemID = ""; if (isset($_GET['i'])) { $itemID = clean($_GET["i"]); }
$action = ""; if (isset($_GET['a'])) { $action = clean($_GET["a"]); }
$message = ""; if (isset($_GET['m'])) { $message = clean($_GET["m"]); }
$origin = "sysapps"; if (isset($_GET['v'])) { $origin = clean($_GET["v"]); if ($origin == '1') { $origin = "sysmodules"; } }

// MAKE FORM FUNCTION
$include = "helpers/makeform-template.php";
if (file_exists("helpers/makeform.php")) { $include = "helpers/makeform.php"; }
require $include;

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
    echo addCode("../../core/plugins/tables/datatables/datatables.fixedheader.min.js");
	echo addCode("../../core/plugins/extensions/leftcontextmenu.js");

	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	    
    // PAGE HEADER
    $include = "helpers/pagehead-template.php";
    if (file_exists("helpers/pagehead.php")) { $include = "helpers/pagehead.php"; }
    require $include;    
	
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
   
    // TABLE QUERY
    $include = "helpers/tablequery-template.php";
    if (file_exists("helpers/tablequery.php")) { $include = "helpers/tablequery.php"; }
    require $include;	    
    
    
    // LIST RECORDS
    if ($d) {
			
		// CARD HEAD
		echo "					<div class='card'>\n";
	
		echo "						<table class='table table-border ed table-hover datatable-highlight'>\n";

		// TABLE HEAD
		$include = "helpers/tablehead-template.php";
		if (file_exists("helpers/tablehead.php")) { $include = "helpers/tablehead.php"; }
		require $include;
		
		// TABLE BODY
		$include = "helpers/tablebody-template.php";
		if (file_exists("helpers/tablebody.php")) { $include = "helpers/tablebody.php"; }
		require $include;	
		
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
		if ($e != 0) { echo "                              <button type='button' id='" . $itemID . "#" . $origin . "#" . $column[1] . "' class='btn btn-danger ml-2 delete-button'><i class='icon-minus-circle2'></i><span class='button-text'>" . translate("Delete") . "</span></button>\n"; }
	
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

	// GET DATA INPUTED
	$include = "helpers/saveform-template.php";
	if (file_exists("helpers/saveform.php")) { $include = "helpers/saveform.php"; }
	require $include;	
    
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