<?php

// FUNCTIONS.PHP 1.0 (2021/09/01)

// SESSION DEFAULT
session_start();
//ini_set('session.cookie_lifetime', 0);

// FULL ERROR MESSAGES
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);

// CACHING PREVENT
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

// TIMEZONE
setlocale(LC_ALL, LOCALE, CODIFIC1, CODIFIC2, LONGLANGUAGE);
date_default_timezone_set(TIMEZONE);

// DEBUG
//$date = '2011-05-08'; echo strftime("%A, %d de %B de %Y", strtotime($date)); exit;

// CURRENCY
//setlocale(LC_MONETARY, LOCALE);

// GENERIC RND SECOND
$rndsec = date('s');

// HOME LINK
$app = getPreferenceValue("OppeningPage");
if ($app == "apps/default/default.php" || $app == '') { $app = "../../apps/default/default.php"; }
if (!file_exists($app)) { $app = "#"; }

// BROWSER FUNCTIONS

// IS BROWSER
function isBrowser() {

    $i = false;
    if (isset($_SESSION['ISBROWSER'])) { if ($_SESSION['ISBROWSER'] == '1') { $i = true; } } else { $_SESSION['ISBROWSER'] = '0'; }
    if (isset($_GET['M'])) { if ($_GET['M'] == '1') { $_SESSION['ISBROWSER'] = '1'; $i = true; } }
    if (isset($_SERVER['HTTP_USER_AGENT'])) { if (left($_SERVER['HTTP_USER_AGENT'], 7) == "MAESTRO") { $_SESSION['ISBROWSER'] = '1'; $i = true; } }

    // VIDEO MODE = FAKE
    if (getOptionValue("CameraVideoFake") == '1') { $i = false; }

    return $i;

}

// IS HTTPS
function isHttps() {

	if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {

		return true;
	}
	return false;
}

// BROWSER VALUES
function getBrowser() {

    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'MAC'; }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'Windows'; }
    if (strpos(strtolower($u_agent), 'ipad')) { $platform = 'iPad'; }
    if (strpos(strtolower($u_agent), 'iphone')) { $platform = 'iPhone'; }

    if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) { }

    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
            $version= $matches['version'][0];
        } else {
            $version= $matches['version'][1];
        }
    } else {
        $version= $matches['version'][0];
    }

    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}

// STRING FUNCTIONS

// LEFT
function left($str, $length) { return substr($str, 0, $length); }

// RIGHT
function right($str, $length) { return substr($str, -$length); }

// CLEAN
function clean($s) { return str_replace(array("<", ">", "\\", "/", "=", "'", "?"), "", $s); }

// OBJECT TO ARRAY
function object2array($object) { return @json_decode(@json_encode($object),1); }

// MESSAGE
function message($message, $type = 'danger', $exit = 0) {

	if ($exit) {
		echo "	<body>\n";
	}

	echo "					<div class='alert alert-" . $type . " border-0'>\n";
	if (!$exit) { echo "						<button type='button' class='close' data-dismiss='alert'><span>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n"; }
	echo "						" . translate($message) . "\n";
	echo "					</div>\n";

	if ($exit) {
		echo "	</body>\n";
		echo "</html>\n";
		exit;
	}

}

// DATE CHECK
function isDate($date) { if (date('Y-m-d H:i:s', strtotime($date)) == $date) { return true; } else { return false; } }

// MISC FUNCTIONS

// ENCRYPTOR
function encryptor($text, $mode = 0) {

    $salt = 'N3M35153H0N0M3D43MPR354';

    $exit = '';
    if ($mode != 0) { $text = base64_decode($text); }
    for ($x = 0; $x < strlen($text); $x++){
        $y = ord(substr($text, $x));
        if ($mode == 0) {
            $y += ord(substr($salt, (($x + 1) % strlen($salt))));
            $exit .= chr($y & 0xFF);
        } else {
            $y -= ord(substr($salt, (($x + 1) % strlen($salt))));
            $exit .= chr(abs($y) & 0xFF);
        }
    }
    if ($mode == 0) { $exit = base64_encode($exit); }
    return $exit;
}

