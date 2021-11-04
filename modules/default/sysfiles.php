<?php

// SYSFILES.PHP 1.0 (2018/12/04)

// NOTE: REQUIRES .HTACESS OR PHP.INI ON ROOT

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

set_time_limit(0);
ini_set("upload_max_filesize","500M");
ini_set("post_max_size","500M");

// SYSTEM FOLDER
$sysFolder = left($_SERVER['SCRIPT_NAME'], strlen($_SERVER['SCRIPT_NAME']) - strlen("/apps/default/sysfiles.php"));

// DATABASE OPEN
openDB();

// CONFIG
$startdir = '../../files';
$extensions = base64_encode(getOptionValue("PermittedFiles"));
$writeonroot = getOptionValue("WriteOnRoot");
$downloadApp = "http://" . $_SERVER['HTTP_HOST'] . $sysFolder . "/?code=";
$includeurl = false;
$showdirs = true;
$forcedownloads = true;
$displayindex = false;
$hide = array('index.php', 'Thumbs', '.htaccess', '.htpasswd');
$indexfiles = array('index.html', 'index.htm');
$showtypes = array();

// GET FROM PHP.INI
$phpallowuploads = (bool) ini_get('file_uploads');
$phpmaxsize = 0; if ($phpallowuploads) { $phpmaxsize = ini_get('upload_max_filesize'); }

// VARIABLES
$successMessage = '';
if (isset($_GET['sm'])) { $successMessage = $_GET['sm']; }
$errorMessage = '';
if (isset($_GET['em'])) { $errorMessage = $_GET['em']; }
$self = strip_tags($_SERVER['PHP_SELF']);

// INITIAL PATH
$dirok = false;
if ($includeurl) { $includeurl = @preg_replace("/^\//", "${1}", $includeurl); if (substr($includeurl, strrpos($includeurl, '/')) != '/') $includeurl .= '/'; }
if ($startdir) { $startdir = @preg_replace("/^\//", "${1}", $startdir); $leadon = $startdir; }
if ($leadon == '.') { $leadon = ''; }
if ((substr($leadon, -1, 1) != '/') && $leadon != '') { $leadon = $leadon . '/'; $startdir = $leadon; }
$dir = '';
if (isset($_GET['dir'])) { $dir = $_GET['dir']; }
if ($dir) {
    if (substr($dir, -1, 1) != '/') { $dir = strip_tags($dir) . '/'; }
    $dirok = true;
    $dotdotdir = '';
    $dirnames = explode('/', strip_tags($dir));
    for ($di = 0; $di < sizeof($dirnames); $di++) {
        if ($di < (sizeof($dirnames) - 2)) { $dotdotdir = $dotdotdir . $dirnames[$di] . '/'; }
        if ($dirnames[$di] == '..') { $dirok = false; }
    }
    if (substr($dir, 0, 1) == '/') { $dirok = false; }
    if ($dirok) { $leadon = $leadon . strip_tags($dir); }
}    

// COPY FILE
if (!isset($_SESSION['copyFile'])) { $_SESSION['copyFile'] = ''; }
$copyFile = '';
if (isset($_GET['copy'])) { $copyFile = $_GET['copy']; }
if ($copyFile != '') {
    $file = str_replace('/', '', $copyFile);
    $file = str_replace('..', '', $file);
    $f = $includeurl . $leadon . $file;
    if (file_exists($f)) { $_SESSION['file'] = $file; $_SESSION['copyFile'] = $f; $successMessage = translate("FileCopied") . ": <b>" . $file . "</b>."; logWrite("FileCopy", "FilesManager", '', '', $file); } else { $errorMessage = translate("SourceFileRemoved"); }
}

