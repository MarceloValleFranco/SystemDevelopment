<?php

// INDEX.PHP 1.0 (2019/01/09)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// DATABASE OPEN
openDB();

	pageHeader('default');

		echo "	<body>\n"; 

		echo "		<div class='page-content'>\n";
		echo "			<div class='content-wrapper'>\n";
		
		if (!ISPHONE) {
			
			if (getOptionValue("HeaderOnMainPage")) {
		
				// PAGE HEADER	
				echo "				<div class='page-header page-header-light'>\n";
				
				echo "					<div class='page-header-content header-elements-inline'>\n";
				
				// PAGE TITLE
				echo "						<div class='page-title d-flex'>\n"; 
				echo "							<h4><span class='font-weight-semibold'>" . translate("MainDashboard") . "</span>\n"; 
				echo "							<small class='d-block opacity-75 ml-0''>" . translate("MainDashboardDescription") . "</small></h4>\n";;
				echo "						</div>\n";	
			 
				// RIGHT BUTTONS
				echo "						<div class='header-elements d-flex align-items-center'>\n"; 
				
					// REFRESH
					echo "							<button type='button' class='btn btn-primary ml-2 refresh-button'><i class='icon-rotate-ccw3'></i><span class='button-text'>" . translate("Refresh") . "</span></button>\n"; 	
				 
				echo "						</div>\n";
				
				echo "					</div>\n"; 

				echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
				
				echo "						<div class='d-flex'>\n";
				
				// BREAD CRUMBS
				echo "							<div class='breadcrumb'>\n"; 
				echo "								<span class='breadcrumb-item active'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</span>\n"; 
				echo "							</div>\n";	

				// TOGGLER
				//echo "							<a href='#' class='header-elements-toggle text-default d-md-none'><i class='icon-more'></i></a>\n";
				
				echo "						</div>\n"; 
				
				echo "					</div>\n"; // / breadcrumb-line

				echo "				</div>\n"; // / page-header	
			
			}

		}
		
		// PAGE CONTENT
		echo "				<div class='content'>\n";

		// LIST OF APPS (PHONE)
		include "widgets/appsinlist.php";
		
		// GRID OF WIDGETS (DESKTOP)
		if (!ISPHONE) { widgetsGrid(); }		

		echo "				</div>\n"; // /content
		echo "			</div>\n"; // /page-wrapper
		echo "		</div>\n"; // /page-content

		echo "	</body>\n";

	pageFooter('default');

	// WRITE LOG
	logWrite("View", "DefaultPage");

// DATABASE CLOSE
closeDB();

function widgetsGrid() {
	
	echo "					<div class='widgets-grid'>\n";
	
	// MODULES WIDGET
	echo "						<div class='item' style='width:696px; height:828px'>\n";
	echo "							<div class='item-content'>\n";
		include "widgets/modulesintabs.php";
	echo "							</div>\n";
	echo "						</div>\n";	
	
	// APPLICATIONS WIDGET
	echo "						<div class='item' style='width:696px; height:828px'>\n";
	echo "							<div class='item-content'>\n";
		include "widgets/appsintabs.php";
	echo "							</div>\n";
	echo "						</div>\n";
	
	echo "					</div>\n";

	// DRAG'N DROP GRID JS 
	echo "					<script src='../../core/plugins/dragdrop/web-animations.min.js'></script>\n";
	echo "					<script src='../../core/plugins/dragdrop/hammer.min.js'></script>\n";
	echo "					<script src='../../core/plugins/dragdrop/muuri.min.js'></script>\n";

	echo "					<script> var grid = new Muuri('.widgets-grid', { dragEnabled: true }); </script>\n";	
	
}