// TRANSLATE
function translate($text, $save = 1, $table = "syslanguages") {

    // DEBUG
    //return $text;

	$text = trim($text);

	if ($text != '') {

		// GET LANGUAGE DEFINITION
		$sysLanguage = DEFAULTLANGUAGE;

		if (isset($_COOKIE['sysLanguage'])) { $sysLanguage = $_COOKIE['sysLanguage']; }

		if (isset($_SESSION['loginKey'])) {
			if (!strpos($_SESSION['loginKey'], '=')) {
				$l = explode('=', encryptor($_SESSION['loginKey'], 1));
			}
		}

		if (!isset($l[2])) { $sysLanguage = DEFAULTLANGUAGE; }

		if ($r = select("BR, EN, FR, ES", $table, "where ItemCode='" . $text . "'")) {

			// GET TRANSLATION
			if ($r[0][strtoupper($sysLanguage)] != '') { $text = $r[0][strtoupper($sysLanguage)]; }

		} else {

			if ($save == 1) {

				// SAVE NEW EXPRESSION
				$t = array();
				$t["itemCode"] = $text;
				$t["EN"] = $text;
				if (!insert($table, $t)) {
					echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [" . $sql . "]</div>\n";
				}

			}

		}

		return $text;

	}
}

// LOG WRITE
function logWrite($actionCode, $actionGroup = 'Others', $eventTable = '', $eventRecord = '', $eventContent = '', $userID = '', $companyID = '') {

	if ($actionCode != '') {

		// DEBUG
		//echo "USERID [" . USERID . "] COMPANYID [" . COMPANYID . "]<br />"; //exit;

		if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return 0; } }
		if ($companyID == '') { if (defined("COMPANYID")) { $companyID = COMPANYID; } else { $companyID = getUserCompanyID($userID); } }

		$t = array();
		$t["UserID"] = $userID;
		$t["CompanyID"] = $companyID;
		$t["ActionCode"] = $actionCode;
		$t["ActionGroup"] = $actionGroup;
		if ($eventTable != '') { $t["EventTable"] = $eventTable; }
		if ($eventRecord != '') { $t["EventRecord"] = $eventRecord; }
		if ($eventContent != '') { $t["EventContent"] = $eventContent; }
		//insert("sysevents", $t);
		if (!insert("sysevents", $t)) {
			echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [LOGWRITE]</div>\n";
		}

		// TEMP
		if (!$r = select("ActionCode", "sysactions", "where ActionCode='" . $actionCode . "'")) {

			// SAVE NEW ACTION
			$t = array();
			$t["ActionCode"] = $actionCode;
			$t["ActionGroup"] = $actionGroup;
			//insert("sysactions", $t);
			if (!insert("sysactions", $t)) {
				echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [SYSACTIONS]</div>\n";
			}

		}
	}
}

// GET OPTION (TODO: CREATE NEW IF NOT EXIST)
function getOptionValue($optionName, $companyID = '') {

    $optionValue = '';

	if ($companyID == '') { if (defined("COMPANYID")) { $companyID = COMPANYID; } else { $companyID = '1'; } }

    $z = select("OptionValue", "sysoptions", "where (OptionName='" . $optionName . "' and OptionActive=1 and CompanyID='" . $companyID . "')");

    if ($z) {

		$optionValue = $z[0]["OptionValue"];

	} else {

		// SAVE NEW OPTION
		$t = array();
		$t["OptionName"] = $optionName;
		$t["OptionGroup"] = "Others";
		$t["OptionIcon"] = "cog";
		$t["OptionValue"] = "1";
		$t["OptionCheckBox"] = "1";
		$t["CompanyID"] = $companyID;
		//insert("sysoptions", $t);
		if (!insert("sysoptions", $t)) {
			echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [SYSOPTIONS]</div>\n";
		}

	}

    return $optionValue;
}

// GET PREFERENCE (TODO: CREATE NEW IF NOT EXIST)
function getPreferenceValue($preferenceName, $userID = '') {

    $preferenceValue = '';

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return ''; } }

    $z = select("PreferenceValue", "syspreferences", "where (PreferenceName='" . $preferenceName . "' and PreferenceActive=1 and UserID=" . $userID . ")");

    if (@$z[0]["PreferenceValue"]) {

		$preferenceValue = $z[0]["PreferenceValue"];

		return $preferenceValue;

	} else {

		return getOptionValue($preferenceName);

	}
}