if (!DEMO) {

    // PASTE FILE
    if (isset($_GET['paste'])) {
        if (file_exists($_SESSION['copyFile'])) { copy($_SESSION['copyFile'], $includeurl . $leadon . $_SESSION['file']); $successMessage = translate("FilePasted") . ": <b>" . $_SESSION['file'] . "</b>."; logWrite("FilePaste", "FilesManager", '', '', $_SESSION['file']); } else { $_SESSION['copyFile'] = ''; $errorMessage = translate("SourceFileRemoved"); }
    }

    // MOVE FILE
    if (isset($_GET['move'])) {
        if (file_exists($_SESSION['copyFile'])) { rename($_SESSION['copyFile'], $includeurl . $leadon . $_SESSION['file']); $successMessage = translate("FileMoved") . ": <b>" . $_SESSION['file'] . "</b>."; logWrite("FileMove", "FilesManager", '', '', $_SESSION['file']); } else { $errorMessage = translate("SourceFileRemoved"); }
        $_SESSION['copyFile'] = '';
    }

    // RENAME FILE
    $fileNewName = '';
    if (isset($_POST['fileNewName'])) { $fileNewName = strip_tags(urldecode(utf8_decode($_POST['fileNewName']))); $fileOldName = strip_tags(urldecode($_POST['fileOldName'])); }
    if ($fileNewName != '') {
        if (strtolower($fileNewName) == strtolower($fileOldName)) {
            $errorMessage = translate("SameFileName") . ": <b>" . $fileNewName . "</b>!";
        } else {
            if (strpbrk($fileNewName, "\\/?%*:|\"<>") === false) {
                if (file_exists($includeurl . $leadon . $fileOldName)) { rename($includeurl . $leadon . $fileOldName, $includeurl . $leadon . $fileNewName); $successMessage = translate("FileRenamed") . ": <b>" . $fileOldName . "</b> " . translate("to") . " <b>" . $fileNewName . "</b>"; logWrite("FileRename", "FilesManager", '', '', $fileOldName . "|" . $fileNewName);} else { $errorMessage = translate("SourceFileRemoved"); }
            } else {
                $errorMessage = translate("FileNameError") . ": <b>" . $fileNewName . "</b>!";
            }
        }
    }
    
    // CREATE NEW FOLDER
    $folderName = '';
    if (isset($_POST['folderName'])) { $folderName = strip_tags($_POST['folderName']); }
    if ($folderName != '') {
        if (strpbrk($folderName, "\\/?%*:|\"<>") === false) {
            if (file_exists($leadon . $folderName)) { $errorMessage = translate("DirAlreadyExists"); } else { if (@mkdir($leadon . utf8_decode($folderName))) { $successMessage = translate("FolderCreated") . ": <b>" . utf8_decode($folderName) . "</b>."; logWrite("FolderCreate", "FilesManager", '', '', $folderName); } }
        } else {
            $errorMessage = translate("FolderNameError") . ": <b>" . $folderName . "</b>!";
        }
    }

    // DELETE FOLDER
    $deleteFolder = '';
    if (isset($_GET['deleteFolder'])) { $deleteFolder = $_GET['deleteFolder']; }
    if ($deleteFolder != '') {
        deleteFolder($startdir . $deleteFolder);
        $temp = left($_GET['deleteFolder'], strlen($deleteFolder) - 1);
        logWrite("FolderDelete", "FilesManager", '', '', utf8_encode($deleteFolder));
        header("Location: ?sm=" . urlencode(translate("FolderDeleted") . ": <b>" . $deleteFolder . "</b>.") . "&dir=" . left($temp, strripos($temp, '/')) . "/");
    }

    // DELETE FILE
    $deleteFile = '';
    if (isset($_GET['delete'])) { $deleteFile = $_GET['delete']; }
    if ($deleteFile != '') {
        $file = str_replace('/', '', $deleteFile);
        $file = str_replace('..', '', $file);
        $f = $includeurl . $leadon . $file;
        if (file_exists($f)) { unlink($f); logWrite("FileDelete", "FilesManager", '', '', utf8_encode($file)); header("Location: ?sm=" . urlencode(translate("FileDeleted") . ": <b>" . $deleteFile . "</b>.") . "&dir=" . urlencode($dir)); }
    }

    // QUICK NOTE SAVE
    $quickNoteName = '';
    if (isset($_POST['quickNoteName'])) { $quickNoteName = strip_tags($_POST['quickNoteName']); }
    if ($quickNoteName != '') {
        if (strpbrk($quickNoteName, "\\/?%*:|\"<>") === false) {
            if (isset($_POST['quickNoteName'])) {
                file_put_contents($includeurl . $leadon . utf8_decode($quickNoteName . '.txt'), "\xEF\xBB\xBF" . strip_tags($_POST['quickNoteText']));
                $successMessage = translate("FileCreated") . ": <b>" . utf8_decode($quickNoteName) . '.txt' . "</b>.";
                logWrite("QuickNote", "FilesManager", '', '', $quickNoteName . '.txt');
            } 
        } else {
            $errorMessage = translate("FileNameError") . "</b>!";
        }
    }

}

