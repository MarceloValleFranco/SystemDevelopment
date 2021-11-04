<?php

// SYSPERMISSIONS.PHP 1.0 (2018/12/04)

require "../../core/config.php";
require "../../core/functions.php";
//require "../../core/security.php";

if (isset($_GET['a'])) {

    charts();

} else {
    
    // DATABASE OPEN
    openDB();

        // PAGE HEADER
        pageHeader("satellite");
        
            dashboard();
            
        // PAGE FOOTER
        pageFooter();

    // DATABASE CLOSE
    closeDB();

}

function dashboard() {

	global $app;
	global $userGroupID;	

    echo "  <body>\n";
    
	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";

	// PAGE HEADER	
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . translate("SatelliteDashboard") . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0'>" . translate("SatelliteDashboardDescription") . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-none'>\n"; 
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate("SatelliteDashboard") . "</span>\n";  
	echo "							</div>\n";	

	// TOGGLER
	echo "							<a href='#' class='header-elements-toggle text-default d-md-none'><i class='icon-more'></i></a>\n";
	
	echo "						</div>\n"; 
 
	echo "						<div class='header-elements d-none'>\n";
	
	echo "							<div class='breadcrumb justify-content-center'>\n";
	
	// BREAD CRUMB RIGHT PULLDOWN
	if (getPermission("View", "UsersReports")) { 
		echo "								<a href='#' class='breadcrumb-elements-item dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2 mr-2'></i>" . translate("Reports") . "</a>\n"; 
		echo "								<div class='dropdown-menu dropdown-menu-right'>\n"; 
		echo "									<a href='#' class='dropdown-item'><i class='icon-printer'></i> " . translate("Print") . "</a>\n"; 
		echo "									<a href='#' class='dropdown-item'><i class='icon-file-pdf'></i> " . translate("ExportPDF") . "</a>\n"; 
		echo "									<a href='#' class='dropdown-item'><i class='icon-file-excel'></i> " . translate("ExportExcel") . "</a>\n"; 
		echo "									<div class='dropdown-divider'></div>\n"; 
		echo "									<a href='#' class='dropdown-item'><i class='icon-gear'></i> 123... 4</a>\n"; 
		echo "								</div>\n";
	}
	
	echo "							</div>\n"; // / breadcrumb
	
	echo "						</div>\n"; // / header-elements
	
	echo "					</div>\n"; // / breadcrumb-line

	echo "				</div>\n"; // / page-header		
	
	echo "				<div class='content'>\n";
    
    echo "                  <div class='row'>\n";
    
    echo "                      <div class='col-lg-4 sat-widget'>\n";

    echo "                          <img id='chart1' src='../../assets/images/preloaders/64x64/loader.gif' style='sat-widget-img' /></i>\n";

    echo "                      </div>\n";
    
    echo "                      <div class='col-lg-4 sat-widget'>\n";

    echo "                          <img id='chart2' src='../../assets/images/preloaders/64x64/loader.gif' style='sat-widget-img' />\n";

    echo "                      </div>\n";

    echo "                      <div class='col-lg-4 sat-widget'>\n";

    echo "                          <img id='chart3' src='../../assets/images/preloaders/64x64/loader.gif' style='sat-widget-img' />\n";

    echo "                      </div>\n";    
    
    echo "                  </div>\n";// / row
    
    echo "                  <div class='row'>\n";
    
    echo "                      <div class='col-lg-8 sat-widget'>\n";

    echo "                          <img id='chart4' src='../../assets/images/preloaders/64x64/loader.gif' style='sat-widget-img' />\n";

    echo "                      </div>\n";
    
    echo "                      <div class='col-lg-4 sat-widget'>\n";

    echo "                          <img id='chart5' src='../../assets/images/preloaders/64x64/loader.gif' style='sat-widget-img' />\n";

    echo "                      </div>\n";  
    
    echo "                  </div>\n";// / row   
    
    echo "				</div>\n";// /content
    
    echo "			</div>\n";// /content-wrapper
    
    echo "		</div>\n";// /page-content  
    
    echo "  </body>\n";
	
	// SAVE LOG EVENT
	//if (!isset($_GET['a'])) { logWrite("View", "UserPermissions", "syspermissions"); }
}