<?php

// SYSPREFERENCES.PHP 1.0 (2018/12/04)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// DATABASE OPEN
openDB();

    // PAGE HEADER
    pageHeader("syspreferences");
    
        $m = '';
        if (isset($_POST['s'])) { if (!DEMO) { itemSave($m); } }
        itemList($m);
        
    // PAGE FOOTER
    pageFooter();

// DATABASE CLOSE
closeDB();

function itemList($m) {

	global $app;	

    echo "  <body>\n";
    
    // PLUGINS
	echo addCode("../../core/plugins/forms/selects/select2.min.js");
    echo addCode("../../core/plugins/forms/styling/uniform.min.js");
    echo addCode("../../core/plugins/forms/styling/switchery.min.js");

    // CONFIRM BOX
    confirmBox();

    // RESTART NEEDED
    $needRestart = '';
    if (isset($_POST['r'])) { echo "		<script>confirmBox();</script>\n"; }  	
    
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
	echo "							<h4><span class='font-weight-semibold'>" . translate("UserPreferences") . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0'>" . translate("UserPreferencesDescription") . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		// UPDATE PREFERENCES
		if (getPermission("Update", "UserPreferences")) { echo "							<button type='button' class='btn btn-success ml-2 confirm' disabled><i class='icon-check'></i><span class='button-text'>" . translate("UpdatePreferences") . "</span></button>\n"; }

	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate("UserPreferences") . "</span>\n";  
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
    echo "					<form method='post' class='form-horizontal preferencesForm' accept-charset='UTF-8' autocomplete='off'>\n";
    
	// HIDDEN FIELDS
    echo "						<input type='hidden' name='s' value='1' />\n";
	echo "						<input type='hidden' name='p' value='" . $activePanel . "' id='activePanel' />\n";
	
    // PREFERENCE GROUPS
    $j = 0; $column = 1; $groupCount = 0; $groupName = array(); $groupIcon = array(); $preferenceCount = 0; $temp = ''; $formRestart = '';
	
    if ($d = select("*", "syspreferences", " where (CompanyID='" . COMPANYID . "' and PreferenceActive='1') order by PreferenceGroup")) {
    
		foreach ($d as $r) {
			
			if ($temp != $r['PreferenceGroup']) {
				$groupCount++;
				$groupName[$groupCount] = $r['PreferenceGroup']; $groupIcon[$groupCount] = $r['PreferenceIcon'];
				$temp = $r['PreferenceGroup'];
			}
			
		}
      
    } else {
    
        // NO PREFERENCE GROUPS
	    echo "					<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";		
        
    } 	
    
    echo "						<div class='row'>\n";
    echo "							<div class='col-lg-3'>\n";
    echo "								<div class='card-group-control card-group-control-right' id='accordion-control-right" . $column . "'>\n";
    
	// PREFERENCES LOOP	
	if ($groupCount > 0) {
	
	   for ($c = 1; $c <= $groupCount; $c++) {
		   
		    $j++;
		   
			if ($j == 5) {
				echo "								</div>\n";// /card-group-control
				echo "							</div>\n";// /col-lg-3
				$column++;
				echo "							<div class='col-lg-3'>\n";
				echo "								<div class='card-group-control card-group-control-right' id='accordion-control-right" . $column . "'>\n";
			}
               
            $icon = "cog"; if ($groupIcon[$c] != '') { $icon = $groupIcon[$c]; }			
    
            echo "									<div class='card' onclick=\"document.getElementById('activePanel').value='" . $c . "'\">\n";
            
            echo "										<div class='card-header'>\n";
            echo "											<h6 class='card-title'><i class='icon-" . $icon . " mr-2 width-20'></i> <a class='"; if ($c != $activePanel) { echo "collapsed "; } echo "text-default' data-toggle='collapse' href='#accordion-control-right-group" . $c . "'>" .  translate($groupName[$c]) . "</a></h6>\n";
            echo "										</div>\n";
            
            echo "										<div id='accordion-control-right-group" . $c . "' class='collapse"; if ($c == $activePanel) { echo " show"; } echo "' data-parent='#accordion-control-right" . $column . "'>\n";
            echo "											<div class='card-body'>\n";
			
                // EACH PREFERENCE
                if ($e = select("*", "syspreferences", "where (CompanyID='" . COMPANYID . "' and PreferenceGroup='" . $groupName[$c]. "' and PreferenceActive='1') order by PreferenceOrder")) {
    
                    foreach ($e as $s) {
    
                        $preferenceCount++; 
                        
                        if ($s['PreferenceCheckBox'] == '1') {
							
							// IF CHECKBOX
							echo "											<table>\n";
							echo "												<tr>\n";
							echo "													<td class='td-name'>" . translate($s['PreferenceName']) . "</td>\n";
							echo "													<td class='td-switch'>\n";
							echo "														<div class='switchery-sm'>\n";
							echo "															<input type='checkbox' class='switchery switch" . $preferenceCount . "'"; if ($s['PreferenceValue'] == '1') { echo " checked='checked'"; } echo " />\n";
							echo "															<input type='hidden' id='" . $s['PreferenceName'] . "' name='" . $s['PreferenceName'] . "' value='" . $s['PreferenceValue'] . "' />\n";
							echo "														</div>\n";
							echo "													</td>\n";
							echo "												</tr>\n";
							echo "											</table>\n";
							echo "											<script>$(function () { $('.switch" . $preferenceCount . "').change(function() { var v = $('.switch" . $preferenceCount . "').prop('checked'); if (v) { v = '1'; } else { v = '0'; } $('#" . $s['PreferenceName'] . "-" . $s['PreferenceName'] . "').val(v); }).change(); })</script>\n";
						
                        } elseif ($s['PreferenceSelect'] == '1') {
                        
							// IF SELECT
							echo "											<div class='form-group'>\n";
							echo "												<label class='control-label'>" . translate($s['PreferenceName']) . "</label>\n";
							echo "												<select class='form-control form-control-select2 preference-select' name='" . $s['PreferenceName'] . "'>\n";
							echo "													<option value='Default'"; if ($s['PreferenceValue'] == 'Default') { echo " selected"; } echo ">Padrão</option>\n";
							echo "													<option value='Ilustre'"; if ($s['PreferenceValue'] == 'Ilustre') { echo " selected"; } echo ">Ilustre</option>\n";
							echo "													<option value='Dark'"; if ($s['PreferenceValue'] == 'Dark') { echo " selected"; } echo ">Dark Theme</option>\n";
							echo "												</select>\n";       
							echo "											</div>\n";
	
                        } elseif ($s['PreferenceTextArea'] == '1') {
                        
							// IF TEXTAREA
							echo "											<div class='form-group'>\n";
							echo "												<label class='control-label'>" . translate($s['PreferenceName']) . "</label>\n";
							echo "												<div><textarea class='form-control' name='" . $s['PreferenceName'] . "'>" . $s['PreferenceValue'] . "</textarea></div>\n";
							echo "											</div>\n";
						
						} else {
							
							// IF TEXTBOX
							echo "											<div class='form-group'>\n";
							echo "												<label class='control-label'>" . translate($s['PreferenceName']) . "</label>\n";
                            echo "												<div><input type='text' class='form-control' name='" . $s['PreferenceName'] . "' value='" . $s['PreferenceValue'] . "' maxlength='250' /></div>\n";
							echo "											</div>\n";
						}
                        
						// IF PREFERENCE NEEDS RESTART
						if ($s['PreferenceNeedRestart'] == '1') { $formRestart = '1'; }
                    }
    
                } else {
    
                    // NO PREFERENCES
					echo "												<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";
        
                }               
            
            echo "											</div>\n";// /card-body
            echo "										</div>\n";// /accordion-control-right-group
            
            echo "									</div>\n";// /card
        
        }
		
	}	
    
    echo "								</div>\n";// /card-group-control
    echo "							</div>\n";// /col-lg-6
    echo "						</div>\n";// / row
	
	// RESTART HIDDEN FIELD
	if ($formRestart == '1') { echo "						<input type='hidden' name='r' value='1' />\n"; }
   
    echo "					</form>\n";     
   
    // DEMO MESSAGE
    if (DEMO) { echo "					<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; }    
    

    echo "				</div>\n";// /content
    echo "			</div>\n";// /content-wrapper
    echo "		</div>\n";// /page-content  
    
    echo "  </body>\n";
	
	// SAVE EVENT
	if (!isset($_GET['a'])) { logWrite("View", "SystemPreferences", "syspreferences"); }
}

function itemSave($m) {
	
    // DEBUG
    //foreach(array_reverse($_POST) as $key=>$value) { echo ">" . $key . " = [" . $value . "]<br />\n"; }; exit;	
   
    foreach($_POST as $key=>$value) { 

        if ($key != 's' && $key != 'p') {
		
            $t = array();        
            $t['PreferenceValue'] = $value;
        
            // GET PREFERENCE ID
            if ($z = select("PreferenceID", "syspreferences", "where (CompanyID='" . COMPANYID . "' and PreferenceName='" . $key . "')")) {
			
				$i = $z[0]["PreferenceID"];
            
				// UPDATE PREFERENCE VALUE
				if (update("syspreferences", $t, "where PreferenceID=" . $i)) { logWrite("Update", "UserPreferences", "syspreferences", $i, $key . "=" . $value); $m = "UpdatePreferencesOk"; }

			}
        }
    }
}

function confirmBox() {

    // RESTART BOX
    echo addCode("../../core/plugins/notifications/bootbox.min.js");
    
    echo "		<script>\n";
    echo "			function confirmBox() {\n";
    echo "              bootbox.dialog({ message: '" . translate('ConfirmRestart') . " ?', title: '<b>" . translate('RestartApp') . "</b>', buttons: {\n";
    echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i> " . translate('Cancel') . "', className: 'btn-default' },\n";
    echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i> " . translate('Confirm') . "', className: 'btn-danger', callback: function() { window.top.location.reload(); } }\n";
    echo "              }});\n";
    echo "			}\n";
    echo "		</script>\n";

}