// DOWNLOAD
$downloadFile = '';
if (isset($_GET['download'])) { $downloadFile = $_GET['download']; }
if (($downloadFile != '') && ($forcedownloads == true)) {
    $file = str_replace('/', '', $downloadFile);
    $file = str_replace('..', '', $file);
	$fullname = $includeurl . $leadon . $file;
    if (file_exists($fullname)) {
        $fd = fopen($fullname, "rb");
        if ($fd) {
            $fsize = filesize($fullname);
            $path_parts = pathinfo($fullname);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case "pdf":
                header("Content-type: application/pdf");
                break;
                case "zip":
                header("Content-type: application/zip");
                break;
                default:
                header("Content-type: application/octet-stream");
                break;
            }
            header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
            header("Content-length: $fsize");
            header("Cache-control: private");
            while(!feof($fd)) {
                $buffer = fread($fd, 1*(1024*1024));
                echo $buffer;
                ob_flush();
                flush();
            }
        }
        else {
            $errorMessage = translate("SourceFileRemoved");
        }
        fclose($fd);
        logWrite("FileDownloaded", "FilesManager", '', '', utf8_encode($downloadFile));
        die();
    }  else {
        $errorMessage = translate("SourceFileRemoved");
    }
    die();
}

// DIRECTORY ORDER
$opendir = $includeurl . $leadon;
if (!$leadon) { $opendir = '.'; }
if (!file_exists($opendir)) { $opendir = '.'; $leadon = $startdir; }
clearstatcache();
$n = 0;
$sort = '';
if (isset($_GET['sort'])) { $sort = $_GET['sort']; }
$order = '';
if (isset($_GET['order'])) { $order = $_GET['order']; }
if ($handle = opendir($opendir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file == "." || $file == "..")
            continue;
        $discard = false;
        for ($hi = 0; $hi < sizeof($hide); $hi++) { if (strpos($file, $hide[$hi]) !== false) { $discard = true; } }
        if ($discard)
            continue;
        if (@filetype($includeurl . $leadon . $file) == "dir") {
            if (!$showdirs)
                continue;
            $n++;
            if ($sort == "date") {
                $key = filemtime($includeurl . $leadon . $file) . $n;
            } else {
                $key = $n;
            }
            $dirs[$key] = $file . "/";
        } else {
            $n++;
            if ($sort == "date") {
                $key = filemtime($includeurl . $leadon . $file) . $n;
            } elseif ($sort == "size") {
                $key = filesize($includeurl . $leadon . $file) . $n;
            } else {
                $key = $n;
            }
            if ($showtypes && !in_array(substr($file, strpos($file, '.') + 1, strlen($file)), $showtypes))
                unset($file);
            if ($file)
                $files[$key] = $file;
            if ($displayindex) {
                if (in_array(strtolower($file), $indexfiles)) {
                    header("Location: " . $leadon . $file);
                    die();
                }
            }
        }
    }
    closedir($handle);
}
if ($sort == "date") { @ksort($dirs, SORT_NUMERIC); @ksort($files, SORT_NUMERIC); } elseif ($sort == "size") { @natcasesort($dirs); @ksort($files, SORT_NUMERIC); } else { @natcasesort($dirs); @natcasesort($files); }
if ($order == "desc" && $sort != "size") { $dirs = @array_reverse($dirs); }
if ($order == "desc") { $files = @array_reverse($files); }
$dirs = @array_values($dirs); $files = @array_values($files);

// PAGE HEADER
pageHeader('sysfiles');

echo "  <body>\n";

// PLUGINS
echo addCode("../../core/plugins/extensions/leftcontextmenu.js"); 
echo addCode("../../core/plugins/notifications/bootbox.min.js");
echo addCode("../../core/plugins/forms/clipboard/clipboard.min.js");

// MESSAGE
$message = '';
if ($successMessage != '') { $c = "success"; $message = $successMessage; }
if ($errorMessage != '') { $c = "danger"; $message = $errorMessage; }	

echo "		<div class='page-content'>\n";

echo "			<div class='content-wrapper'>\n";

// PAGE HEADER	
echo "				<div class='page-header page-header-light'>\n";

echo "					<div class='page-header-content header-elements-inline'>\n";

// PAGE TITLE
echo "						<div class='page-title d-flex'>\n"; 
echo "							<h4><span class='font-weight-semibold'>" . translate("FilesControl") . "</span>\n"; 
echo "							<small class='d-block opacity-75 ml-0'>" . translate("FilesManagerDescription") . "</small></h4>\n";;
echo "						</div>\n";	