// GET PERMISSION
function getPermission($actionCode, $actionGroup = 'Others', $userID = '', $userGroupID = '', $companyID = '') {

	// DEBUG
	//echo "USERID [" . USERID . "] COMPANYID [" . COMPANYID . "] USERGROUPID [" . USERGROUPID . "]<br />"; //exit;

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return 0; } }
	if ($userGroupID == '') { if (defined("USERGROUPID")) { $userGroupID = USERGROUPID; } else { $userGroupID = getUserGroupID($userID); } }
	if ($companyID == '') { if (defined("COMPANYID")) { $companyID = COMPANYID; } else { $companyID = getUserCompanyID($userID); } }

    // DEVELOPER
    //if ($userGroupID == '1') { return 1; }

    $response = 0;

    // CHECK IF PERMISSION EXISTS
    if (!$r = select("ActionCode", "syspermissions", "where (ActionCode='" . $actionCode . "' and ActionGroup='" . $actionGroup . "')")) {

        // CHECK IF ACTION EXISTS
        if (!$r = select("ActionCode", "sysactions", "where (ActionCode='" . $actionCode . "' and ActionGroup='" . $actionGroup . "')")) {

            // SAVE NEW ACTION
            $t = array();
            $t["ActionCode"] = $actionCode;
            $t["ActionGroup"] = $actionGroup;
			if (!insert("sysactions", $t)) {
				echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [SYSACTIONS]</div>\n";
			}

        }

        // NEW PERMISSION SETTED TO 1 (ALLOW)
        $u = array();
        $u["ActionCode"] = $actionCode;
        $u["ActionGroup"] = $actionGroup;
        $u["UserGroupID"] = $userGroupID;
		$u["CompanyID"] = $companyID;
        $u["Allow"] = '1';
        if (insert("syspermissions", $u)) { $response = 1; }

    } else {

        // GET PERMISSION BY GROUP
        if ($r = select("Allow", "syspermissions", "where (ActionCode='" . $actionCode . "' and ActionGroup='" . $actionGroup . "' and CompanyID=" . $companyID . " and UserGroupID=" . $userGroupID . ")")) {
            
            if (trim($r[0]['Allow']) == '1') { $response = 1; } 
            
        } else {
            
            // NEW PERMISSION SETTED TO 1 (ALLOW)
            $u = array();
            $u["ActionCode"] = $actionCode;
            $u["ActionGroup"] = $actionGroup;
            $u["UserGroupID"] = $userGroupID;
            $u["CompanyID"] = $companyID;
            $u["Allow"] = '1';
            if (insert("syspermissions", $u)) { $response = 1; }
        
        }

        // GET PERMISSION BY USER
        if ($r = select("Allow", "syspermissions", "where (ActionCode='" . $actionCode . "' and ActionGroup='" . $actionGroup . "' and CompanyID=" . $companyID . " and UserID=" . $userID . ")")) { if (trim($r[0]['Allow']) == '1') { $response = 1; } }

    }

    return $response;
}

// GET USER NAME
function getUserName($userID = '') {

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return '?'; } }

    if ($r = select("UserName", "sysusers", "where UserID=" . $userID)) { if (trim($r[0]['UserName']) == '') { return ''; } else { return $r[0]['UserName']; } }
}

// GET USER AVATAR
function getUserAvatar($userID = '') {

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return ''; } }

    if ($r = select("UserAvatar", "sysusers", "where UserID=" . $userID)) { if (trim($r[0]['UserAvatar']) == '') { return ''; } else { return $r[0]['UserAvatar']; } }
}

// GET USER GROUP
function getUserGroupID($userID = '') {

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return 0; } }

    if ($r = select("UserGroupID", "sysusers", "where UserID=" . $userID)) { if (trim($r[0]['UserGroupID']) == '') { return 0; } else { return $r[0]['UserGroupID']; } }
}

// GET USER COMPANY
function getUserCompanyID($userID = '') {

	if ($userID == '') { if (defined("USERID")) { $userID = USERID; } else { return 0; } }

    if ($r = select("CompanyID", "sysusers", "where UserID=" . $userID)) { if (trim($r[0]['CompanyID']) == '') { return 0; } else { return $r[0]['CompanyID']; } }
}

// GET GROUP NAME
function getUserGroupName($userGroupID = '') {

	if ($userGroupID == '') { if (defined("USERGROUPID")) { $userGroupID = USERGROUPID; } else { return '?'; } }

    if ($r = select("UserGroupName", "sysusersgroups", "where UserGroupID=" . $userGroupID)) { if (trim($r[0]['UserGroupName']) == '') { return ''; } else { return $r[0]['UserGroupName']; } }
}

// GET COMPANY NAME
function getCompanyName($companyID = '') {

	if ($companyID == '') { if (defined("COMPANYID")) { $companyID = COMPANYID; } else { return '?'; } }

    if ($r = select("CompanyName", "syscompanies", "where CompanyID=" . $companyID)) { if (trim($r[0]['CompanyName']) == '') { return ''; } else { return $r[0]['CompanyName']; } }
}

