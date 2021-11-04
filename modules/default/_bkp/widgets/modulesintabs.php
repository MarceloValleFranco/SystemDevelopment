<?php

// MODULESINTABS.PHP 1.0 (2019/01/09)

	// EACH GROUP OF MODULES
	$d = select("*", "sysmodulesgroups", "where (CompanyID='" . COMPANYID . "' and ShowOnDesktop='1' and ItemActive<>'0') order by ItemOrder, ItemName");
	if ($d) {
		
		// CARD
		echo "							<div id='apps-in-tabs' class='card apps-in-tabs'>\n";
		
		// CARD HEADER
		echo "								<div class='card-header header-elements-inline'>\n"; 
		echo "									<h5 class='card-title'>" . translate("SystemModules") . "</h5>\n"; 
		// echo "									<div class='header-elements'>\n"; 
		// echo "										<div class='list-icons'>\n"; 
		// echo "											<a class='list-icons-item' data-action='collapse'></a>\n"; 
		// echo "											<a class='list-icons-item' data-action='reload'></a>\n"; 
		// echo "											<a class='list-icons-item' data-action='remove'></a>\n"; 
		// echo "										</div>\n"; 
		// echo "									</div>\n"; 
		echo "								</div>\n"; 

		// CARD BODY
		echo "								<div class='card-body thumbnail-container'>\n";
		
		echo "									<div class='d-md-flex'>\n";

		echo "										<ul class='nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 border-bottom-0'>\n";

		// MODULES GROUPS MENU
		$c = 0;
		$g = array();
		$tab = ''; if (isset($_GET["t"])) { $tab = $_GET["t"]; }
		$last = '';
		foreach ($d as $r) {        
			$c++;
			echo "											<li class='nav-item'>\n";
			echo "												<a href='#tab-" . $r["ItemID"] . "' data-toggle='tab' class='nav-link";
			if ($c == 1) { if ($tab == '') { echo " active"; } } 
			if ($tab == $r["ItemName"]) { echo " active"; }
			echo "'>\n";
			echo "													<span class='badge bg-grey-800 mr-2' style='border-radius:4px; padding:7px 6px 6px 6px'><i class='icon-" . $r["ItemIcon"] . "'></i></span>\n";
			echo "													<span class='tab-section'>" . translate($r["ItemName"]) . "</span>\n";
			echo "												</a>\n";
			echo "											</li>\n";
			$g[$c] = $r["ItemID"];
		}	

		echo "										</ul>\n";

		echo "										<div class='tab-content' id='tab-content'>\n";

		// MODULES GROUPS CONTENT
		for ($x = 1; $x <= count($d); $x++) {

			echo "											<div class='tab-pane fade show";
				if ($x == 1) { if ($tab == '') { echo " active"; } } 
				if ($tab == $g[$x]) { echo " active"; }
			echo "' id='tab-" . $g[$x] . "'>\n";
			echo "												<div class='thumbnail-container'>\n";

			// EACH MODULE IN THIS GROUP
			if ($e = select("*", "sysmodules", "where (CompanyID='" . COMPANYID . "' and ModuleGroupID='" . $g[$x] . "' and ShowOnDesktop='1' and AppActive<>'0') order by ItemOrder, ItemName")) {

				foreach ($e as $s) {
					
					if (getPermission("View", $s["ItemName"])) {

						$inactive = ''; if ($s["AppActive"] == "2") { $inactive = " suspended"; }
						
						echo "													<div class='thumbnail" . $inactive . "'>\n";
						
						$link = ''; $aLink = ''; $nLink = ''; $noShow = '';
						
						if ($s["AppActive"] == "1") { 
						
							if (trim($s["ItemURL"]) != '') {
								
								if (strpos($s["ItemURL"], "autocrud")) {
									
									$link = $s["ItemURL"] . "?v=1&n=" . $s["ItemID"];
									
								} else {
									
									$link =  $s["ItemURL"];

								}
															
								$aLink = " onclick=\"parent.dWindow('" . translate($s["ItemName"]) . "', '<iframe class=window-app-frame src=" . $link . "></iframe>'); return false;\"";
															
								if (left($link, 4) == "apps") { $link = str_replace("apps/", "../../apps/", $link); } else { $link = str_replace("modules/", "../", $link); }
								
								$nLink = " onclick=\"block('.page-content'); window.location.href=('" . $link . "')\"";
							
							} else {
								
								$noShow = " display-none";
							}
							
						} else {
								
							$noShow = " display-none";
						}

						echo "														<div class='thumbnail-link'" . $aLink . ">\n";
						echo "															<span class='icon-popout" . $noShow . "'></span>\n";
						echo "														</div>\n";						
						echo "														<div class='thumbnail-body'" . $nLink . ">\n";
						echo "															<div class='thumbnail-icon'><i class='icon-" . $s["ItemIcon"] . " icon-size-48'></i></div>\n";
						echo "															<div class='thumbnail-title'>" . translate($s["ItemName"]) . "</div>\n";
						echo "														</div>\n";
						
						echo "													</div>\n";// /thumbnail
					
					}
				}
			
			}
			
			echo "												</div>\n"; // /thumbnail-container
			echo "											</div>\n"; // /tab-pane
		}

		echo "										</div>\n"; // tab-content

		echo "									</div>\n"; // /d-md-flex
		echo "								</div>\n"; // /card-body
		
		echo "							</div>\n"; // /card
	}