// RIGHT BUTTONS
echo "						<div class='header-elements d-flex align-items-center'>\n"; 

	// WRITE ON ROOT
	if (!$writeonroot) { if ($dir != '') { $writeonroot = true; } }

	if ($writeonroot) {

		// UPLOAD BUTTON (TODO: NEW UPLOAD MODULE)
		if ($phpallowuploads) { if (getPermission("FilesUpload", "FilesManager")) { echo "                              <button type='button' class='btn btn-success ml-2' onclick='multiUploadBox()'><i class='icon-upload7'></i><span class='button-text'>" . translate("FilesUpload") . "</span></button>\n"; } }

		// NEW FOLDER BUTTON
		if (getPermission("FolderCreate", "FilesManager")) { echo "                              <button type='button' class='btn btn-primary ml-2' onclick='newFolderBox()'><i class='icon-folder-plus'></i><span class='button-text'>" . translate("NewFolder") . "</span></button>\n"; }

		// DELETE FOLDER BUTTON
		if ($dir != '' and $dir != '/') { if (getPermission("FolderDelete", "FilesManager")) { echo "                              <button type='button' class='btn btn-danger ml-2' onclick=\"deleteFolderBox('" . urlencode(strip_tags($dir)) . "')\"><i class='icon-folder-minus'></i><span class='button-text'>" . translate("FolderDelete") . "</span></button>\n"; } }
		
		// QUICK NOTE
		if (getPermission("QuickNote", "FilesManager")) { echo "                              <button type='button' class='btn btn-info ml-2' onclick=\"quickNoteBox('" . urlencode(strip_tags($dir)) . "')\"><i class='icon-pencil5'></i><span class='button-text'>" . translate("QuickNote") . "</span></button>\n"; }
	}
	
echo "						</div>\n";

echo "					</div>\n"; 

echo "					<div class='breadcrumb-line breadcrumb-line-light header-elements-md-inline'>\n"; 

echo "						<div class='d-flex'>\n";

// FOLDER BREADCRUMBS
$breadcrumbs = explode('/', str_replace($startdir, '', $leadon));
//if (($dirok) or ((sizeof($breadcrumbs)) > 1)) {
	echo "                      <div class='breadcrumb'>\n";
	echo "                          <a href='" . $app . "' class='breadcrumb-item'><i class='icon-home2 mr-2'></i>" . translate("InitialPage") . "</a>\n";
	if ($dirok) { echo "                          <a class='breadcrumb-item d-sm-none' href='" . $self . "' title='" . translate("RootFolder") . "' onclick=\"block('body')\">" . translate("Root") . "</a>\n"; }
	if (($bsize = sizeof($breadcrumbs)) > 0) {
		$sofar = '';
		for ($bi = 0; $bi < ($bsize - 1); $bi++) {
			$sofar = $sofar . $breadcrumbs[$bi] . '/';
			if ($bi != ($bsize - 2)) {
				echo "                          <a class='breadcrumb-item' href='" . $self . "?dir=" . urlencode($sofar) . "'>" . utf8_encode($breadcrumbs[$bi]) . "</a>\n";
			} else {
				echo "                          <a class='breadcrumb-item active'>" . utf8_encode($breadcrumbs[$bi]) . "</a>\n";
			}
		}
	}
	echo "                      </div>\n";
//} else {
//	if ($message == '') { echo "                      <div class='uline'><hr /></div>\n"; }
//}

$baseurl = $self . '?dir=' . urlencode(strip_tags($dir)) . '&amp;';
$fileurl = 'sort=name&amp;order=asc'; $iName = "";
$sizeurl = 'sort=size&amp;order=asc'; $iSize = ""; $sSize = "none";
$dateurl = 'sort=date&amp;order=asc'; $iDate = ""; $sDate = "none";
switch ($sort) {
	case 'name': if ($order == 'asc') { $fileurl = 'sort=name&amp;order=desc'; $iName = "icon-arrow-up22"; } else { $iName = "icon-arrow-down22"; } break;
	case 'size': $sSize = "block"; if ($order == 'asc') { $sizeurl = 'sort=size&amp;order=desc'; $iSize = "icon-arrow-up22"; } else { $iSize = "icon-arrow-down22"; } break;
	case 'date': $sDate = "block"; if ($order == 'asc') { $dateurl = 'sort=date&amp;order=desc'; $iDate = "icon-arrow-up22"; } else { $iDate = "icon-arrow-down22"; } break;
	default: $fileurl = 'sort=name&amp;order=desc'; break;
}
if ($iName == '' && $iSize == '' && $iDate == '') { $iName = "icon-arrow-up22"; }

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