// GET APP GROUP NAME
function getAppGroupName($appID) {

    if ($r = select("ItemName", "sysappsgroups", "where ItemID=" . $appID)) { if (trim($r[0]['ItemName']) == '') { return ''; } else { return $r[0]['ItemName']; } }
}

// CHECK APP
function checkApp($appName, $companyID = '') {

	if ($companyID == '') { if (defined("COMPANYID")) { $companyID = COMPANYID; } else { return 0; } }

    if ($r = select("AppActive", "sysapps", "where (CompanyID='" . COMPANYID . "' and AppName='" . $appName . "')")) { if (($r[0]['AppActive'] == '0') || ($r[0]['AppActive'] == '2')) { return 0; } else { return 1; } } else { return 1; }
}

// SEND MAIL
function sendMail($toName, $toMail, $fromName, $fromMail, $subject, $message) {

    //$subject = utf8_decode($_POST["subject"]);
    $headers = "To: " . $toName . " <" . $toMail . ">\n";
    $headers .= "From: " . $fromName . " <" . $fromMail . ">\n";
    $headers .= "Reply-To: " . $fromName . " <" . $fromMail . ">\n";
    //$headers .= "CC: NAME <EMAIL>\n";
    //$headers .= "BCC: NAME <EMAIL>\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\n";

    try {

        if (@!mail($toMail, $subject, $message, $headers, "-r" . $fromMail)) {

            $headers .= "Return-Path: " . $fromMail . "/n";
            @mail($toMail, $subject, $message, $headers);

        } else {

            return false;

        }

        return true;

    } catch (Exception $e) {

        return false;
    }
}

// EXIT SYSTEM
function exitSystem($message = '') {

	$index = "index.php";
	if (!file_exists($index)) { $index = "../index.php"; }
	if (!file_exists($index)) { $index = "../../index.php"; }

    echo "<!DOCTYPE html><html><head><script>document.write(\"<form action='" . $index . "' method='post' target='_top' id='redirectForm'><input type='hidden' name='a' value='3' /><input type='hidden' name='m' value='" . $message . "' /></form>\"); document.getElementById('redirectForm').submit();</script></head><body></body></html>"; exit;
}

// JS ERROR ALERT
function errorBox($message) {

    echo "<html><head><script>alert('" . utf8_encode($message) . "');</script></head><body></body>";
}

// VIDEO STREAMMING
function videoStream($videoSource, $videoClass = 'video-stream', $id = 'vs', $w = '226', $h = '164' ) {

    if (isIE) {

        // VLC PLUGIN
        echo "              <object classid='clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921' codebase='http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab' width='" . $w . "' height='" . $h . "'>\n";
        echo "                  <param name='Src' value='" . $videoSource. "' />\n";
        echo "                  <param name='ShowDisplay' value='False' />\n";
        echo "                  <param name='AutoLoop' value='False' />\n";
        echo "                  <param name='AutoPlay' value='True' />\n";
        echo "                  <param name='wmode' value='Opaque'>\n";
        echo "                  <param name='toolbar' value='false'>\n";
        echo "                  <param name='windowless' value='Opaque'>\n";
        echo "                  <embed target='" . $videoSource . "' type='application/x-vlc-plugin' pluginspage='http://www.videolan.org' autoplay='yes' toolbar='false' menu='false' loop='false' wmode='opaque' windowless='opaque' title='VLC Video Plugin' width='" . $w . "' height='" . $h . "' />\n";
        echo "              </object>\n";

    } else {

        // TAG IMAGE
        echo "              <iframe class='display-none' src='" . $videoSource . "'></iframe>\n";
        echo "              <img id='" . $id . "' src='' class='" . $videoClass . "' />\n";
        //echo "              <script>$('#" . $id . "').error(function() { $('#" . $id . "').attr('src', '../../assets/images/error/novideo.png') }).attr('src', '" . $videoSource . "').load(function(){ this.width; });</script>\n";
        echo "              <script>$('#" . $id . "').error(function() { $('.widget-video').hide() }).attr('src', '" . $videoSource . "').load(function(){ this.width; });</script>\n";

    }
}

// LAYOUT FUNCTIONS

