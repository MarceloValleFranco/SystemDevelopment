<?php

// SYSUSERS.PHP 1.0 (2019/01/22)

$avatars = "../../custom/assets/images/avatar/"; // CHECK SYSUSERS.JS

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// ACTION
$a = ""; if (isset($_GET['a'])) { $a = clean($_GET["a"]); }
$i = ""; if (isset($_GET['i'])) { $i = clean($_GET["i"]); }
$m = ""; if (isset($_GET['m'])) { $m = clean($_GET["m"]); }

// DATABASE OPEN
openDB();

    if ($a == 'u') { 

        // PHOTO UPLOAD
        fileUpload($_FILES, $i);
    
    } else {

            // PAGE HEADER
            pageHeader("sysusers");

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
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . translate("UsersManager") . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . translate("UsersManagerDescription") . "</small></h4>\n";;
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		// ADD NEW USER
		echo "							<button type='button' class='btn btn-success ml-2 add-button'><i class='icon-user-plus'></i><span class='button-text'>" . translate("AddUser") . "</span></button>\n"; 	
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	echo "								<span class='breadcrumb-item active'>" . translate("UsersManager") . "</span>\n";  
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
	if (getPermission("View", "UsersReport")) { 
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
	
	// SQL QUERY
    if ($d = select("sysusers.*, sysusersgroups.*", "sysusers", "inner join sysusersgroups on sysusers.UserGroupID=sysusersgroups.UserGroupID where (sysusers.CompanyID='" . COMPANYID . "' and UserActive<>'4')")) {
			
		// CARD HEAD
		echo "					<div class='card'>\n";

		// OPTIONAL CARD HEADER
		//echo "					<div class='card-header header-elements-inline'>\n"; 
		//echo "						<h5 class='card-title'>Multiple columns</h5>\n"; 
		//echo "						<div class='header-elements'>\n"; 
		//echo "							<div class='list-icons'>\n"; 
		//echo "		                		<a class='list-icons-item' data-action='collapse'></a>\n"; 
		//echo "		                		<a class='list-icons-item' data-action='reload'></a>\n"; 
		//echo "		                		<a class='list-icons-item' data-action='remove'></a>\n"; 
		//echo "		                	</div>\n"; 
		//echo "	                	</div>\n"; 
		//echo "					</div>\n"; 

		//// CARD BODY
		//echo "					<div class='card-body'>123\n";
		//echo "					</div>\n"; // /card-body

		//echo "				</div>\n"; // /card				
				
		echo "						<table class='table table-border ed table-hover datatable-highlight'>\n";

		echo "							<thead>\n"; 
		echo "								<tr>\n"; 

        //if (getOptionValue("UseAvatar")) {
		//	echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserAvatar") . "</th>\n";
		//}
        echo "                                      <th class='pl-10'>" . translate("UserName") . "</th>\n";
        echo "                                      <th class='d-none d-sm-table-cell'>" . translate("UserMail") . "</th>\n";
		echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserGroup") . "</th>\n";
        echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserRegisterDate") . "</th>\n";
		echo "                                      <th class='d-none d-lg-table-cell'>" . translate("UserUpdateDate") . "</th>\n";
        echo "                                      <th class='status text-right'>" . translate("Status") . "</th>\n";

		echo "								</tr>\n"; 
		echo "							</thead>\n";

		echo "							<tbody>\n"; 
		
		// RECORDS LOOP
		foreach ($d as $r) { 
		
			echo "								<tr class='table-tr' id='" . $r['UserID'] . "#" . $r["UserName"] . " " . $r["UserSurName"] . "'>\n"; 

                //// USER AVATAR
                //if (getOptionValue("UseAvatar")) {
                //    $userAvatar = $r['UserAvatar']; if ($userAvatar == '') { $userAvatar = "../../assets/images/avatar/0.png"; } else { $userAvatar = $avatars . $userAvatar; if (!file_exists($userAvatar)) { $userAvatar = "../../assets/images/avatar/0.png"; } }
                //    echo "                                      <td class='d-none d-lg-table-cell'><img alt='" . $r["UserName"] . " " . $r["UserSurName"] . "' src='" . $userAvatar . "' class='userAvatar img-rounded' /></td>\n";
                //}
                
                echo "                                      <td class='pl-10'>" . $r["UserName"] . " " . $r["UserSurName"] . "</td>\n";
                echo "                                      <td class='d-none d-sm-table-cell'>" . $r["UserMail"] . "</td>\n";
                echo "                                      <td class='d-none d-lg-table-cell'>" . translate($r["UserGroupName"]) . "</td>\n";

                // REGISTER DATE
                $rDate = ''; if (isDate($r["UserRegisterDate"])) { $temp = date_create($r["UserRegisterDate"]); $rDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='d-none d-lg-table-cell'>" . $rDate . "h</td>\n";

                // UPDATE DATE
                $uDate = ''; if (isDate($r["UserUpdateDate"])) { $temp = date_create($r["UserUpdateDate"]); $uDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='d-none d-lg-table-cell'>" . $uDate . "h</td>\n";
                
                // USER STATUS
                switch($r["UserActive"]) {
                    case "0": $c = "danger"; $n = translate("Inactive"); break;
                    case "2": $c = "warning"; $n = translate("Suspended"); break;
					case "3": $c = "warning"; $n = translate("WaitActivation"); break;
                    default: $c = "success"; $n = translate("Active"); break;                
                } 
                echo "                                      <td class='text-right'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";			
			
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
	
	// CONTEXT MENU
	echo "					<div id='OptionMenu' class='context-menu display-none'>\n";
	echo "						<ul>\n";
	$edit = "View"; $icon = "eye"; if (getPermission("Edit", "UsersManager")) { $edit = "Edit"; $icon = "pencil7"; }
	if (getPermission("View", "UsersManager")) { echo "							<li id='edit'><i class='icon-" . $icon . " context-icon'></i>" . translate($edit) . "</li>\n"; }
	if (getPermission("Clone", "UsersManager")) { echo "							<li id='clone'><i class='icon-users2 context-icon'></i>" . translate('Clone') . "</li>\n"; } 
	if (getPermission("Delete", "UsersManager")) { echo "						<li id='delete'><i class='icon-user-minus context-icon'></i>" . translate('Delete') . "</li>\n"; }
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
	if (!isset($_GET['a'])) { logWrite("View", "UsersManager", "sysusers"); }	
	
}

function itemEdit($i, $m = '', $field = '', $clone = '', $t = '') {

    global $avatars;
	global $app;
    
    echo "	<body>\n";
    
    // PLUGINS
    echo addCode("../../core/plugins/forms/selects/select2.min.js");
    echo addCode("../../core/plugins/forms/styling/uniform.min.js");
    echo addCode("../../core/plugins/forms/styling/switchery.min.js");
    
    // CONFIRM BOX
    confirmBox();  
    
    // DEFAULT DATA
    $userName = '';
    $userSurName = '';
    $userMail = '';
    $userPassword = '';
    $userLogin = '';
    $userLanguage = DEFAULTLANGUAGE;
    $userActive = '1';  
    $userAvatar = '';
	$userGroupID = '';
    
    $title = translate("UserCreate"); $description = translate("UserCreateDescription");
    
    if ($i != '0' || $clone != '0') {
    
        $e = $i; if ($clone != '') { $e = $clone; } else { if ($i != '0') { $title = translate("UserEdit"); $description = translate("UserEditDescription"); } }
    
		// GET EXISTENT DATA
        if ($r = select("*", "sysusers", "where UserID=" . $e)) { 
        
            $userName = $r[0]["UserName"];
            $userSurName = $r[0]["UserSurName"];
            $userMail = $r[0]["UserMail"];
            $userPassword = encryptor($r[0]["UserPassword"], 1);
            $userLogin = $r[0]["UserLogin"];
            $userLanguage = $r[0]["UserLanguage"];
            $userActive = $r[0]["UserActive"];
			$userGroupID = $r[0]["UserGroupID"];
            
			// AVATAR
			$userAvatar = $r[0]["UserAvatar"];
			if (isset($_GET['d'])) {
				if ($_GET['d'] == '1') { 
					fileDelete($i, $userAvatar);
					$userAvatar = '';
				}
			}
        }
        
    }
    
	// SET EDITED DATA
    if (isset($_GET['UserName'])) { $userName = $_GET['UserName']; }
    if (isset($_GET['UserSurName'])) { $userSurName = $_GET['UserSurName']; }
    if (isset($_GET['UserMail'])) { $userMail = $_GET['UserMail']; }
    if (isset($_GET['UserPassword'])) { $userPassword = $_GET['UserPassword']; }
    if (isset($_GET['UserLogin'])) { $userLogin = $_GET['UserLogin']; }
    if (isset($_GET['UserLanguage'])) { $userLanguage = $_GET['UserLanguage']; }
    if (isset($_GET['UserActive'])) { $userActive = $_GET['UserActive']; }
    if (isset($_GET['UserAvatar'])) { $userAvatar = $_GET['UserAvatar']; }
	if (isset($_GET['UserGroupID'])) { $userGroupID = $_GET['UserGroupID']; }
	    
    // PROFILE MODE
    if (isset($_GET['p'])) { $title = translate("UserProfile"); }
    
	echo "		<div class='page-content'>\n";
	
	echo "			<div class='content-wrapper'>\n";
	
	// PAGE HEAD	
	echo "				<div class='page-header page-header-light'>\n";
	
	echo "					<div class='page-header-content header-elements-inline'>\n";
	
	// PAGE TITLE
	echo "						<div class='page-title d-flex'>\n"; 
	echo "							<h4><span class='font-weight-semibold'>" . $title . "</span>\n"; 
	echo "							<small class='d-block opacity-75 ml-0''>" . $description . "</small></h4>\n";
	echo "						</div>\n";	
 
	// RIGHT BUTTONS
	echo "						<div class='header-elements d-flex align-items-center'>\n"; 
	
		//CANCEL
		if (!isset($_GET['p'])) { echo "                              <button type='button' class='btn btn-default cancel-button d-none d-md-block'><i class='icon-chevron-left pl-0 pr-0'></i><span class='button-text'>" . translate("Cancel") . "</span></button>\n"; }
	
		//DELETE
		if ($i != '0' && $i != USERID) { if (getPermission("Delete", "UsersManager")) { echo "                              <button type='button' class='btn btn-danger ml-2 delete-button' id='" . $i . "#" . $userName . " " . $userSurName . "'><i class='icon-user-minus'></i><span class='button-text'>" . translate("Delete") . "</span></button>\n"; } }
	
		//UPDATE
		if (isset($_GET['p'])) { 
			echo "                              <button type='button' class='btn btn-success ml-2 save-button'><i class='icon-user-check'></i><span class='button-text'>" . translate("Update") . "</span></button>\n";
		} else {
			$act = "Edit"; $caption = "Update"; if ($i == '0') { $act = "Add"; $caption = "Save"; }
			if (getPermission($act, "UsersManager")) { echo "                              <button type='button' class='btn btn-success ml-2 save-button'><i class='icon-user-check'></i><span class='button-text'>" . translate($caption) . "</span></button>\n"; }
		}		
	 
	echo "						</div>\n";
	
	echo "					</div>\n"; 

	echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 
	
	echo "						<div class='d-flex'>\n";
	
	// BREAD CRUMBS
	echo "							<div class='breadcrumb'>\n"; 
	echo "								<a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n"; 
	if (getPermission("View", "UsersManager")) { echo "								<a href='?' class='breadcrumb-item'>" . translate("UsersManager") . "</a>\n"; } 
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
	if (getPermission("View", "UsersReport")) { 
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
	    echo "						" . $m . "\n";
	    echo "					</div>\n";
    } 
    
    echo "					<div class='card'>\n";
	
	echo "						<div class='card-body'>\n";
	
	// LEFT SIDE
	echo "							<div class='row'>\n";
	
	// PERSONAL DATA
	echo "								<div class='col-md-6'>\n";
	echo "									<fieldset>\n";
	echo "										<legend class='font-weight-semibold'><i class='icon-user mr-2'></i>" . translate("PersonalData") . "</legend>\n";
	
	echo "										<form method='get' class='form-vertical' accept-charset='UTF-8' autocomplete='off'>\n";
	
		// HIDDEN FIELDS 
        echo "											<input type='hidden' name='a' value='s' />\n";
        echo "											<input type='hidden' name='i' value='" . $i ."' />\n";
		echo "											<input type='hidden' name='z' value='" . $avatars ."' id='z' />\n";
        echo "											<input type='hidden' name='UserAvatar' id='UserAvatarFile' value='" . $userAvatar . "' />\n";
        if (isset($_GET['p'])) { echo "											<input type='hidden' name='p' value='1' />\n"; }
		
		// COMPANY ID
		echo "											<input type='hidden' name='CompanyID' value='" . COMPANYID . "' />\n";
		
		echo "											<div class='row'>\n";
		
		// USER NAME
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserName") . "</label>\n";
        echo "														<input type='text' class='form-control user-name' name='UserName' value='" . $userName . "' maxlength='40' placeholder='" . translate("UserName") . "' />\n";
        if ($field == "UserName") { echo "														<span class='help-block text-danger'>" . $m . "</span>\n"; }
 
		echo "													</div>\n";
		echo "												</div>\n";

		// USER SURNAME
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserSurName") . "</label>\n";
        echo "														<input type='text' class='form-control user-surname' name='UserSurName' value='" . $userSurName . "' maxlength='40' placeholder='" . translate("UserSurName") . "' />\n";
        if ($field == "UserSurName") { echo "														<span class='help-block text-danger'>" . $m . "</span>\n"; }
        
		echo "													</div>\n";
		echo "												</div>\n";
		
		echo "											</div>\n";	

		echo "											<div class='row'>\n";
		
		// USER MAIL
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserMail") . "</label>\n";
        echo "                                                      <input type='email' class='form-control user-mail' name='UserMail' value='" . $userMail . "' maxlength='200' placeholder='" . translate("name@server.com") . "' autocomplete='off' readonly  onfocus=\"if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus(); }\" />\n";
        if ($field == "UserMail") { echo "                                                      <span class='help-block text-danger'>" . $m . "</span>\n"; } 
		echo "													</div>\n";
		echo "												</div>\n";

		// USER PASSWORD
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserPassword") . "</label>\n";
        echo "                                                      <input type='password' class='form-control user-password' name='UserPassword' value='" . $userPassword . "' placeholder='" . translate("Max12Chars") . "' maxlength='12' autocomplete='off' readonly  onfocus=\"if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus(); }\" />\n";
        if ($field == "UserPassword") { echo "                                                      <span class='help-block text-danger'>" . $m . "</span>\n"; }        
		echo "													</div>\n";
		echo "												</div>\n";
		
		echo "											</div>\n";	

		echo "											<div class='row'>\n";
		
		// USER LOGIN
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserLogin") . "</label>\n";
        echo "                                                      <input type='text' class='form-control user-login' name='UserLogin' value='" . $userLogin . "' maxlength='20' placeholder='" . translate("Name.SurName") . "' />\n";
        if ($field == "UserLogin") { echo "                                                     <span class='help-block text-danger'>" . $m . "</span>\n"; } 
		echo "													</div>\n";
		echo "												</div>\n";

		// USER LANGUAGE
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		echo "														<label>" . translate("UserLanguage") . "</label>\n";
        echo "                                                      <select class='form-control form-control-select2 select2combo' name='UserLanguage'>\n";
        echo "                                                          <option value='br'"; if ($userLanguage == 'br') { echo " selected"; } echo "> Portugu&ecirc;s</option>\n";
        echo "                                                          <option value='en'"; if ($userLanguage == 'en') { echo " selected"; } echo "> English</option>\n";
        //echo "                                                          <option value='es'"; if ($userLanguage == 'es') { echo " selected"; } echo "> Espa&ntilde;ol</option>\n";
        //echo "                                                          <option value='fr'"; if ($userLanguage == 'fr') { echo " selected"; } echo "> Fran&ccedil;ais</option>\n";
        echo "                                                      </select>\n";       
		echo "													</div>\n";
		echo "												</div>\n";
		
		echo "											</div>\n";

		echo "											<div class='row'>\n";
		
		// USER GROUP
		echo "												<div class='col-md-6'>\n";
		echo "													<div class='form-group'>\n";
		if ($d = select("*", "sysusersgroups", "where (CompanyID=" . COMPANYID . " and UserGroupActive='1')")) {
			echo "														<label>" . translate("UserGroup") . "</label>\n";
			echo "                                                      <select class='form-control form-control-select2 select2combo' name='UserGroupID'>\n";
			foreach ($d as $r) {
				if (($r["UserGroupID"] > USERGROUPID) || USERGROUPID == '1') {
					echo "                                                          <option value='" . $r["UserGroupID"] . "'"; if ($userGroupID == $r["UserGroupID"]) { echo " selected"; } echo "> " . translate($r["UserGroupName"]) . " </option>\n";
				}
			}
			echo "                                                      </select>\n";
		}
		echo "													</div>\n";
		echo "												</div>\n";
		
		// USER ACTIVE
		echo "												<div class='col-md-6'>\n";
		if (getPermission("Activate", "UsersManager")) { 
			echo "													<div class='form-group'>\n";
			echo "														<label>" . translate("UserActive") . "</label>\n";
			echo "                                                      <select class='form-control select-icons select2combo' name='UserActive' data-fouc>\n";
			echo "                                                          <option data-icon='user-check' value='0'"; if ($userActive == '0') { echo " selected"; } echo "> " . translate("Inactive") . "</option>\n";
			echo "                                                          <option data-icon='user-block' value='1'"; if ($userActive == '1') { echo " selected"; } echo "> " . translate("Active") . "</option>\n";
			echo "                                                          <option data-icon='user-lock' value='2'"; if ($userActive == '2') { echo " selected"; } echo "> " . translate("Suspended") . "</option>\n";
			echo "                                                          <option data-icon='user-lock' value='3'"; if ($userActive == '3') { echo " selected"; } echo "> " . translate("WaitActivation") . "</option>\n";
			echo "                                                      </select>\n"; 
			echo "													</div>\n";
		}
		echo "												</div>\n";		
	
		echo "											</div>\n";	

		echo "											<div class='row'>\n";
		
		// USER REMINDER
		echo "												<div class='col-md-6'>\n";
        if ($i == USERID) {
            $userReminder = "0";
            if (isset($_COOKIE['sysUserReminder'])) { if ($_COOKIE['sysUserReminder'] != '') { $userReminder = "1"; } }		
			echo "													<div class='form-group'>\n";
			echo "														<label>" . translate("UserReminder") . "</label>\n";
			echo "														<div class='checkbox-left switchery-sm'>\n";
			echo "															<input type='checkbox' class='switchery switch2' "; if ($userReminder == '1') { echo " checked='checked'"; } echo " />\n";
			echo "                                                      	<input type='hidden' id='UserReminder' name='UserReminder' value='" . $userReminder . "' />\n";
			echo "														</div>\n";       
			echo "													</div>\n";
		}
		echo "												</div>\n";
		
		// FREE SPACE
		echo "												<div class='col-md-6'>\n";

		echo "												</div>\n";		
		
		echo "											</div>\n";	

	echo "										</form>\n";
		
	echo "									</fieldset>\n";
	
	echo "								</div>\n"; // /col-md-6
	
	echo "							</row>\n";
	
	// RIGHT SIDE
	echo "							<div class='row>'\n";

	echo "								<div class='col-md-6'>\n";
	/*
    if (getOptionValue("UserAvatar")) {
    
        if (getPermission("UploadAvatar", "UsersManager")) { 
    
            $dl = 0; if ($userAvatar == '') { $userAvatar = "../../assets/images/avatar/0.png"; } else { $userAvatar = $avatars . $userAvatar; if (!file_exists($userAvatar)) { $userAvatar = "../../assets/images/avatar/0.png"; } else { $dl = 1; } }    
    
				// IMAGES (RIGHT COLUMN)
                echo "                                          <fieldset>\n";
                echo "                                              <legend class='font-weight-semibold'><i class='icon-camera mr-2'></i> " . translate("Images") . "</legend>\n";
				echo "                                      		<form method='post' enctype='multipart/form-data' id='uploadForm'>\n";
                echo "                                              	<div class='form-group'>\n";
                echo "                                                  	<label class='preMedia'>" . translate("UserPhoto") . "</label>\n";
                echo "                                                  	<div class='col-lg-10'>\n";
                echo "                                                      	<div class='media no-margin-top'>\n";
                echo "                                                          	<div class='pull-left'>\n";
                echo "                                                              	<img src='" . $userAvatar . "' class='userAvatar avatar-rounded' id='userAvatar' title='" .$userName . " " .$userSurName . "' alt='" .$userName . " " .$userSurName . "' />\n";
                echo "                                                          	</div>\n";
                echo "                                                          	<div class='media-body'>\n";
                echo "                                                              	<input type='file' accept='.gif, .png, .jpg, .jpeg' class='file-upload-ajax file-styledx' name='uploadFile' />\n";
                echo "                                                              	<input type='hidden' name='i' id='userID' value='" . $i . "' />\n";
                echo "                                                              	<span class='help-block'>" . translate("UserPhotoUploadRestrict");
                $p = ''; if (isset($_GET['p'])) { if ($_GET['p'] == '1') { $p = '&p=1'; } }
				if ($dl == '1') { echo " <a href='?a=e&i=" . $e . "&d=1" . $p . "'>" . translate("DeleteImage") . "</a>"; }
                echo "<br /><span class='error-message'></span></span>\n";
                $bs = "Select"; if ($dl == '1') { $bs = "Change"; }
                echo "                                                              	<script>$('.file-styledx').uniform({ fileButtonClass: 'action btn btn-default btn-outline', fileDefaultHtml: '" . translate("PickAFile") . "', fileButtonHtml: '<i class=icon-camera></i><span class=hidden-xs>&nbsp; &nbsp;" . translate($bs) . "</span>' });</script>\n";
                echo "                                                          	</div>\n";
                echo "                                                      	</div>\n";
                echo "                                                  	</div>\n";
                echo "                                              	</div>\n";
				echo "                                      		</form>\n";				
                echo "                                          </fieldset>\n";    
        
        }
    
    }	
	*/	
	echo "								</div>\n"; // /col-md-6
	
	echo "							</row>\n"; // /row

    echo "						</div>\n"; // /card-body
    
    echo "					</div>\n"; // /card
	
    // DEMO MESSAGE
    if (DEMO) { echo "					<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; } 	
	
    echo "				</div>\n"; // /content
	
    echo "			</div>\n"; // /content-wrapper
    echo "		</div>\n"; // /page-container
	
	// DEBUG
	echo "		<!-- TOKEN [" . base64_encode($i . "," . $userLanguage . "," . date("YmdHis")) . "] -->\n"; 
	   
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
                
                    case "UserLogin":
                        if ($value != '') {
                            $value = strtolower($value);
                            $t[$key] = $value; 
                            
                            // VERIFY IF USER LOGIN EXISTS
                            if ($i == '0') {
                                if ($r = select("UserLogin", "sysusers", "where (UserLogin='" . $value . "' and UserLogin<>'')")) { $error = translate('LoginAlreadyExists'); $field = $key; }
                            } else {
                                if ($r = select("UserLogin", "sysusers", "where (UserLogin='" . $value . "' and UserLogin<>'' and UserID<>" . $i . ")")) { $error = translate('LoginAlreadyExists'); $field = $key; }
                            }
                            unset($r);
                        }
                        break;                  
                
                    case "UserPassword":
                        if ($value == '') { $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key; } else { $t[$key] = encryptor($value); }
                        break;   
                        
                    case "UserReminder":
                        if ($value == '1') { setcookie("sysUserReminder", encryptor($_GET['UserMail'] . "#" . $_GET['UserPassword'] . "#" . $_SESSION['screenWidth'] . "#" . $_SESSION['screenHeight'] . "#" . USERLANGUAGE), time() + (86400 * 90), "/"); } else { setcookie("sysUserReminder", "", time() - 3600, "/"); }
                        break;                          
                        
                    case "UserMail":
                        $value = strtolower($value);
                        if ($value == '') { 
                            $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key;
                        } else {
                            $t[$key] = $value;
                            
                            // CHECK VALID E-MAIL
                            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {

                                // VERIFY IF USER E-MAIL ALREADY EXISTS ON DATABASE
                                if ($i == '0') {
                                    $sql = "select * from sysusers where UserMail='" . $value . "'";
                                    if ($r = select("UserMail", "sysusers", "where UserMail='" . $value . "'")) { $error = translate('EmailAlreadyExists'); $field = $key; }
                                } else {
                                    if ($r = select("UserMail", "sysusers", "where (UserMail='" . $value . "' and UserID<>" . $i . ")")) { $error = translate('EmailAlreadyExists'); $field = $key; }
                                }
                                unset($r);
                            
                            } else {
                                $error = translate('InvalidEmail'); $field = $key;
                            }
                        }
                        break;
                                                
                    case "UserSurName":
                        if ($value == '') { $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key; } else { $t[$key] = strtoupper($value); } 
                        break;                    
                    
                    case "UserName":
                        if ($value == '') { $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key; } else { $t[$key] = strtoupper($value); } 
                        break;
                        
                    default: $t[$key] = $value; break;
                } 
            }
        }    
    }

	// EXTRA FIELD
	$t["UserRegisterDate"] = date("Y-m-d H:i:s");
    
    // DEBUG
    //print_r($t); exit;
    
    if ($error != '') {
    
		// ERROR: RETURN TO USER EDITION
        itemEdit($i, $error, $field, '', '1');
        
    } else {
    
		// GET NAME OF THE USER
		$name = $_GET["UserName"]; if (isset($_GET["UserSurName"])) { $name .= ' ' . $_GET["UserSurName"]; }
	
        if ($i == '0') {

            // INSERT NEW USER
            if ($n = insert("sysusers", $t)) {

				// SAVE LOG EVENT
                logWrite("Add", "UsersManager", "sysusers", $n, $name);

				// RETURN TO USER LIST
                header("Location: ?a=x&m=RecordedOk");

            } else {

                // ERROR: RETURN TO USER EDITION
				itemEdit($i, "ErrorSavingData", "Form", "", "1");

            }          
        
        } else {    

            // UPDATE EXISTING USER
            if (update("sysusers", $t, "where UserID=" . $i)) {

				// SAVE LOG EVENT
                logWrite("Update", "UsersManager", "sysusers", $i, $name);

				// RETURN TO USER LIST
                if (isset($_GET['p'])) { header("Location: ?m=UpdatedOk&a=e&i=" . $i . "&p=1"); } else { header("Location: ?a=x&m=UpdatedOk"); }

            } else {

				// ERROR: RETURN TO USER EDITION
                itemEdit($i, "ErrorUpdatingData", "Form", "", "1"); 

            }        
        
        }    
    
    }
}

function itemDelete($i) {

    if ($i != USERID) {

        $t = array();
        $t["UserActive"] = "4";

		// RECORD IS NOT DELETED - JUST SET TO STATUS 4
        if (update("sysusers", $t, "where UserID=" . $i)) { 
    
			// SAVE LOG EVENT
            logWrite("Delete", "UsersManager", "sysusers", $i);

			// RETURN TO USER LIST
            header('Location: ?a=x&m=DeletedOk');
    
		} else {
	
			// ERROR: RETURN TO USER LIST
			header('Location: ?a=x&m=DeleteError&t=1');

		}
    
    } else {

		// ERROR: RETURN TO USER LIST
        header('Location: ?m=CannotDeleteYourself&t=1');

    }
}

function fileUpload($file_array, $i) {

    global $avatars;
		
    $response = array();
    $uploaded_files = array();
            
	if (isset($file_array['uploadFile']) && $file_array['uploadFile']['name'] != '') {
            
		$currentfile_extension = end(@explode(".", $file_array['uploadFile']['name']));

        switch($currentfile_extension) {
            
            case "jpg":
            case "png":
            case "gif":
            case "jpeg":
                
                $filename = date("YmdHis") . rand(1000,9999) . "." . $currentfile_extension;
                
			    if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $avatars . $filename)) {
                
				    $response['file_name'] = $filename;
				    $response['response_html'] = $filename;
                    $response['message'] = '';
                    
					// SAVE EVENT
                    logWrite("UploadAvatar", "UsersManager", "sysusers", $i, $filename);
                    
			    } else {
                
				    $response['file_name'] = '';
                    $response['response_html'] = '';
                    $response['message'] = translate("FileUploadError");
			    }
            break;
            
            default:
                $response['message'] = translate("IncompatibleFile");
                break;
        }
	}

    echo json_encode($response);
}

function fileDelete($i, $userAvatar) {

	global $avatars;

	if ($userAvatar != '') { if (file_exists($avatars . $userAvatar)) { unlink($avatars . $userAvatar); } }

    $t = array();
    $t["UserAvatar"] = '';
    if (update("sysusers", $t, "where UserID=" . $i)) { 
    
        logWrite("DeleteAvatar", "UsersManager", "sysusers", $i);
    
    }
}

function confirmBox() {

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