// MESSAGE BOX
if ($message != '') {
    echo "					<div class='alert alert-" . $c . " border-0"; if ($dir == '' or $dir == '/') { echo " root-margin"; } echo "'>\n";
    echo "						<button type='button' class='close' data-dismiss='alert'><span>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n";
    echo "						" . utf8_encode($message) . "\n";
    echo "					</div>\n";
}

// ENTRIES
$fdsize = @sizeof($dirs);
$arsize = @sizeof($files);

if ($arsize != 0 || $fdsize != 0) { 

    // MAIN TABLE
    echo "					<div class='card'><table class='table table-hover datatable-highlight'>\n";

    echo "						<thead>\n";
    echo "							<tr>\n";
    echo "								<th class='width-32 pl-10 d-none d-sm-table-cell'";
    if ($dirok) { echo " onclick=\"block('body'); window.location.href=('?dir=" . urlencode($dotdotdir) . "')\" title='" . translate("ParentFolder") . "'><i class='icon-folder-upload'></i"; }
    echo "></th>\n";
    echo "								<th class='max-width-100 pl-10' onclick=\"block('body'); window.location.href=('" . $baseurl . $fileurl . "')\">" . translate("Name") . " &nbsp; <i class='" . $iName . "'></i></th>\n";
    if ($arsize > 0) { echo "                                      <th class='width-126 text-right pr-10' onclick=\"block('body'); window.location.href=('" . $baseurl . $sizeurl . "')\">" . translate("Size") . "<div class='float-right d-none d-md-flex' style='display:" . $sSize . "'> &nbsp; <i class='" . $iSize . "'></i></div></th>\n"; }
    echo "								<th class='width-160 text-right pr-10 d-none d-sm-table-cell' onclick=\"block('body'); window.location.href=('" . $baseurl . $dateurl . "')\">" . translate("ModifiedIn") . "<div class='float-right d-none d-md-flex' style='display:" . $sDate . "'> &nbsp; <i class='" . $iDate . "'></i></div></th>\n";
    echo "							</tr>\n";
    echo "						</thead>\n";

    echo "						<tbody>\n";

    // FOLDERS
    for ($i = 0; $i < $fdsize; $i++) {

        echo "							<tr onclick=\"block('body'); window.location.href=('" . $self . '?dir=' . urlencode(str_replace($startdir, '', $leadon) . $dirs[$i]) . "');\">\n";
        echo "								<td class='pl-10 d-none d-sm-table-cell'><i class='icon-folder'></i></td>\n";
        echo "								<td class='pl-10'><b>" . utf8_encode(str_replace("/", "", $dirs[$i])) . "</b></td>\n";
        if ($arsize > 0) { echo "                                       <td></td>\n"; }
        echo "								<td class='text-right pr-10 d-none d-sm-table-cell' nowrap>" . date("Y/m/d - h:i", filemtime($includeurl . $leadon . $dirs[$i])) . "h</td>\n";
        echo "							</tr>\n";
    }

    // FILES
    for ($i = 0; $i < $arsize; $i++) {
        $filename = $files[$i];
        if (strlen($filename) > 80) { $filename = substr($files[$i], 0, 76) . '...'; }
        $fileurl = $includeurl . $leadon . $files[$i];
        if ($forcedownloads) {
            $fileurl = '?dir=' . urlencode(str_replace($startdir, '', $leadon)) . '&download=' . urlencode($files[$i]);
            $fileData = urlencode(str_replace($startdir, '', $leadon)) . '#' . urlencode($files[$i]);
        }
        echo "							<tr id='" . $fileData . "' class='fileName'>\n";
        echo "								<td class='pl-10 d-none d-sm-table-cell'><i class='icon-file-empty'></i></td>\n";
        echo "								<td class='file-name pl-10'>" . utf8_encode($filename) . "</td>\n";
        echo "								<td class='text-right pr-10'>" . number_format(@filesize($includeurl . $leadon . $files[$i]), 0, '.', '.') . "Kb</td>\n";
        echo "								<td class='text-right pr-10 d-none d-sm-table-cell' nowrap>" . date("Y/m/d - h:i", @filemtime($includeurl . $leadon . $files[$i])) . "h</td>\n";
        echo "							</tr>\n";
    }

    echo "						<tbody>\n";

    echo "					</table></div>\n";    

} 

echo "					<div class='directory-footer'>\n";

