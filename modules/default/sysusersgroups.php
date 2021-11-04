<?php

// SYSUSERSGROUPS.PHP 1.0 (2018/12/16)

$avatars = "../../custom/assets/images/avatar/"; // CHECK SYSUSERS.JS

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// ACTION
$a = ""; if (isset($_GET["a"])) { $a = clean($_GET["a"]); }
$i = ""; if (isset($_GET["i"])) { $i = clean($_GET["i"]); }
$m = ""; if (isset($_GET["m"])) { $m = clean($_GET["m"]); }

// DATABASE OPEN
openDB();

    if ($a == 'u') { 

        // PHOTO UPLOAD
        fileUpload($_FILES, $i);
    
    } else {

			// PAGES THEME
			if (getPreferenceValue("BackTheme")) { $hTheme = " header-background dark"; $bTheme = " breadcrumb-background dark"; } else { $hTheme = ''; $bTheme = ''; }
	
            // PAGE HEADER
            pageHeader("sysusersgroups");

                switch ($a) {
                    case "c": itemEdit('0', '','', $i); break;
                    case "d": if (DEMO) { itemList($m); } else  { itemDelete($i); } break;
                    case "e": itemEdit($i, $m); break;
                    case "s": if (DEMO) { itemList($m); } else  { itemSave($i); } break;
                    default: itemList($m); break;
                }
        
            // PAGE FOOTER
            pageFooter();

    }

// DATABASE CLOSE
closeDB();

