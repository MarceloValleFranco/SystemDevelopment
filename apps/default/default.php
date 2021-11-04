<?php

// INDEX.PHP 1.0 (2019/11/05)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// DATABASE OPEN
openDB();

	pageHeader('default');

		echo "	<body class='desk-body'>\n"; 
		
		// WIDGET DRAG'N DROP GRID JS 
		if (!ISPHONE) { 
			echo addCode("../../core/plugins/dragdrop/web-animations.min.js");
			echo addCode("../../core/plugins/dragdrop/hammer.min.js");
			echo addCode("../../core/plugins/dragdrop/muuri.min.js");
		}		

		echo "		<div class='page-content'>\n";
		echo "			<div class='content-wrapper'>\n";

		// PAGE CONTENT
		echo "				<div class='content'>\n";

		// LIST OF APPS/MODULES (PHONE)
		include "../../widgets/default/allinlist.php";
		
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
	
		$d = select("*", "syswidgets", "where (CompanyID='" . COMPANYID . "' and ShowOnDesktop='1' and WidgetActive<>'0') order by ItemOrder, ItemName");
        
		if ($d) {
			
			$loadStyle = 0;

			// EACH WIDGET
			foreach ($d as $z) {

				$itemWidth = $z["ItemWidth"];
				$mode = $z["ItemMode"]; 
				
				echo "						<div class='item " . $z["ItemCode"] . "'>\n";
				echo "							<div class='item-content'>\n";
				include "../../" . $z["ItemURL"];
				echo "							</div>\n";
				echo "						</div>\n";
				
			}
		
		}
	
	echo "					</div>\n";
	
	// WIDGET GRID ACTIVATE
	echo "					<script>var grid = new Muuri('.widgets-grid', { dragEnabled: true });</script>\n";
}