// DIRECTORY RESULT
$dirResult = '';
if ($arsize == 0 && $fdsize == 0) { $dirResult = translate("FilesNotFound"); }
if ($fdsize > 0) { $dirResult = $fdsize . " " . translate("FoldersFound") . " "; }
if ($arsize > 0) { $dirResult .= $arsize . " " . translate("FilesFound"); }
if ($writeonroot) { if (isset($_SESSION['copyFile'])) { if ($_SESSION['copyFile'] != '') { $dirResult .= " <a>" . translate("ClickHereToCoP") . "</a>"; } } }
echo "						<div id='" . urlencode(str_replace($startdir, '', $leadon)) . "' class='fileName directory-result float-left'>" . $dirResult . "</div>\n";
    
// DISK SPACE
$free = (disk_total_space('.') - disk_free_space('.'));
echo "							<div class='directory-result float-right'>\n";
echo "									" . translate("Free") . ": <span class='text-success'>" . number_format((disk_free_space('.')/1000000), 0, '.', '.') . "Mb</span> | \n";
echo "									" . translate("Used") . ": <span class='text-danger'>" . number_format(($free/1000000), 0, '.', '.') . "Mb</span> | \n";
echo "									" . translate("Total") . ": " . number_format((disk_total_space('.')/1000000), 0, '.', '.') . "Mb. \n";
$mx = getMaximumFileUploadSize();
if (is_numeric($mx)) {
    $mx = number_format($mx, 0, '.', '.');
    if ($phpmaxsize) { echo "									<span title='" . $mx . "Kb'><span class='phpmaxsize'>" . translate("MaxFileUpload") . ": " . $phpmaxsize . "b" . "/" . translate("File") . "</span>.</span>\n"; }
}
echo "							</div>\n";

echo "							<div style='clear:both'></div>\n";

echo "						</div>\n";

echo "					</div>\n";

// DEMO MESSAGE
if (DEMO) { echo "				<div class='text-danger text-center m-10'>" . translate('DemoNoSave') . "</div>"; }

// CONTEXT MENUS
$copyFile = ''; if ($_SESSION['copyFile'] != '') { $copyFile = str_replace($startdir, '', $_SESSION['copyFile']); }

echo "					<div id='OptionMenu' class='context-menu display-none'>\n";
echo "						<ul>\n";
if ($arsize > 0) { 
    //echo "	              <li id='fileopen'><i class='icon-eye context-icon'></i>" . translate("Open") . "</li>\n";
    if (getPermission("FileCopy", "FilesManager")) { echo "						<li id='filecopy'><i class='icon-copy context-icon'></i>" . translate("FileCopy") . "</li>\n"; }
}
if (getPermission("FileCopy", "FilesManager")) { 
    if ($_SESSION['copyFile'] != '') {
        echo "							<li id='filepaste' title='" . translate("PasteFrom") . ": " . utf8_encode($copyFile) . "...'><i class='icon-paste context-icon'></i>" . translate("FilePaste") . "</li>\n";
        echo "							<li id='filemove' title='" . translate("MoveFrom") . ": " . utf8_encode($copyFile) . "...'><i class='icon-paste2 context-icon'></i>" . translate("FileMove") . "</li>\n";
    } else {
        echo "							<li><i class='icon-paste context-icon' style='color:#ddd'></i><span style='color:#ccc'>" . translate("FilePaste") . "</span></li>\n";
        echo "							<li><i class='icon-paste2 context-icon' style='color:#ddd'></i><span style='color:#ccc'>" . translate("FileMove") . "</span></li>\n";
    }
}
if ($arsize > 0) { 
    if (getPermission("FileRename", "FilesManager")) { echo "							<li id='filerename'><i class='icon-pencil7 context-icon'></i>" . translate("FileRename") . "</li>\n"; }
    if (getPermission("FileDelete", "FilesManager")) { echo "							<li id='filedelete'><i class='icon-file-minus context-icon'></i>" . translate("FileDelete") . "</li>\n"; }
    if (getPermission("FileDownload", "FilesManager")) {echo "							<li id='filedownload'><i class='icon-download7 context-icon'></i>" . translate("FileDownload") . "</li>\n"; }
    if (getPermission("FileShare", "FilesManager")) { echo "							<li id='fileshare'><i class='icon-share2 context-icon'></i>" . translate("FileShare") . "</li>\n"; }
    //if (getPermission("FileProperties", "FilesManager")) { echo "						<li id='fileproperties'><i class='icon-cog4 context-icon'></i>" . translate("Properties") . "</li>\n"; }
}
echo "						</ul>\n";
echo "					</div>\n";

echo "					<script>\n";

