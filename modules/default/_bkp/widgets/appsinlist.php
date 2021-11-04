<?php

// APPSINLIST.PHP 1.0 (2019/01/08)

	// EACH GROUP OF APPS
	if ($d = select("*", "sysappsgroups", "where (CompanyID='" . COMPANYID . "' and ShowOnMobile='1' and ItemActive<>'0') order by ItemOrder, ItemName")) {
	
		echo "					<div class='apps-in-list'>\n";
		
		foreach ($d as $r) {     

			$app = "						<div class='group-title-container'><h6 class='group-title font-weight-semibold'>" . translate($r['ItemName'])  . "</h6></div>\n";
			
			$app .="							<div class='thumbnail-container'>\n";

			$count = 0;	
			
			// EACH APP IN THIS GROUP
			if ($e = select("*", "sysapps", "where (CompanyID='" . COMPANYID . "' and ShowOnMobile='1' and AppGroupID=" . $r['ItemID']  . " and AppActive<>'0') order by ItemOrder, ItemName")) {
			
				foreach ($e as $s) {
					
					if (getPermission("View", $s["ItemName"])) {

						$inactive = ''; if ($s["AppActive"] == "2") { $inactive = " suspended"; }
						
						$app .="								<div class='thumbnail" . $inactive . "'";
						
						if (trim($s["ItemURL"]) != '') { 
						
							if (strpos($s["ItemURL"], "autocrud")) {
								
								$link = $s["ItemURL"] . "?n=" . $s["ItemID"];
								
							} else {
								
								$link =  $s["ItemURL"];

							}
						
							if (left($link, 4) == "apps") { $link = str_replace("apps/", "../../apps/", $link); } else { $link = str_replace("modules/", "../", $link); }
							
							$app .=" block('.page-content'); onclick=\"window.location.href=('" . $link . "')\"";
						
						}
						$app .=">\n";			
						$app .="								<div class='thumbnail-icon'><i class='icon-" . $s["ItemIcon"] . " icon-size-48'></i></div>\n";
						$app .="								<div class='thumbnail-title'>" . translate($s["ItemName"]) . "</div>\n";
						$app .="							</div>\n";
						
						$count ++;
					
					}
				}			
			}

			$app .="						</div>\n"; // /thumbnail-container

			if ($count > 0) { echo $app; }
			
			$count = 0;
			
		}
		
		echo "					</div>\n";

	}