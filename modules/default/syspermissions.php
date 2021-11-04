<?php

// SYSPERMISSIONS.PHP 1.0 (2018/12/04)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// DATABASE OPEN
openDB();

	// USER GROUP ID
	if (isset($_GET["g"])) { $userGroupID = $_GET["g"]; } else { if (USERGROUPID == '1') { $userGroupID = (USERGROUPID); } else { $userGroupID = (USERGROUPID + 1); } }

    // PAGE HEADER
    pageHeader("syspermissions");
    
        $m = '';
        if (isset($_POST['s'])) { if (!DEMO) { itemSave($userGroupID); } }
        itemList($m);
        
    // PAGE FOOTER
    pageFooter();

// DATABASE CLOSE
closeDB();

function itemList($m) {

	global $app;
	global $userGroupID;	

    echo "  <body>\n";
    
    // PLUGINS
	echo addCode("../../core/plugins/forms/selects/select2.min.js");
    echo addCode("../../core/plugins/forms/styling/uniform.min.js");
    echo addCode("../../core/plugins/forms/styling/switchery.min.js");	

    // INITIAL PANEL
    $activePanel = '1';
    if (isset($_POST['p'])) { $activePanel = $_POST['p']; }

	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";

	// PAGE HEADER	
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . translate("UsersPermissions") . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0'>" . translate("UsersPermissionsDescription") . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-none'>\n"; 
	
		// GROUP COMBO
		if ($d = select("*", "sysusersgroups", "where (CompanyID='" . COMPANYID . "' and UserGroupActive='1')")) {
			echo "							<form action='#'>\n";
			echo "								<div class='form-group'>\n";
			echo "									<select class='form-control user-group-id' name='UserGroupID'>\n";
			foreach ($d as $r) {
				if (($r["UserGroupID"] > USERGROUPID) || USERGROUPID == '1') {
					echo "												<option value='" . $r["UserGroupID"] . "'"; if ($userGroupID == $r["UserGroupID"]) { echo " selected"; } echo "> " . translate($r["UserGroupName"]) . " </option>\n";
				}
			}
			echo "									</select>\n";
			echo "								</div>\n";
			echo "							</form>\n";
			echo "							<script>$('.user-group-id').select2({ minimumResultsForSearch: Infinity });</script>\n";
		}		
	
		// UPDATE PERMISSIONS
		if (getPermission("Update", "UsersPermissions")) { echo "							<button type='button' class='btn btn-success ml-2 confirm' disabled><i class='icon-check'></i><span class='button-text'>" . translate("UpdatePermissions") . "</span></button>\n"; }

	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate("UsersPermissions") . "</span>\n";  
	echo "							</div>\n";	

	// TOGGLER
	echo "							<a href='#' class='header-elements-toggle text-default d-md-none'><i class='icon-more'></i></a>\n";
	
	echo "						</div>\n"; 
 
	echo "						<div class='header-elements d-none'>\n";
	
	echo "							<div class='breadcrumb justify-content-center'>\n";
	
	// BREAD CRUMB RIGHT BUTTONS
	if (getPermission("View", "UsersGroupsManager")) { echo "								<a href='#' class='breadcrumb-elements-item'><i class='icon-users4 mr-2'></i>" . translate("UsersGroupsManager") . "</a>\n"; }
	if (getPermission("View", "UsersPermissions")) { echo "								<a href='#' class='breadcrumb-elements-item'><i class='icon-user-lock mr-2'></i>" . translate("UsersPermissions") . "</a>\n"; }
	
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
	
	// MAIN CONTENT
	echo "				<div class='content'>\n";
  
    // MESSAGE
    if ($m != '') { 
        $mType = "success"; if (isset($_GET['t'])) { $mType = "danger"; }
	    echo "					<div class='alert alert-" . $mType . " border-0'>\n";
	    echo "						<button type='button' class='close' data-dismiss='alert'><span>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n";
	    echo "						" . translate($m) . "\n";
	    echo "					</div>\n";
    }       
	  
	// FORM
    echo "					<form method='post' class='form-horizontal permissionsForm' accept-charset='UTF-8' autocomplete='off'>\n";
    
	// HIDDEN FIELDS
    echo "						<input type='hidden' name='s' value='1' />\n";
	echo "						<input type='hidden' name='p' value='" . $activePanel . "' id='activePanel' />\n";
	
    // ACTION GROUPS
    $j = 0; $column = 1; $groupCount = 0; $groupName = array(); $groupIcon = array(); $actionCount = 0; $temp = ''; $formRestart = '';
	
    if ($d = select("*", "syspermissions", "where (CompanyID='" . COMPANYID . "' and UserGroupID=" . $userGroupID . ") order by ActionGroup")) {
    
		foreach ($d as $r) {
			
			if ($temp != $r['ActionGroup']) {
				if (checkApp($r['ActionGroup'])) { $groupCount++; $groupName[$groupCount] = $r['ActionGroup']; }
				$temp = $r['ActionGroup'];
			}
			
		}
      
    } else {
    
        // NO PERMISSION GROUPS
		echo "					<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";		
        
    } 	
    
    echo "						<div class='row'>\n";
    echo "							<div class='col-lg-3'>\n";
    echo "								<div class='card-group-control card-group-control-right' id='accordion-control-right" . $column . "'>\n";
    
	// PERMISSIONS LOOP	
	if ($groupCount > 0) {
	
	   for ($c = 1; $c <= $groupCount; $c++) {
		   
		    $j++;
		   
			if ($j == 5) {
				echo "								</div>\n";// /card-group-control
				echo "							</div>\n";// /col-lg-3
				$column++; 
				echo "							<div class='col-lg-3'>\n";
				echo "								<div class='card-group-control card-group-control-right' id='accordion-control-right" . $column . "'>\n";
				$j = 1;
			}
               
            $icon = "cog"; //if ($groupIcon[$c] != '') { $icon = $groupIcon[$c]; }			
    
            echo "									<div class='card' onclick=\"document.getElementById('activePanel').value='" . $c . "'\">\n";
            
            echo "										<div class='card-header'>\n";
            echo "											<h6 class='card-title'><i class='icon-" . $icon . " mr-2 width-20'></i> <a class='"; if ($c != $activePanel) { echo "collapsed "; } echo "text-default' data-toggle='collapse' href='#accordion-control-right-group" . $c . "'>" .  translate($groupName[$c]) . "</a></h6>\n";
            echo "										</div>\n";
            
            echo "										<div id='accordion-control-right-group" . $c . "' class='collapse"; if ($c == $activePanel) { echo " show"; } echo "' data-parent='#accordion-control-right" . $column . "'>\n";
            echo "											<div class='card-body'>\n";
			
                // EACH ACTION
                if ($e = select("*", "syspermissions", "where (CompanyID='" . COMPANYID . "' and ActionGroup='" . $groupName[$c] . "' and UserGroupID=1) order by ActionCode")) {
    
                    foreach ($e as $s) {
    
                        $actionCount++; 
                        
                        echo "												<table>\n";
						echo "													<tr>\n";
						echo "														<td>" . translate($s['ActionCode']) . "</td>\n";
						echo "														<td class='td-switch'>\n";
						echo "															<div class='switchery-sm'>\n";
						echo "																<input type='checkbox' class='switchery switch" . $actionCount . "'"; if ($s['Allow'] == '1') { echo " checked='checked'"; } echo " />\n";
						echo "																<input type='hidden' id='" . $s['ActionCode'] . "-" . $groupName[$c] . "' name='" . $s['ActionCode'] . "=" . $groupName[$c] . "' value='" . $s['Allow'] . "' />\n";
						echo "															</div>\n";
						echo "														</td>\n";
						echo "													</tr>\n";
						echo "												</table>\n";
						echo "												<script>$(function () { $('.switch" . $actionCount . "').change(function() { var v = $('.switch" . $actionCount . "').prop('checked'); if (v) { v = '1'; } else { v = '0'; } $('#" . $s['ActionCode'] . "-" . $groupName[$c] . "').val(v); }).change(); })</script>\n";

                    }
    
                } else {
    
                    // NO ACTIONS
                    echo "					<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";
        
                }               
            
            echo "											</div>\n";// /card-body
            echo "										</div>\n";// /accordion-control-right-group
            
            echo "									</div>\n";// /card
        
        }
		
	}	
    
    echo "								</div>\n";// /card-group-control
    echo "							</div>\n";// /col-lg-3
    echo "						</div>\n";// / row
	  
    echo "					</form>\n";     
   
    // DEMO MESSAGE
    if (DEMO) { echo "					<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; }  

    echo "				</div>\n";// /content
    echo "			</div>\n";// /content-wrapper
    echo "		</div>\n";// /page-content  
    
    echo "  </body>\n";
	
	// SAVE LOG EVENT
	if (!isset($_GET['a'])) { logWrite("View", "UserPermissions", "syspermissions"); }
}

function itemSave($userGroupID) {
	
    // DEBUG
    //foreach(array_reverse($_POST) as $key=>$value) { echo ">" . $key . " = [" . $value . "]<br />\n"; }; //exit;	

    global $m;
   
    foreach($_POST as $key=>$value) { 

        if ($key != 's' && $key != 'p' && $key != 'g') {
		
            $t = array();        
            $t['Allow'] = $value;
			
			$u = explode("=", $key);
        
            // GET PERMISSION ID
            if ($z = select("PermissionID", "syspermissions", "where (CompanyID='" . COMPANYID . "' and UserGroupID=" . $userGroupID . " and ActionCode='" . $u[0] . "' and ActionGroup='" . $u[1] . "')")) {
			
				$i = $z[0]["PermissionID"];
            
				// UPDATE PERMISSION VALUE
				if (update("syspermissions", $t, "where (CompanyID='" . COMPANYID . "' and UserGroupID=" . $userGroupID . " and PermissionID=" . $i . ")")) {
					
					// SAVE LOG EVENT
					logWrite("Update", "UserPermissions", "syspermissions", $i, $userGroupID . "|" . $u[0] . "|" . $u[1] . "=" . $value);
					
					$m = "UpdatePermissionsOk";
				}

			} else {
				
				$t['ActionCode'] = $u[0];
				$t['ActionGroup'] = $u[1];
				$t['UserGroupID'] = $_POST['g'];
				
				// ADD PERMISSION VALUE
				if (insert("syspermissions", $t, '', 1)) {
					
					// SAVE LOG EVENT
					logWrite("Update", "UserPermissions", "syspermissions", $i, $u[0] . "|" . $u[1] . "=" . $value);
					
					$m = "InsertPermissionsOk";
				}				
				
			}
        }
    }
}