// MULTI UPLOAD BOX
echo "          function multiUploadBox() {\n";
//echo "              var wC = \"<iframe src='" . "../../core/plugins/uploaders/uploadify/uploader.php?folder=../../" . urlencode(utf8_encode($startdir . $dir)) . "' style='width:100%; height:400px; border:0'></iframe>\"\n"; // UPLOADIFY
echo "              var wC = \"<iframe src='" . "../../core/plugins/uploaders/jqueryupload/jqueryupload.php?folder=" . base64_encode("../../" . $startdir . $dir) . "&extensions=" . $extensions . "&select=" . translate('SelectFiles') . "&start=" . translate('StartUpload') . "&cancel=" . translate('CancelAll') . "&remove=" . translate('Remove') . "&processing=" . translate('Processing') . "&conclude=" . translate('RefreshFolder') . "' class='iFrame'></iframe>\"\n"; // JQUERYUPLOAD
echo "              bootbox.dialog({ message: wC, title: '<b>" . translate('FilesUpload') . "</b>', backdrop: true, buttons: {\n";
//echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' }\n"; // CANCEL BUTTON
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default', callback: function() { window.parent.block('body'); window.location.reload(); } }\n"; // CONCLUDE BUTTON
echo "              }});\n";
echo "          };\n";

// QUICK NOTE BOX
echo "          function quickNoteBox(n) {\n";
echo "              var wC = \"<form name='quickNoteForm' method='post' accept-charset='UTF-8'>\"\n";
echo "              wC += \"    <div class='form-group'>\"\n";
echo "              wC += \"        <label for='quickNoteName' class='control-label'>" . translate('FileName') . "</label>\"\n";
echo "              wC += \"        <input type='text' class='form-control' name='quickNoteName' id='quickNoteName' autofocus />\"\n";
echo "              wC += \"    </div>\"\n";
echo "              wC += \"    <div class='form-group'>\"\n";
echo "              wC += \"        <label for='quickNoteText' class='control-label'>" . translate('Message') . "</label>\"\n";
echo "              wC += \"        <textarea class='form-control quickNoteText' name='quickNoteText'id='quickNoteText'></textarea>\"\n";
echo "              wC += \"    </div>\"\n";
echo "              wC += \"</form>\"\n";
echo "              bootbox.dialog({ message: wC, title: '<b>" . translate('QuickNote') . "</b>', backdrop: true, buttons: {\n";
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' },\n";
echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('Save') . "', className: 'btn-success', callback: function() { block('body'); document.quickNoteForm.submit(); } }\n";
echo "              }});\n";
echo "          };\n";

// NEW FOLDER BOX
echo "          function newFolderBox() {\n";
echo "              var wC = \"<form name='newFolderForm' method='post' accept-charset='UTF-8'>\"\n";
echo "              wC += \"    <div class='form-group'>\"\n";
echo "              wC += \"        <label for='folderName' class='control-label'>" . translate('FolderName') . "</label>\"\n";
echo "              wC += \"        <input type='text' class='form-control' name='folderName' id='folderName' placeholder='" . translate('NewFolder') . "...' autofocus />\"\n";
echo "              wC += \"    </div>\"\n";
echo "              wC += \"</form>\"\n";
echo "              bootbox.dialog({ message: wC, title: '<b>" . translate('NewFolder') . "</b>', backdrop: true, buttons: {\n";
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' },\n";
echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('Confirm') . "', className: 'btn-success', callback: function() { block('body'); document.newFolderForm.submit(); } }\n";
echo "              }});\n";
echo "          };\n";

// RENAME FILE BOX
echo "          function renameFileBox(n) {\n";
echo "              var wC = \"<form name='renameFileForm' method='post' accept-charset='UTF-8'>\"\n";
echo "              wC += \"    <div class='form-group'>\"\n";
echo "              wC += \"        <label for='fileName' class='control-label'>" . translate('FileName') . "</label>\"\n";
echo "              wC += \"        <input type='hidden' name='fileOldName' id='fileOldName' value='\" + n + \"' />\"\n";
echo "              wC += \"        <input type='text' class='form-control' name='fileNewName' id='fileNewName' value='\" + unescape(n).replaceAll('+', ' ') + \"' autofocus />\"\n";
echo "              wC += \"    </div>\"\n";
echo "              wC += \"</form>\"\n";
echo "              bootbox.dialog({ message: wC, title: '<b>" . translate('Rename') . "</b>', backdrop: true, buttons: {\n";
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' },\n";
echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('Rename') . "', className: 'btn-success', callback: function() { block('body'); document.renameFileForm.submit(); } }\n";
echo "              }});\n";
echo "          };\n";

