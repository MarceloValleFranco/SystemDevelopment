<?php

// PAGEHEAD.PHP 1.0 (2019/05/10)

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
     
    // ----------- MOBILITY -----------    
    
    if ($appTable == "appservices") {
        
        // DATE SELECTOR PLUGINS
        echo addCode("../../core/plugins/ui/moment/moment.min.js");
        echo addCode("../../core/plugins/pickers/daterangepicker.js");
        echo addCode("../../core/plugins/pickers/anytime.min.js");
        echo addCode("../../core/plugins/pickers/pickadate/picker.js");
        echo addCode("../../core/plugins/pickers/pickadate/picker.date.js");
        echo addCode("../../core/plugins/pickers/pickadate/picker.time.js");
        echo addCode("../../core/plugins/pickers/pickadate/legacy.js");
        echo addCode("../../core/plugins/notifications/jgrowl.min.js");
        
        // GET INITIAL DATE
        if (isset($_GET['IniDate'])) { $iniDate = $_GET['IniDate']; } else { $date = new DateTime(date('Y-m-d')); $iniDate = $date->format('Y-m-d'); }
        
        // GET FINAL DATE
        if (isset($_GET['EndDate'])) { $endDate = $_GET['EndDate']; } else  { $date = new DateTime(date('Y-m-d')); $endDate = $date->format('Y-m-d'); }             
        
        // DATE SELECTOR CODE
        dateRangePicker($iniDate, $endDate);
     
        // DATE SELECTOR BUTTON  
        echo "							<form id='dateForm' method='get'>\n";
        echo "							    <button type='button' class='btn btn-warning ml-2 daterange-ranges'><i class='icon-calendar22 mr-2'></i><span></span></button>\n"; 
        echo "						        <input type='hidden' value='" . $iniDate . "' id='IniDate' name='IniDate' />\n"; 
        echo "						        <input type='hidden' value='" . $endDate . "' id='EndDate' name='EndDate' />\n"; 
		echo "								<input type='hidden' name='n' value='" . $appID ."' />\n";
		echo "								<input type='hidden' name='v' value='" . $origin ."' />\n";        
        echo "							</form>\n";
    
    }
    
    // ----------- /MOBILITY -----------
        
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
	echo "								<a href='#' title='" . translate(getUserGroupName()) . " (" . getCompanyName() . ")' class='breadcrumb-elements-item' id='../../modules/default/sysusers.php?a=e&p=1&i=" . USERID . "'><i class='icon-user mr-2'></i>" . translate("UserProfile") . "</a>\n";  			
	echo "								<a href='#' title='" . translate("UserPreferences") . "' class='breadcrumb-elements-item' id='../../modules/default/syspreferences.php'><i class='icon-user-check mr-2'></i>" . translate("UserPreferences") . "</a>\n";  			
    
	// BREAD CRUMB RIGHT PULLDOWN
	if (getPermission("View", "UsersReport")) { 
    
        // ----------- MOBILITY -----------
    
        // APPSERVICES REPORT
        if ($appTable == "appservices") {
            echo "									<a href='#' title='" . translate("ExportXLS") . "' class='breadcrumb-elements-item' id='autoexcel.php?t=appservices&i=" . $iniDate . "&e=" . $endDate . "'><i class='icon-file-pdf'></i> " . translate("ExportXLS") . "</a>\n"; 
        }
        
        // ----------- /MOBILITY -----------
        
	}
	
	echo "							</div>\n"; // / breadcrumb
	
	echo "						</div>\n"; // / header-elements
	
	echo "					</div>\n"; // / breadcrumb-line

	echo "				</div>\n"; // / page-header