function itemList($m = '') {

    global $avatars;
	global $app;
	global $hTheme;
	global $bTheme;
	
	echo "	<body>\n";
	
    // CONFIRM BOX
    confirmBox(); 
	
	// PLUGINS
	echo addCode("../../core/plugins/forms/selects/select2.min.js");
	echo addCode("../../core/plugins/tables/datatables/datatables.min.js");
	echo addCode("../../core/plugins/extensions/leftcontextmenu.js");	

	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	
	// PAGE HEADER	
	echo "				<div class='page-header page-header-light" . $hTheme . "'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . translate("UsersGroupsManager") . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . translate("UsersGroupsManagerDescription") . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		// ADD NEW USER GROUP
		echo "							<button type='button' class='btn btn-success ml-2 add-button'><i class='icon-user-plus'></i><span class='button-text'>" . translate("AddUserGroup") . "</span></button>\n"; 	
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline" . $bTheme . "'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate("UsersGroupsManager") . "</span>\n";  
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
	if (getPermission("View", "UsersGroupsReport")) { 
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
        $mType = "success"; if (isset($_GET["t"])) { $mType = "danger"; }
	    echo "					<div class='alert alert-" . $mType . " border-0'>\n";
	    echo "						<button type='button' class='close' data-dismiss='alert'><span>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n";
	    echo "						" . translate($m) . "\n";
	    echo "					</div>\n";
    }   	
	
	// SQL QUERY
    if ($d = select("*", "sysusersgroups", "where (UserGroupActive<>'4' and CompanyID=1)")) {
		
		// CARD HEAD
		echo "					<div class='card'>\n";		
				
		echo "						<table class='table table-border ed table-hover datatable-highlight'>\n";

		echo "							<thead>\n"; 
		echo "								<tr>\n"; 
        echo "                                  <tr>\n";
        if (getOptionValue("UseGroupAvatar")) {
			echo "                                      <th class='d-none d-sm-table-cell'>" . translate("UserGroupAvatar") . "</th>\n";
		}
        echo "                                      <th>" . translate("UserGroupName") . "</th>\n";
        echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserGroupMail") . "</th>\n";
        echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserGroupRegisterDate") . "</th>\n";
		echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserGroupUpdateDate") . "</th>\n";
        echo "                                      <th class='status'>" . translate("Status") . "</th>\n";
        echo "                                  </tr>\n";
		echo "								</tr>\n"; 
		echo "							</thead>\n";

		echo "							<tbody>\n"; 
		
		// RECORDS LOOP
		foreach ($d as $r) { 
		
			echo "								<tr class='table-tr' id='" . $r["UserGroupID"] . "#" . $r["UserGroupName"] . "'>\n"; 

                // USER AVATAR
                if (getOptionValue("UseGroupAvatar")) {
                    $userGroupAvatar = $r["UserGroupAvatar"]; if ($userGroupAvatar == '') { $userGroupAvatar = "../../assets/images/avatar/0.png"; } else { $userGroupAvatar = $avatars . $userGroupAvatar; if (!file_exists($userGroupAvatar)) { $userGroupAvatar = "../../assets/images/avatar/0.png"; } }
                    echo "                                      <td class='d-none d-sm-table-cell'><img alt='" . $r["UserGroupName"] . "' src='" . $userGroupAvatar . "' class='user-group-avatar img-rounded' /></td>\n";
                }
                
				echo "                                      <td class='upper-text'>" . $r["UserGroupName"] . "</td>\n";
                echo "                                      <td class='d-none d-lg-table-cell'>" . $r["UserGroupMail"] . "</td>\n";

                // REGISTER DATE
                $rDate = ''; if (isDate($r["UserGroupRegisterDate"])) { $temp = date_create($r["UserGroupRegisterDate"]); $rDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='d-none d-lg-table-cell'>" . $rDate . "h</td>\n";

                // UPDATE DATE
                $uDate = ''; if (isDate($r["UserGroupUpdateDate"])) { $temp = date_create($r["UserGroupUpdateDate"]); $uDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='d-none d-lg-table-cell'>" . $uDate . "h</td>\n";
                
                // USER STATUS
                switch($r["UserGroupActive"]) {
                    case "0": $c = "danger"; $n = translate("Inactive"); break;
                    case "2": $c = "warning"; $n = translate("Suspended"); break;
					case "3": $c = "warning"; $n = translate("WaitActivation"); break;
                    default: $c = "success"; $n = translate("Active"); break;                
                }                
                echo "                                      <td><span class='badge badge-" . $c . " badge-block text-uppercase'>" . $n . "</span></td>\n";			
			
			echo "								</tr>\n";
			
		}
			
		echo "							</tbody>\n";

		echo "						</table>\n"; 
		
		echo "					</div>\n"; // /card		
        
    } else {
    
        // EMPTY GRID (NO RECORD FOUND)
	    echo "					<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";
        
    }		
	
	// DATA-TABLE RENDER
	dataTable();
	
	// DATA-TABLE RENDER
	dataTable();
	
	// CONTEXT MENU
	echo "					<div id='OptionMenu' class='context-menu display-none'>\n";
	echo "						<ul>\n";
	$edit = "View"; $icon = "eye"; if (getPermission("Edit", "UsersManager")) { $edit = "Edit"; $icon = "pencil7"; }
	if (getPermission("View", "UsersGroupsManager")) { echo "							<li id='edit'><i class='icon-" . $icon . " context-icon'></i>" . translate($edit) . "</li>\n"; }
	if (getPermission("Clone", "UsersGroupsManager")) { echo "							<li id='clone'><i class='icon-users2 context-icon'></i>" . translate('Clone') . "</li>\n"; } 
	if (getPermission("Delete", "UsersGroupsManager")) { echo "						<li id='delete'><i class='icon-user-minus context-icon'></i>" . translate('Delete') . "</li>\n"; }
	echo "						</ul>\n";
	echo "					</div>\n"; 			
	
	echo "					<script>\n"; 
	echo "						if ($('.table').length) {\n"; 
	echo "							$('tr.table-tr').contextMenu('OptionMenu', {\n"; 
	echo "								bindings: {\n"; 
	echo "									'edit': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										block('body');\n"; 
	echo "										window.location.href = ('?a=e&i=' + r[0])\n"; 
	echo "									},\n"; 
	echo "									'delete': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										confirmBox(r[0], r[1])\n"; 
	echo "									},\n"; 
	echo "									'clone': function(t) {\n"; 
	echo "										r = t.id.split('#');\n"; 
	echo "										block('body');\n"; 
	echo "										window.location.href = ('?a=c&i=' + r[0])\n"; 
	echo "									}\n"; 
	echo "								}\n"; 
	echo "							});\n"; 
	echo "						}\n"; 
	echo "					</script>\n";	

	echo "				</div>\n"; // /content
	
	echo "			</div>\n"; // /page-wrapper
	echo "		</div>\n"; // /page-content
	
	echo "	</body>\n";	
	
	// SAVE EVENT LOG
	if (!isset($_GET["a"])) { logWrite("View", "UsersGroupsManager", "sysusersgroups"); }	
	
}

function itemEdit($i, $m = '', $field = '', $clone = '', $t = '') {

    global $avatars;
	global $app;
	global $hTheme;
	global $bTheme;
    
    echo "	<body>\n";
    
    // PLUGINS
    echo addCode("../../core/plugins/forms/selects/select2.min.js");
    echo addCode("../../core/plugins/forms/styling/uniform.min.js");
    echo addCode("../../core/plugins/forms/styling/switchery.min.js");
    
    // CONFIRM BOX
    confirmBox();  
    
    // DEFAULT DATA
    $userGroupName = '';
    $userGroupMail = '';
    $userGroupActive = '1';  
    $userGroupAvatar = '';
    
    $title = translate("UserGroupCreate"); $description = translate("UserGroupCreateDescription");
    
    if ($i != '0' || $clone != '0') {
    
        $e = $i; if ($clone != '') { $e = $clone; } else { if ($i != '0') { $title = translate("UserGroupEdit"); $description = translate("UserGroupEditDescription"); } }
    
		// GET EXISTENT DATA
        if ($r = select("*", "sysusersgroups", "where UserGroupID=" . $e)) { 
        
            $userGroupName = $r[0]["UserGroupName"];
            $userGroupMail = $r[0]["UserGroupMail"];
            $userGroupActive = $r[0]["UserGroupActive"];
            
			// AVATAR
			$userGroupAvatar = $r[0]["UserGroupAvatar"];
			if (isset($_GET["d"])) {
				if ($_GET["d"] == '1') { 
					fileDelete($i, $userGroupAvatar);
					$userGroupAvatar = '';
				}
			}
        }
        
    }
    
	// SET EDITED DATA
    if (isset($_GET["UserGroupName"])) { $userGroupName = $_GET["UserGroupName"]; }
    if (isset($_GET["UserGroupMail"])) { $userGroupMail = $_GET["UserGroupMail"]; }
    if (isset($_GET["UserGroupActive"])) { $userGroupActive = $_GET["UserGroupActive"]; }
    if (isset($_GET["UserGroupAvatar"])) { $userGroupAvatar = $_GET["UserGroupAvatar"]; }
	       
	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	
	// PAGE HEAD	
	echo "				<div class='page-header page-header-light" .$hTheme . "'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . $title . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . $description . "</small></h4>\n";
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		//CANCEL
		if (!isset($_GET["p"])) { echo "                              <button type='button' class='btn btn-default cancel-button d-none d-lg-block'><i class='icon-chevron-left pl-0 pr-0'></i><span class='button-text'>" . translate("Cancel") . "</span></button>\n"; }
	
		//DELETE
		if ($i != '0' && $i != USERID) { if (getPermission("Delete", "UsersGroupsManager")) { echo "                              <button type='button' class='btn btn-danger ml-2 delete-button' id='" . $i . "#" . $userGroupName . "'><i class='icon-user-minus'></i><span class='button-text'>" . translate("Delete") . "</span></button>\n"; } }
	
		//UPDATE
		if (isset($_GET["p"])) { 
			echo "                              <button type='button' class='btn btn-success ml-2 save-button'><i class='icon-user-check'></i><span class='button-text'>" . translate("Update") . "</span></button>\n";
		} else {
			$act = "Edit"; $caption = "Update"; if ($i == '0') { $act = "Add"; $caption = "Save"; }
			if (getPermission($act, "UsersManager")) { echo "                              <button type='button' class='btn btn-success ml-2 save-button'><i class='icon-user-check'></i><span class='button-text'>" . translate($caption) . "</span></button>\n"; }
		}		
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline" .$bTheme . "'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	if (getPermission("View", "UsersGroupsManager")) { echo "								<a href='?' class='breadcrumb-item'>" . translate("UsersManager") . "</a>\n"; } 
	echo "								<span class='breadcrumb-item active d-none d-lg-block'>" . $title . "</span>\n"; 
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
	if (getPermission("View", "UsersGroupsReport")) { 
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
        $mType = "success"; if ($t == '1') { $mType = "danger"; }
	    echo "					<div class='alert alert-" . $mType . " border-0'>\n";
	    echo "						<button type='button' class='close' data-dismiss='alert'><span>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n";
	    echo "						" . translate($m) . "\n";
	    echo "					</div>\n";
    } 
    
    echo "					<div class='card'>\n";
	
	echo "						<div class='card-body'>\n";
	
	// LEFT SIDE
	echo "							<div class='row'>\n";
	
	// PERSONAL DATA
	echo "								<div class='col-md-6'>\n";
	//echo "									<fieldset>\n";
	//echo "										<legend class='font-weight-semibold'><i class='icon-user mr-2'></i>" . translate("PersonalData") . "</legend>\n";
	
	echo "										<form method='get' class='form-vertical' accept-charset='UTF-8' autocomplete='off'>\n";
	
		// HIDDEN FIELDS
        echo "											<input type='hidden' name='a' value='s' />\n";
        echo "											<input type='hidden' name='i' value='" . $i ."' />\n";
		echo "											<input type='hidden' name='c' value='" . $e ."' />\n";
		echo "											<input type='hidden' name='z' value='" . $avatars ."' id='z' />\n";
        echo "											<input type='hidden' name='UserGroupAvatar' id='UserAvatarFile' value='" . $userGroupAvatar . "' />\n";
		
		// COMPANY ID
		echo "											<input type='hidden' name='CompanyID' value='" . COMPANYID . "' />\n";		
		
		echo "											<div class='row'>\n";
		
		// USER GROUP NAME
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserGroupName") . "</label>\n";
        echo "														<input type='text' class='form-control user-group-name' name='UserGroupName' value='" . $userGroupName . "' maxlength='40' placeholder='" . translate("UserName") . "' />\n";
        if ($field == "UserGroupName") { echo "														<span class='help-block text-danger'>" . $m . "</span>\n"; }
 
		echo "													</div>\n";
		echo "												</div>\n";
		// USER GROUP MAIL
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserGroupMail") . "</label>\n";
        echo "                                                      <input type='email' class='form-control user-group-mail' name='UserGroupMail' value='" . $userGroupMail . "' maxlength='200' placeholder='" . translate("name@server.com") . "' autocomplete='off' readonly  onfocus=\"if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus(); }\" />\n";
        if ($field == "UserGroupMail") { echo "                                                      <span class='help-block text-danger'>" . $m . "</span>\n"; } 
		echo "													</div>\n";
		echo "												</div>\n";

		echo "											</div>\n";	

		echo "											<div class='row'>\n";
		
		// USER GROUP ACTIVE
		echo "												<div class='col-md-6'>\n";
		if (getPermission("Activate", "UsersGroupsManager")) { 
			echo "													<div class='form-group'>\n";
			echo "														<label>" . translate("UserGroupActive") . "</label>\n";
			echo "                                                      <select class='form-control select-icons user-group-active' name='UserGroupActive' data-fouc>\n";
			echo "                                                          <option data-icon='user-check' value='0'"; if ($userGroupActive == '0') { echo " selected"; } echo "> " . translate("Inactive") . "</option>\n";
			echo "                                                          <option data-icon='user-block' value='1'"; if ($userGroupActive == '1') { echo " selected"; } echo "> " . translate("Active") . "</option>\n";
			echo "                                                          <option data-icon='user-lock' value='2'"; if ($userGroupActive == '2') { echo " selected"; } echo "> " . translate("Suspended") . "</option>\n";
			echo "                                                          <option data-icon='user-lock' value='3'"; if ($userGroupActive == '3') { echo " selected"; } echo "> " . translate("WaitActivation") . "</option>\n";
			echo "                                                      </select>\n";
			echo "                                                      <script>$('.user-group-active').select2({ minimumResultsForSearch: Infinity });</script>\n";  
			echo "													</div>\n";
		}
		echo "												</div>\n";		

		// FREE SPACE
		echo "												<div class='col-md-6'>\n";

		echo "												</div>\n";		
		
		echo "											</div>\n";	

	echo "										</form>\n";
		
	//echo "									</fieldset>\n";
	
	echo "								</div>\n"; // /col-md-6
	
	echo "							</row>\n";
	
    echo "						</div>\n"; // /card-body
    
    echo "					</div>\n"; // /card
	
    // DEMO MESSAGE
    if (DEMO) { echo "					<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; } 	
	
    echo "				</div>\n"; // /content
	
    echo "			</div>\n"; // /content-wrapper
    echo "		</div>\n"; // /page-container
	
    echo "	</body>\n";       
}

function itemSave($i) {

    $t = array();
    $error = '';
    $field = '';
    
    // DEBUG
    //foreach(array_reverse($_GET) as $key=>$value) { echo ">" . $key . " = [" . $value . "]<br />\n"; }; exit;    
    
    foreach(array_reverse($_GET) as $key=>$value) {
    
        if (strlen($key) > 1) {
        
			// REMOVE '
            $value = str_replace("'", "´", $value);
            
            if (is_array($value)) {
            
				// ARRAY VALUE
                $t[$key] = "#". implode("#", $value) . "#";
                
            } else {
            
                switch ($key) {
                
                    case "UserGroupMail":
                        $value = strtolower($value);
                        
                        if ($value != '') {                        
                            $t[$key] = $value;
                            
                            // CHECK VALID E-MAIL
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                                // VERIFY IF GROUP E-MAIL ALREADY EXISTS
                                if ($i == '0') {
                                    $sql = "select * from sysusersgroups where UserGroupMail='" . $value . "'";
                                    if ($r = select("UserGroupMail", "sysusersgroups", "where UserGroupMail='" . $value . "'")) { $error = translate('EmailAlreadyExists'); $field = $key; }
                                } else {
                                    if ($r = select("UserGroupMail", "sysusersgroups", "where (UserGroupMail='" . $value . "' and UserGroupID<>" . $i . ")")) { $error = translate('EmailAlreadyExists'); $field = $key; }
                                }
                                unset($r);
                            
                            } else {
                                $error = translate('InvalidEmail'); $field = $key;
                            }
                        }
                        break;                  
                    
                    case "UsersGroupName":
                        if ($value == '') {
							$error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired');
							$field = $key;
						} else {
							
							// VERIFY IF GROUP NAME ALREADY EXISTS
							if ($r = select("UserGroupName", "sysusersgroups", "where UserGroupName='" . $value . "'")) { $error = translate('NameAlreadyExists'); $field = $key; } else { $t[$key] = strtoupper($value); }
						} 
                        break;
                        
                    default: $t[$key] = $value; break;
                } 
            }
        }    
    }
    
    // DEBUG
    //print_r($t); exit;
    
    if ($error != '') {
    
		// ERROR: RETURN TO USERGROUP EDITION
        itemEdit($i, $error, $field, '', '1');
        
    } else {
    
        if ($i == '0') {

            // INSERT NEW USERGROUP
            if ($n = insert("sysusersgroups", $t)) {
				
				// GET NEW USERGROUP ID
				if ($r = select("UserGroupID", "sysusersgroups", "where CompanyID='" . COMPANYID . "' order by UserGroupID desc limit 1")) { $z = $r[0]['UserGroupID']; }
                				
				// IF ACTION IS CLONE, COPY ALL PERMISSIONS
				if ($_GET['c'] != '') { clonePermissions($z, $_GET['c']); } else { clonePermissions($z, '1'); }
				
				// SAVE LOG EVENT
				logWrite("Add", "UsersGroupsManager", "sysusersgroups", $z, $_GET["UserGroupName"]);
				
				// RETURN TO USERGROUP GRID
                header("Location: ?m=RecordedOk");
				
            } else {
				
				// ERROR: RETURN TO USERGROUP EDITION
                itemEdit($i, "ErrorSavingData", "Form");
            }          
        
        } else {    

            // UPDATE EXISTING USERGROUP
            if (update("sysusersgroups", $t, "WHERE UserGroupID=" . $i)) {
				
				// SAVE LOG EVENT
                logWrite("Update", "UsersGroupsManager", "sysusersgroups", $i, $_GET["UserGroupName"]);
				
				// RETURN TO USERGROUP LIST
                header("Location: ?m=UpdatedOk&a=e&i=" . $i);
				
            } else {
				
				// ERROR: RETURN TO USERGROUP EDITION
                itemEdit($i, "ErrorUpdatingData", "Form"); 
            }        
        
        }    
    
    }

}

function itemDelete($i) {

    if ($i != USERID) {

        $t = array();
        $t["UserGroupActive"] = "4";

		// RECORD IS NOT DELETED - JUST SET TO STATUS 4
        if (update("sysusersgroups", $t, "where UserGroupID=" . $i)) { 
    
			// SAVE LOGEVENT
            logWrite("Delete", "UsersGroupsManager", "sysusersgroups", $i);

			// RETURN TO USERGROUP LIST
            header('Location: ?a=x&m=DeletedOk');
    
		} else {
	
			// ERROR: RETURN TO USERGROUP LIST
			header('Location: ?a=x&m=DeleteError&t=1');

		}
    
    } else {

		// ERROR: RETURN TO USERGROUP LIST
        header('Location: ?m=CannotDeleteYourself&t=1');

    }
}

function fileUpload($file_array, $i) {

    global $avatars;
		
    $response = array();
    $uploaded_files = array();
            
	if (isset($file_array["uploadFile"]) && $file_array["uploadFile"]["name"] != '') {
            
		$currentfile_extension = end(@explode(".", $file_array["uploadFile"]["name"]));

        switch($currentfile_extension) {
            
            case "jpg":
            case "png":
            case "gif":
            case "jpeg":
                
                $filename = date("YmdHis") . rand(1000,9999) . "." . $currentfile_extension;
                
			    if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $avatars . $filename)) {
                
				    $response["file_name"] = $filename;
				    $response["response_html"] = $filename;
                    $response["message"] = '';
                    
					// SAVE EVENT
                    logWrite("UploadAvatar", "UsersManager", "sysusersgroups", $i, $filename);
                    
			    } else {
                
				    $response["file_name"] = '';
                    $response["response_html"] = '';
                    $response["message"] = translate("FileUploadError");
			    }
            break;
            
            default:
                $response["message"] = translate("IncompatibleFile");
                break;
        }
	}

    echo json_encode($response);
}

