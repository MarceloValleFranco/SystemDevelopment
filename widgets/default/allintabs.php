<?php

// ALLINTABS.PHP 1.0 (2019/11/07)

	// ADD CSS
	if ($loadStyle == 0) { echo "						" . addCode("../../widgets/default/assets/css/allintabs.css"); $loadStyle = 1; } 
	
	// ADD MORE CSS
	echo "								<style>.all-in-tabs{ width:" . $itemWidth . "px }</style>\n";

	// EACH GROUP OF MODULES
	$d = select("*", "sys" . $mode . "groups", "where (CompanyID='" . COMPANYID . "' and ShowOnDesktop='1' and ItemActive<>'0') order by ItemOrder, ItemName");
    
	if ($d) {
		
		// CARD
		echo "								<div class='card all-in-tabs'>\n";
		
		// CARD HEADER
		$title = "SystemApps"; if (strtolower($mode) == "modules") { $title = "SystemModules"; }
		echo "									<div class='card-header header-elements-inline'>\n"; 
		echo "										<h5 class='card-title'>" . translate($title) . "</h5>\n"; 
		// echo "										<div class='header-elements'>\n"; 
		// echo "											<div class='list-icons'>\n"; 
		// echo "												<a class='list-icons-item' data-action='collapse'></a>\n"; 
		// echo "												<a class='list-icons-item' data-action='reload'></a>\n"; 
		// echo "												<a class='list-icons-item' data-action='remove'></a>\n"; 
		// echo "											</div>\n"; 
		// echo "										</div>\n"; 
		echo "									</div>\n"; 

		// CARD BODY
		echo "									<div class='card-body all-in-tabs'>\n";
		
		echo "										<div class='d-md-flex'>\n";

		echo "											<ul class='nav nav-tabs nav-tabs-vertical flex-column border-bottom-0'>\n";

		// MODULES GROUPS MENU
		$c = 0;
		$g = array();
		$tab = ''; if (isset($_GET["t"])) { $tab = $_GET["t"]; }
		$last = '';
		foreach ($d as $r) {        
			$c++;
			echo "												<li class='nav-item'>\n";
			echo "													<a href='#tab-" . $z["ItemName"] . "-" . $r["ItemID"] . "' data-toggle='tab' class='nav-link";
			if ($c == 1) { if ($tab == '') { echo " active"; } } 
			if ($tab == $r["ItemName"]) { echo " active"; }
			echo "'>\n";
			echo "														<span class='badge bg-grey-800 mr-2 tabs-badge'><i class='icon-" . $r["ItemIcon"] . "'></i></span>\n";
			echo "														<span class='tab-section'>" . translate($r["ItemName"]) . "</span>\n";
			echo "													</a>\n";
			echo "												</li>\n";
			$g[$c] = $r["ItemID"];
		}	

		echo "											</ul>\n";

		echo "											<div class='tab-content' id='tab-content'>\n";

		// MODULES GROUPS CONTENT
		for ($x = 1; $x <= count($d); $x++) {

			echo "												<div class='tab-pane fade show";
				if ($x == 1) { if ($tab == '') { echo " active"; } } 
				if ($tab == $g[$x]) { echo " active"; }
			echo "' id='tab-" . $z["ItemName"] . "-" . $g[$x] . "'>\n";
			echo "													<div class='thumbnail-tabs-container'>\n";

			// EACH MODULE IN THIS GROUP
			$m = "App"; $l = ''; if (strtolower($mode) == "modules") { $m = "Module"; $l = "v=1&"; }
			
			$e = select("*", "sys" . $mode, "where (CompanyID='" . COMPANYID . "' and " . $m . "GroupID='" . $g[$x] . "' and ShowOnDesktop='1' and " . $m . "Active<>'0') order by ItemOrder, ItemName");
			
			if ($e) {

				foreach ($e as $s) {
					
					if (getPermission("View", $s["ItemName"])) {

						$inactive = ''; if ($s[$m . "Active"] == "2") { $inactive = " suspended"; }
						
						echo "														<div class='thumbnail-tabs" . $inactive . "'>\n";
						
						$link = ''; $aLink = ''; $nLink = ''; $noShow = '';
						
						if ($s[$m . "Active"] == "1") { 
						
							if (trim($s["ItemURL"]) != '') {
								
								if (strpos($s["ItemURL"], "autocrud")) {
									
									$link = $s["ItemURL"] . "?" . $l . "n=" . $s["ItemID"];
									
								} else {
									
									$link = $s["ItemURL"];
                                    if ($s["AddUserID"] ) { $link .= "?userid=" . USERID; }

								}
								
								$aLink = " onclick=\"parent.dWindow('" . translate($s["ItemName"]) . "', '<iframe class=window-app-frame src=" . $link . "></iframe>'); return false;\"";
									
								if (strtolower($mode) == "modules") {
									
									if (left($link, 7) == "modules") { $link = str_replace("modules/", "../../modules/", $link); }
									
								} else {
									
									if (left($link, 4) == "apps") { $link = str_replace("apps/", "../../apps/", $link); } else { $link = str_replace("modules/", "../", $link); }
									
								}
								
								$nLink = " onclick=\"block('.page-content'); window.location.href=('" . $link . "')\"";
							
							} else {
								
								$noShow = " display-none";
							}
							
						} else {
								
							$noShow = " display-none";
						}

						echo "															<div class='thumbnail-tabs-link'" . $aLink . ">\n";
						echo "																<span class='icon-popout" . $noShow . "'></span>\n";
						echo "															</div>\n";						
						echo "															<div class='thumbnail-tabs-body'" . $nLink . ">\n";
						echo "																<div class='thumbnail-tabs-icon'><i class='icon-" . $s["ItemIcon"] . " icon-size-48'></i></div>\n";
						echo "																<div class='thumbnail-tabs-title'>" . translate($s["ItemName"]) . "</div>\n";
						echo "															</div>\n";
						
						echo "														</div>\n";// /thumbnail-tabs
					
					}
				}
			
			}
			
			echo "													</div>\n"; // /thumbnail-tabs-container
			
			echo "												</div>\n"; // /tab-pane
		}

		echo "											</div>\n"; // tab-content

		echo "										</div>\n"; // /d-md-flex
		
		echo "									</div>\n"; // /card-body
		
		echo "								</div>\n"; // /card
	}