// PAGE HEADER
function pageHeader($code = '', $theme = '') {

    global $assets;

    echo "<!DOCTYPE html>\n";
    echo "<html lang='en'>\n";

    echo "	<head>\n";

    echo "      <meta charset='utf-8' />\n";
    echo "      <meta http-equiv='X-UA-Compatible' content='IE=edge' />\n";
    echo "      <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />\n";

    echo "      <title>" . SYSTITLE . "</title>\n";

	// FONT
	//echo "      <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet'>\n";
	echo "      <link href='https://fonts.googleapis.com/css?family=Lato:400,400i,700,900' rel='stylesheet'>\n";

    // FONT ICONS
    echo addCode("../../assets/icons/icomoon/styles.css");

	// WINDOW PLUGIN
	echo addCode("../../core/plugins/window/window.css");

    // CSS
    echo addCode("../../core/vendor/bootstrap/bootstrap.min.css");
	echo addCode("../../core/vendor/limitness/css/bootstrap_limitless.min.css");
    echo addCode("../../core/vendor/limitness/css/layout.min.css");
    echo addCode("../../core/vendor/limitness/css/components.min.css");
	echo addCode("../../core/vendor/limitness/css/colors.min.css");

	// CUSTOM CSS LOAD
	$css = "../../assets/css/custom-template.css";
	if (file_exists("../../assets/css/custom.css")) { $css = "../../assets/css/custom.css"; }
	echo addCode($css);

	// THEME
	if ($theme == '') { $theme = getPreferenceValue("SysTheme"); if (strtolower($theme) == "default") { $theme = ''; } }
	if ($theme != '') { echo addCode("../../assets/css/themes/" . strtolower($theme) . ".css"); }

	// SPECIFIC CSS
    if ($code != '') { echo addCode("assets/css/" . $code . ".css"); }

	// JS LIBRARIES
	echo addCode("../../core/vendor/jquery/jquery.min.js");
    echo addCode("../../core/vendor/bootstrap/bootstrap.bundle.min.js");
    //echo addCode("../../core/plugins/loaders/pace.min.js");
    echo addCode("../../core/plugins/ui/nicescroll.min.js");
    echo addCode("../../core/plugins/loaders/blockui.min.js");

	echo addCode("../../core/plugins/window/window.js");
    echo addCode("../../core/plugins/notifications/bootbox.min.js");
	echo addCode("../../core/plugins/extensions/textblock.min.js");
    echo addCode("../../core/vendor/limitness/js/app.js");
	echo addCode("../../assets/js/custom.js");

	// SPECIFIC JS
    if ($code != '') { echo addCode("assets/js/" . $code . ".js"); }

    echo "	</head>\n";
}

// PAGE FOOTER
function pageFooter() {

    echo "</html>";
}

// ADD INCLUDES
function addCode($file, $noBuffer = "1") {

    if (file_exists($file)) {
        
        switch (strtolower(substr(strrchr($file, '.'), 1))) { 
        
            case "js": if ($noBuffer == "1") { $file .= "?d=" . date('is'); } return "		<script src='" . $file . "'></script>\n"; break;
            
            case "css": if ($noBuffer == "1") { $file .= "?d=" . date('is'); } return "		<link href='" . $file . "' rel='stylesheet' />\n"; break;
            
            default: include $file; break;        
        }
        
    } else {
        
        return "		<!-- F [ " . $file . " ] -->\n";
    }
}

// ERROR
function dataErrorBox($message, $mode = 0) {

    $errorBox = '';

    if ($mode == 3) { $errorBox = "<html><head><title>SYSTEM ERROR!</title>"; }

    if ($mode == 2 || $mode == 3) { $errorBox .= "</head><body>"; }

    $errorBox .= "<div style='padding:40px'><div style='padding:20px; color:#fff; background-color:#f00; border-radius:6px; text-align:center; font-family:arial, helvetica, sans-serif; font-weight:bold'>" . utf8_encode($message) . "</div></div>";

    if ($mode == 1 || $mode == 2 || $mode == 3) { $errorBox .= "</body></html>"; }

    return $errorBox;
}

// DATABASE FUNCTIONS

$conn = '';