// CONFIRM DELETE FOLDER
echo "          function deleteFolderBox(i) {\n";
echo "              var fn = unescape(i).replace(/\+/gi, ' ');\n";
echo "              bootbox.dialog({ message: '" . translate('ConfirmFolderDelete') . " \"<b>' + fn + '</b>\" " . translate('AndAllContent') . " ?', title: '<b>" . translate('FolderDelete') . "</b>', buttons: {\n";
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' },\n";
echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('FolderDelete') . "', className: 'btn-danger', callback: function() { block('body'); window.location.href=('?dir=' + i + '&deleteFolder=' + i); } }\n";
echo "              }});\n";
echo "          }\n";

// CONFIRM DELETE FILE
echo "          function confirmBox(i, n) {\n";
echo "              var fn = unescape(n).replace(/\+/gi, ' ');\n";
echo "              bootbox.dialog({ message: '" . translate('ConfirmDelete') . " \"<b>' + fn + '</b>\" ?', title: '<b>" . translate('FileDelete') . "</b>', buttons: {\n";
echo "                  cancelBtn: { label: '<i class=\"icon-chevron-left mr-2\"></i>" . translate('Cancel') . "', className: 'btn-default' },\n";
echo "                  confirmBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('FileDelete') . "', className: 'btn-danger', callback: function() { block('body'); window.location.href=('?dir=' + i + '&delete=' + n); } }\n";
echo "              }});\n";
echo "          };\n";

// SHARE BOX
echo "          function shareBox(i, n) {\n";
echo "				var clipboard = new Clipboard('#bText');\n";
echo "              var wC = \"    <div class='form-group'>\"\n";
echo "              wC += \"        <label for='fileName' class='control-label'>" . translate('CopyToClipboard') . "</label>\"\n";
echo "              wC += \"            <div class='input-group'>\"\n";
echo "              wC += \"                <input type='text' maxlength='255' class='cText form-control' value='" . $downloadApp . "\" + encodeURI(b64EncodeUnicode('" . utf8_encode(str_replace($startdir, '', $leadon)) . "' + n)) + \"' id='cText' />\"\n";
echo "              wC += \"                <span class='input-group-btn'><button class='btn btn-default form-buttons' type='button' data-clipboard-target='#cText' id='bText'><i class='icon-copy mr-2'></i>" . translate('Copy') . "</button></span>\"\n";
echo "              wC += \"            </div>\"\n";
echo "              wC += \"    </div>\"\n";
echo "              bootbox.dialog({ message: wC, title: '<b>" . translate('ShareLink') . "</b>', backdrop: true, buttons: { \n";
echo "                  cancelBtn: { label: '<i class=\"icon-check mr-2\"></i>" . translate('Close') . "', className: 'btn-default' }\n";
echo "              }});\n";
echo "          };\n";

echo "				</script>\n";

echo "			</div>\n"; // /content-wrapper

echo "		</div>\n"; // /page-content

echo "	</body>\n";

// SAVE EVENT
$vf = $dir; if ($dir == '' || $dir == '/') { $vf =translate('RootFolder'); }
if (!isset($_GET['a'])) { logWrite("View", "FilesManager", '', '', $vf); }

// DATABASE CLOSE
closeDB();

// PAGE FOOTER
pageFooter();

// DELETE FOLDER
function deleteFolder($directory, $empty=FALSE) {
    if (substr($directory,-1) == '/') { $directory = substr($directory,0,-1); }
    if (!file_exists($directory) || !is_dir($directory)) {
        return FALSE;
        } 
        elseif (is_readable($directory)) {
            $handle = opendir($directory);
            while (FALSE !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    $path = $directory.'/'.$item;
                    if (is_dir($path)) {
                        deleteFolder($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($handle);
            if($empty == FALSE) { if (!rmdir($directory)) { return FALSE; } }
        }
    return TRUE;
}

// UPLOAD SIZE
function convertPHPSizeToBytes($sSize) {  
    if ( is_numeric( $sSize) ) {
       return $sSize;
    }
    $sSuffix = substr($sSize, -1);  
    $iValue = substr($sSize, 0, -1);  
    switch(strtoupper($sSuffix)){  
    case 'P':  
        $iValue *= 1024;  
    case 'T':  
        $iValue *= 1024;  
    case 'G':  
        $iValue *= 1024;  
    case 'M':  
        $iValue *= 1024;  
    case 'K':  
        $iValue *= 1024;  
        break;  
    }  
    return $iValue;  
}  

function getMaximumFileUploadSize() {  
    return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));  
} 