function fileDelete($i, $userGroupAvatar) {

	global $avatars;

	if ($userGroupAvatar != '') { if (file_exists($avatars . $userGroupAvatar)) { unlink($avatars . $userGroupAvatar); } }

    $t = array();
    $t["UserAvatar"] = '';
    if (update("sysusersgroups", $t, "where UserID=" . $i)) { 
    
        logWrite("DeleteAvatar", "UsersManager", "sysusersgroups", $i);
    
    }
}

function confirmBox() {

    ;

    // CONFIRM DELETE BOX
    echo addCode("../../core/plugins/notifications/bootbox.min.js");
    
    echo "		<script>\n";
    echo "			function confirmBox(i, n) {\n";
    echo "              bootbox.dialog({ message: '" . translate('ConfirmDelete') . " \"' + n + '\" ?', title: '<b>" . translate('Delete') . "</b>', buttons: {\n";
    echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i> " . translate('Cancel') . "', className: 'btn-default' },\n";
    echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i> " . translate('Confirm') . "', className: 'btn-danger', callback: function() { window.location.href=('?a=d&i=' + i); } }\n";
    echo "              }});\n";
    echo "			}\n";
    echo "		</script>\n";

}

function clonePermissions($i, $e) {
	
	// DEBUG
	//echo "CLONE [" . $e . "] to [" . $i . "]<br /><br />"; 
	
	if ($d = select("*", "syspermissions", "where (CompanyID='" . COMPANYID . "' and UserGroupID=" . $e . ") order by ActionCode")) { 

		foreach ($d as $r) {
			
			// DEBUG
			//echo $r["ActionCode"] . " (" . $r["ActionGroup"] . ") = " . $r["Allow"] . "<br />\n";	
			
			$t = array();
			$t["CompanyID"] = $r["CompanyID"];
			$t["ActionCode"] = $r["ActionCode"];
			$t["ActionGroup"] = $r["ActionGroup"];
			$t["UserGroupID"] = $i;
			$t["Allow"] = $r["Allow"];
			
			$n = insert("syspermissions", $t);
			
		}

	}
	
}