// DB OPEN
function openDB($h = '', $u = '', $p = '', $n = '') {

	if ($h == '') { $h = DATABASEHOST; }
    if ($u == '') { $u = DATABASEUSER; }
    if ($p == '') { $p = DATABASEPASS; }
    if ($n == '') { $n = DATABASENAME; }

	global $conn;

	$conn = @mysqli_connect($h, $u, $p, $n);

	if ($conn) {

		//if (!isset($_SESSION["connected"])) { $_SESSION["connected"] = "1"; }

	} else {
		// DEBUG
		//echo "DATABASEHOST [" . $h . "] DATABASEUSER [" . $u . "] DATABASEPASS [" . $p . "] DATABASENAME [" . $n . "]"; exit;
		//$m = "Database Error: N [ " . mysqli_connect_errno() . " ] - E [ " . mysqli_connect_error() . " ]";

		$m = "Database Server Connection Error!";
        if (isset($_SESSION['loginKey']) && $_SESSION['loginKey'] != '') { errorBox($m); } else { echo dataErrorBox($m, 3); }

		exit;
    }
}

// DB CLOSE
function closeDB() { global $conn; mysqli_close($conn); }

// GENERIC QUERY
function query($sql, $debug = 0) {

	// DEBUG
	if ($debug == 1) { echo $sql; exit; }

    global $conn; $return = 0; charset(); if ($d = $conn->query($sql)) { $return = $d; }

	return $return;
}

// SELECT
function select($fields, $from, $where = '', $debug = 0) {

    global $conn; $return = 0; charset();

	$sql = "SELECT {$fields} FROM {$from} {$where}";

	// DEBUG
	if ($debug == 1) { echo $sql; exit; }

    if ($d = @$conn->query($sql)) {

        if ($d->num_rows > 0) { while ($s = $d->fetch_assoc()) { $z[] = $s; } $return = $z; } else { $return = $d->fetch_assoc(); } unset($z, $s); $d->free();
    }

    return $return;
}

// INSERT
function insert($table, $array, $debug = 0) {

    global $conn; $return = 0; $fields = ''; $values = '';

	foreach ($array as $key => $value) {

		if ($key != '') { $fields .= $key . ","; $values .= "'" . $value . "',"; }

	}
    $fields = left($fields, strlen($fields) - 1);
    $values = left($values, strlen($values) - 1);

    charset();

    $sql = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";

	// DEBUG
	if ($debug == 1) { echo $sql; exit; }

	if (@$conn->query($sql)) {

		unset($table, $array, $fields, $values); $return = $conn->insert_id; $return = 1;

	} else {

        // DEBUG ERROR ALERT
        echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [" . $sql . "]</div>\n";

    }

	return $return;
}

// UPDATE
function update($table, $array, $where = '', $debug = 0) {

	global $conn; $return = 0; $data = array();

	foreach ($array as $key => $value) {

		$data[] = str_replace('==', '=', escape($key) . "='" . escape($value) . "'");

	}

	$data = implode(', ', $data); charset();

	$sql = "UPDATE {$table} SET {$data} {$where}";

	// DEBUG
	if ($debug == 1) { echo $sql; exit; }

    if ($conn->update = $conn->query($sql)) {

        unset($table, $array, $where, $data); $return = 1;

	} else {

        // DEBUG ERROR ALERT
        echo "<div class='alert alert-danger no-border'>" . __FILE__ . ": [" . $sql . "]</div>\n";

    }

    return $return;
}

// DELETE
function delete($table, $where = '', $debug = 0) {

    global $conn; $return = 0;

    $sql = "DELETE FROM {$table} {$where}";

	// DEBUG
	if ($debug == 1) { echo $sql; exit; }

	if ($conn->query($sql)) { unset($table, $where); $return = 1; }

	return $return;
}

// DB ESCAPE
function escape($data) { global $conn; return @$conn->real_escape_string($data); }

// DB CHARSET
function charset($c = "UTF8") {

	//if ($_SESSION["connected"] == "1") {

		global $conn; @$conn->query("SET NAMES '" . $c . "'"); @$conn->query("SET CHARACTER SET " . $c);

	//} else {

		// DEBUG ERROR ALERT
        //$m = "Database Server Connection Not Openned!"; echo dataErrorBox($m, 3); exit;

	//}

}

// PLUGIN FUNCTIONS

