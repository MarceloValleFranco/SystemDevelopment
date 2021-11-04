<?php

// NAVICONS.PHP 1.0 (2019/11/31)

		// LOCATIONS
		if (getPermission("View", "LocationsManager")) { 
        
			echo "                  <li class='nav-item dropdown'>\n";
			echo "                      <a title='" . translate("Locations") . "' class='navbar-nav-link dropdown-toggle' data-toggle='dropdown'><span><i class='icon-location3 icon-size-14'></i><span class='d-md-none ml-2'>" . translate("Locations") . "</span></span></a>\n";
			echo "                      <div class='dropdown-menu dropdown-menu-right'>\n";

				$d = select("*", "applocations", "where ItemActive='1' order by ItemName");
                
				if ($d) {
                    
					foreach ($d as $r) {
                        
						echo "						<a title='" . $r["ItemName"] . "' class='dropdown-item menu-link' id='apps/locations/index.php?m=" . $r["ItemID"] . "'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-map5'></i></span>" . $r["ItemName"] . "</a>\n";                       
					}
				}
	   
			echo "                      </div>\n";
			echo "                  </li>\n";
		}