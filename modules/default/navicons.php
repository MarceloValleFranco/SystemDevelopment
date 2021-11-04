<?php

// NAVICONS.PHP 1.0 (2019/01/09)

	// CHAT
	//if (getPermission("View", "Communicator")) { echo "                  <li class='nav-item'><a title='" . translate("Communicator") . "' class='navbar-nav-link menu-link' onclick=\"block('.chat-frame'); chatBox('modules/default/syschat.php', '" . translate("Communicator") . "', '" .  translate('Close') . "')\"" . $closeMobileMenu ."><i class='icon-bubble icon-size-14'></i><span class='d-md-none ml-2'>" . translate('Communicator') . "</span></a></li>\n"; }        	
	
	// FULL SCREEN TOGGLER
	if (getPermission("FullScreenToggler", "GeneralOptions")) { 
		echo "                  <li class='nav-item'><a title='" . translate("FullScreen") . "' class='navbar-nav-link full-screen'><i class='icon-screen-full'></i><span class='d-md-none ml-2'>" . translate("FullScreen") . "</span></a></li>\n";		
	}
	
	// SYSTEM CONFIG
	echo "                  <li class='nav-item dropdown'>\n";
	echo "                      <a title='" . translate("Maintenance") . "' class='navbar-nav-link dropdown-toggle' data-toggle='dropdown'><span><i class='icon-cog icon-size-14'></i><span class='d-md-none ml-2'>" . translate("Maintenance") . "</span></span></a>\n";
	echo "                      <div class='dropdown-menu dropdown-menu-right'>\n";

		// USERS MANAGER
		if (getPermission("View", "UsersManager")) { echo "						<a title='" . translate("UsersManager") . "' class='dropdown-item menu-link' id='modules/default/sysusers.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-users'></i></span>" . translate("UsersManager") . "</a>\n"; }                       

		// USER GROUPS MANAGER
		if (getPermission("View", "UsersGroupsManager")) { echo "						<a title='" . translate("UsersGroupsManager") . "' class='dropdown-item menu-link' id='modules/default/sysusersgroups.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-users4'></i></span>" . translate("UsersGroupsManager") . "</a>\n"; }                               

		// USERS PERMISSIONS
		if (getPermission("View", "UsersPermissions")) { echo "						<a title='" . translate("UsersPermissions") . "' class='dropdown-item menu-link' id='modules/default/syspermissions.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-user-lock'></i></span>" . translate("UsersPermissions") . "</a>\n"; }                               

		// USER PREFERENCES
		echo "						<a title='" . translate("UserPreferences") . "' class='dropdown-item menu-link' id='modules/default/syspreferences.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-user-check'></i></span>" . translate("UserPreferences") . "</a>\n";  			
		
		// DIVIDER
		echo "						<div class='dropdown-divider'></div>\n";

		$vfm = 0;
		
		// FILES MANAGER
		//if (getPermission("View", "FilesManager")) { $vfm = 1; echo "						<a title='" . translate("FilesManager") . "' class='dropdown-item menu-link' id='modules/default/sysfiles.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-folder'></i></span>" . translate("FilesManager") . "</a>\n"; }              

		// DIVIDER
		if ($vfm == 1) { echo "						<div class='dropdown-divider'></div>\n"; }

		$vfm = 0;

		// SYSTEM MODULES
		//if (getPermission("View", "SystemModules")) { $vfm = 1; echo "						<a title='" . translate("SystemModules") . "' class='dropdown-item menu-link' " . $closeMobileMenu . " onclick=\"parent.dWindow('" . translate("SystemModules") . "', '<iframe class=window-app-frame src=modules/default/autocrud.php?n=20></iframe>'); return false;\"><span class='dropdown-icon'><i class='icon-cube2'></i></span>" . translate("SystemModules") . "</a>\n"; }              					
		
		// SYSTEM MODULES GROUPS
		//if (getPermission("View", "SystemModulesGroups")) { $vfm = 1; echo "						<a title='" . translate("SystemModulesGroups") . "' class='dropdown-item menu-link' " . $closeMobileMenu . " onclick=\"parent.dWindow('" . translate("SystemModulesGroups") . "', '<iframe class=window-app-frame src=modules/default/autocrud.php?n=62></iframe>'); return false;\"><span class='dropdown-icon'><i class='icon-cube3'></i></span>" . translate("SystemModulesGroups") . "</a>\n"; }              	
		
		// DIVIDER
		if ($vfm == 1) { echo "						<div class='dropdown-divider'></div>\n"; }		
		
		// SYSTEM APPS
		if (getPermission("View", "SystemApps")) { $vfm = 1; echo "						<a title='" . translate("SystemApps") . "' class='dropdown-item menu-link' " . $closeMobileMenu . " onclick=\"parent.dWindow('" . translate("SystemApps") . "', '<iframe class=window-app-frame src=modules/default/autocrud.php?v=1&n=7></iframe>'); return false;\"><span class='dropdown-icon'><i class='icon-cube2'></i></span>" . translate("SystemApps") . "</a>\n"; }              					
		
		// SYSTEM APPS GROUPS
		if (getPermission("View", "SystemAppsGroups")) { $vfm = 1; echo "						<a title='" . translate("SystemAppsGroups") . "' class='dropdown-item menu-link' " . $closeMobileMenu . " onclick=\"parent.dWindow('" . translate("SystemAppsGroups") . "', '<iframe class=window-app-frame src=modules/default/autocrud.php?v=1&n=8></iframe>'); return false;\"><span class='dropdown-icon'><i class='icon-cube3'></i></span>" . translate("SystemAppsGroups") . "</a>\n"; }              	
		
		// DIVIDER
		if ($vfm == 1) { echo "						<div class='dropdown-divider'></div>\n"; }

		// SYSTEM OPTIONS
		if (getPermission("View", "SystemOptions")) { echo "						<a title='" . translate("SystemOptions") . "' class='dropdown-item menu-link' id='modules/default/sysoptions.php'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-cog'></i></span>" . translate("SystemOptions") . "</a>\n"; }  
			
	echo "                      </div>\n";
	echo "                  </li>\n";

	// SYSTEM HELP
	
	echo "                  <li class='nav-item dropdown'>\n";
	echo "                      <a title='" . translate("SystemHelp") . "' class='navbar-nav-link dropdown-toggle' data-toggle='dropdown'><span><i class='icon-help icon-size-14'></i><span class='d-md-none ml-2'>" . translate("SystemHelp") . "</span></span></a>\n";
	echo "                      <div class='dropdown-menu dropdown-menu-right'>\n";		
	
		$vsm = 0;
	
		// DOWNLOAD INSTRUCTIONS MANUAL
		if (getPermission("View", "SystemManual")) { 
		
			$vsm = 1;
		
			// ONLINE DYNAMIC MANUAL
			//$sysManual = SYSMANUALURL; if (right($sysManual, 1) != "/") { $sysManual .= "/"; }
			//$file_headers = @get_headers($sysManual);
			//if((!$file_headers) || ($file_headers[0] == 'HTTP/1.1 404 Not Found')) {
			//    $exists = false;
			//} else {
			//	echo "						<a href='" . $sysManual . '?l=' . LICENSECODE . "&d=" . LICENSEDATA . "&s=" . $_SERVER['HTTP_HOST'] . "&t=pdf' target='_blank'><i class='icon-file-text2 drop-down-icon'></i>" . translate("DownloadManual") . "</a></li>\n";
			//}
			
			// MANUAL IN FILE MANAGER
			echo "						<a title='" . translate("OperationManuals") . "' class='dropdown-item menu-link' id='modules/default/sysfiles.php?dir=Manual'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-file-text2'></i></span>" . translate("OperationManuals") . "</a>\n";				
		}
	
		// DIVIDER
		if ($vsm == 1) { echo "						<div class='dropdown-divider'></div>\n"; }

		$dwa = 0;
		
		// APP APPLE DOWNLOAD
		if (getPermission("Download", "IOSApp")) { $dwa = 1; echo "						<a title='" . translate("IOSApp") . "' class='dropdown-item menu-link' id='files/Apps/iot-messages.apk' target='#'" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-apple2'></i></span>" . translate("IOSApp") . "</a>\n"; }        

		// APP ANDROID DOWNLOAD
		if (getPermission("Download", "AndroidApp")) { $dwa = 1; echo "						<a title='" . translate("AndroidApp") . "' class='dropdown-item menu-link' id='files/Apps/iot-messages.apk' target='#'><span class='dropdown-icon'><i class='icon-android'></i></span>" . translate("AndroidApp") . "</a>\n"; }        

		// DIVIDER
		if ($dwa == 1) { echo "						<div class='dropdown-divider'></div>\n"; }

		// SYSTEM ABOUT
		echo "						<a title='" . translate("SystemAbout") . "' class='dropdown-item' onclick=\"block('.modal-body'); aboutBox('modules/default/sysabout.php', '" . translate("SystemAbout") . "', '" .  translate('Close') . "')\"" . $closeMobileMenu . "><span class='dropdown-icon'><i class='icon-info3'></i></span>" . translate("SystemAbout") . "</a>\n";        
	
	echo "                      </div>\n";
	echo "                  </li>\n";