// DATE RANGE PICKER
function dateRangePicker($iniDate = '', $endDate = '') {
       
    if ($iniDate == '') { 

        $temp = date("Y-m-d");
        $date = new DateTime($temp); 
        $iniDate = $date->format('m/d/Y');

    } else {

        $temp = explode("-", $iniDate);
        if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
        if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }    
        $i = $temp[1] . "/" . $temp[2] . "/" . $temp[0];  

    }    
    
    if ($endDate == '') { 

        $temp = date("Y-m-d");
        $date = new DateTime($temp); 
        $endDate = $date->format('m/d/Y');

    } else {

        $temp = explode("-", $endDate);
        if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
        if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }    
        $e = $temp[1] . "/" . $temp[2] . "/" . $temp[0];  

    }    

    echo "					<script>\n"; 
     
    echo "						moment.locale('pt_BR');\n"; 
     
    echo "						var DateTimePickers = function() {\n"; 
     
    echo "							var _componentDaterange = function() {\n"; 

    echo "								if (!$().daterangepicker) { console.warn('Warning - daterangepicker.js is not loaded.'); return; }\n"; 
     
    echo "								$('.daterange-ranges').daterangepicker({\n"; 

    echo "										opens: 'left',\n"; 
     
    echo "										locale: {\n"; 
    echo "											applyLabel: 'Selecionar',\n"; 
    echo "											cancelLabel: 'Cancelar',\n"; 
    echo "											startLabel: 'Data Inicial',\n"; 
    echo "											endLabel: 'Data Final',\n"; 
    echo "											customRangeLabel: 'Intervalo entre Datas',\n"; 
    echo "											daysOfWeek: ['" . translate("SUN"). "', '" . translate("MON"). "', '" . translate("TUE"). "', '" . translate("WED"). "', '" . translate("THU"). "', '" . translate("FRI"). "','" . translate("SAT"). "'],\n"; 
    echo "											monthNames: ['" . translate("January"). "', '" . translate("February"). "', '" . translate("March"). "', '" . translate("April"). "', '" . translate("May"). "', '" . translate("June"). "', '" . translate("July"). "', '" . translate("August"). "', '" . translate("September"). "', '" . translate("October"). "', '" . translate("November"). "', '" . translate("December"). "'],\n"; 
    echo "											firstDay: 1\n"; 
    echo "										},\n"; 
     
    echo "										formatSubmit: 'yyyy/mm/dd',\n"; 
    echo "										startDate: '" . $i . "',\n"; 
    echo "										endDate: '" . $e . "',\n"; 
    echo "										minDate: '01/01/2019',\n"; 
    echo "										maxDate: '12/31/2022',\n"; 
    echo "										dateLimit: { days: 60 },\n"; 
    echo "										ranges: {\n"; 
    echo "                                          '" . translate("Today"). "': [moment(), moment()],\n";
    echo "                                          '" . translate("Yesterday") . "': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\n";
    echo "                                          '" . translate("Last7Days") . "': [moment().subtract(6, 'days'), moment()],\n";
    //echo "                                          '" . translate("Tomorrow") . "': [moment().add(1, 'days'), moment().add(1, 'days')],\n";
    //echo "                                          '" . translate("Next7Days") . "': [moment().add(1, 'days'), moment().add(8, 'days')],\n";
    echo "                                          '" . translate("Last30Days") . "': [moment().subtract(29, 'days'), moment()],\n";
    echo "                                          '" . translate("ThisMonth") . "': [moment().startOf('month'), moment().endOf('month')],\n";
    echo "                                          '" . translate("LastMonth") . "': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]\n";        
    echo "										},\n"; 
    echo "										opens: 'left',\n"; 
    echo "										applyClass: 'btn-sm bg-slate-600',\n"; 
    echo "										cancelClass: 'btn-sm btn-light'\n"; 
    echo "									},\n";
    
    echo "									function(start, end) {\n"; 
    echo "                                      block('.content');\n";
    echo "										sD = start.format('DD/MM/YYYY'); iniDate = start.format('YYYY-MM-DD');\n"; 
    echo "										eD = end.format('DD/MM/YYYY'); endDate = end.format('YYYY-MM-DD');\n"; 
    echo "										if (sD != eD) { sD = sD + ' - ' + eD }\n"; 
    echo "										$('.daterange-ranges span').html(sD);\n"; 
    echo "										document.getElementById('IniDate').value = iniDate;\n"; 
    echo "										document.getElementById('EndDate').value = endDate;\n"; 
    echo "										document.getElementById('dateForm').submit();\n";
    echo "									}\n"; 
    
    echo "								);\n"; 
     
    $temp = explode("-", $iniDate);
    if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
    if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }    
    $i = $temp[2] . "/" . $temp[1] . "/" . $temp[0];  
    
    $temp = explode("-", $endDate);
    if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
    if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }    
    $e = $temp[2] . "/" . $temp[1] . "/" . $temp[0]; 
    
    $s = $i; if ($i != $e) { $s .= " a " . $e; }
     
    echo "								$('.daterange-ranges span').html('" . $s . "');\n"; 
     
    echo "							};\n"; 
     
    echo "							return { init: function() { _componentDaterange(); } }\n"; 
     
    echo "						}();\n"; 
     
    echo "						document.addEventListener('DOMContentLoaded', function() { DateTimePickers.init(); });\n"; 
     
    echo "					</script>\n";
        
}

// DATA-TABLES
function dataTable($orderFields = "[[ 0, 'desc' ]]", $stateSave = "true") {

	echo "					<script>\n";

	echo "						var DatatableAdvanced = function() {\n";

	echo "							var _componentDatatableAdvanced = function() {\n";

	echo "								if (!$().DataTable) {\n";
	echo "									console.warn('Warning - datatables.min.js is not loaded.');\n";
	echo "									return;\n";
	echo "								}\n";

	echo "								$.extend($.fn.dataTable.defaults, {\n";
    
	echo "									autoWidth: false,\n";
    
	echo "									dom: '<\"datatable-header\"fl><\"datatable-scroll\"t><\"datatable-footer\"ip>',\n";

	// ORDER
	echo "									order: " . $orderFields . ",\n";

	// STATE SAVE
	echo "									stateSave: " . $stateSave . ",\n";   

    // FIXED HEADER & FOOTER
    echo "									fixedHeader: { header: true, footer: false },\n";    

	echo "									language: {\n";
	echo "										search: '<span>" . translate("Filter") . "</span> _INPUT_',\n";
	echo "										searchPlaceholder: '" . translate("TypeToFilter") . "',\n";
	echo "										lengthMenu: '<span>" . translate("Show") . "</span> _MENU_',\n";
	echo "				  						info: '<span>_START_ " . translate('to') . " _END_ " . translate('of') . " _TOTAL_ " . translate('records') . "</span>',\n";
	echo "				  						'infoEmpty': '',\n";
	echo "				  						'emptyTable': '<div class=m-2>" . strtoupper(translate('NoRecordsFound')) . "</div>',\n";
	echo "				  						'zeroRecords': '<span>" . translate('NoRecordsFound') . "</span>',\n";
	echo "										paginate: {\n";
	echo "											'first': '" . translate("First") . "',\n";
	echo "											'last': '" . translate("Last") . "',\n";
	echo "											'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',\n";
	echo "											'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'\n";
	echo "										}\n";
	echo "									}\n";
    
	echo "								});\n";

	echo "								$('.datatable-show-all').DataTable({\n";
	echo "									lengthMenu: [\n";
	echo "										[10, 25, 50, -1],\n";
	echo "										[10, 25, 50, \"" . translate("All") . "\"]\n";
	echo "									]\n";
	echo "								});\n";

	echo "								$('.datatable-dom-position').DataTable({\n";
	echo "									dom: '<\"datatable-header length-left\"lp><\"datatable-scroll\"t><\"datatable-footer info-right\"fi>',\n";
	echo "								});\n";

	echo "								var lastIdx = null;\n";
	echo "								var table = $('.datatable-highlight').DataTable();\n";
	// echo "								$('.datatable-highlight tbody').on('mouseover', 'td', function() {\n";
	// echo "									var colIdx = table.cell(this).index().column;\n";
	// echo "									if (colIdx !== lastIdx) {\n";
	// echo "										$(table.cells().nodes()).removeClass('active');\n";
	// echo "										$(table.column(colIdx).nodes()).addClass('active');\n";
	// echo "									}\n";
	// echo "								}).on('mouseleave', function() {\n";
	// echo "									$(table.cells().nodes()).removeClass('active');\n";
	// echo "								});\n";

	echo "						};\n";

	echo "						var _componentSelect2 = function() {\n";

	echo "							if (!$().select2) {\n";
	echo "								console.warn('Warning - select2.min.js is not loaded.');\n";
	echo "								return;\n";
	echo "							}\n";


	echo "							$('.dataTables_length select').select2({\n";
	echo "								minimumResultsForSearch: Infinity,\n";
	echo "								dropdownAutoWidth: true,\n";
	echo "								width: 'auto'\n";
	echo "							});\n";

	echo "							};\n";

	echo "							return {\n";
	echo "								init: function() {\n";
	echo "									_componentDatatableAdvanced();\n";
	echo "									_componentSelect2();\n";
	echo "								}\n";
	echo "							}\n";

	echo "						}();\n";

	echo "						document.addEventListener('DOMContentLoaded', function() {\n";
	echo "							DatatableAdvanced.init();\n";
	echo "						});\n";

	echo "					</script>\n";

}