<?php

// INDEX.PHP 1.0 (2021/09/11)

// NO RENDER
if (isset($_GET['token'])) { exit(); }

// ADD FILES
addLoginCode("core/config.php");
addLoginCode("core/functions.php");

// DEBUG
// foreach(array_reverse($_POST) as $key=>$value) { echo $key . " [" . $value . "]<br />"; }; //exit;

// GET DEVICE & VERSION
$device = ''; $version = '';
foreach (array_reverse($_GET) as $key => $value) {
    
    if ($key == 'U') { $device = $value; }
    if ($key == 'V') { $version = $value; }
}

// EXECUTE UPDATER.PHP (IF EXISTS)
// if (file_exists("core/updater.php")) { include "core/updater.php"; }

// DATABASE OPEN
OpenDB();

    // LOCALHOST
    $localhost = array('127.0.0.1', '::1');

    // FORCE HTTPS
    if (getOptionValue("RequiresHTTPS") and ! in_array($_SERVER['REMOTE_ADDR'], $localhost)) {
        
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
            $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }

    // ACTION
    $action = '';
    if (isset($_POST['a'])) {
        $action = clean($_POST['a']);
    }
    if (isset($_GET['code'])) {
        $action = "5";
        $code = clean($_GET['code']);
    }
    switch ($action) {
        case "1":
            passwordForm();
            break;
        case "2":
            registerForm();
            break;
        case "3":
            logoutSystem();
            break;
        case "4":
            keepAlive();
            break;
        case "5":
            download($code);
            break;
        default:
            loginForm();
            break;
    }

// DATABASE CLOSE
closeDB();

function loginForm($message = '', $field = '', $noCookie = 0) {

    // UNSET SESSION KEY
    unset($_SESSION['loginKey']);

    global $device; global $version;

    // GET SPECIAL POSTED DATA
    foreach (array_reverse($_POST) as $key => $value) {
        
        if ($key == 'sU') { $device = $value; }
        if ($key == 'sV') { $version = $value; }
    }
    
    // AUTO-LOGIN
    $sysUserReminder = ''; if (isset($_COOKIE['sysUserReminder'])) { if ($_COOKIE['sysUserReminder'] != '') { $sysUserReminder = trim($_COOKIE['sysUserReminder']); } }

    // GET POSTED DATA
    if ((isset($_POST["loginMail"]) && isset($_POST["pW"])) || ($sysUserReminder != '' && $noCookie == 0)) {

        if ($sysUserReminder == '') {

            $_SESSION['screenWidth'] = clean($_POST["wW"]);
            $_SESSION['screenHeight'] = clean($_POST["wH"]);
            $user = clean(strtolower($_POST["loginMail"]));
            $pass = clean($_POST["pW"]);
            $userLanguage = clean($_POST["loginLang"]);
            
        } else {

            $u = explode("#", encryptor($sysUserReminder, 1));
            $_SESSION['screenWidth'] = $u[2];
            $_SESSION['screenHeight'] = $u[3];
            $user = $u[0];
            $pass = hash("sha256", substr(session_id(), - 5) . "A" . $u[1]);
            $userLanguage = $u[4];
        }

        $userID = "";

        // GET USER DATA
        if ($d = select("UserID, UserGroupID, UserName, UserSurName, UserActive, UserPassword, UserLanguage, CompanyID", "sysusers", "where (UserMail='" . $user . "' or UserLogin='" . $user . "') order by UserID desc")) {

            $userID = $d[0]['UserID'];
            $userGroupID = $d[0]['UserGroupID'];
            $userName = $d[0]['UserName'];
            $userSurName = $d[0]['UserSurName'];
            $userPass = $d[0]['UserPassword'];
            $userActive = $d[0]['UserActive'];
            $companyID = $d[0]['CompanyID'];

            $_SESSION['UserName'] = $userName;
            $_SESSION['UserSurName'] = $userSurName;

            // DEFINE VARIABLES
            define('USERID', $userID);
            define('USERGROUPID', $userGroupID);
            define('USERFULLNAME', $userName . " " . $userSurName);
            define('COMPANYID', $companyID);
            
            setcookie("UserID", $userID, time() + (86400 * 90), "/");

            // PASSWORD DECRYPT
            $userPass = encryptor($userPass, 1);

            // PASSWORD COMPARE
            if ($pass == hash("sha256", substr(session_id(), - 5) . "A" . $userPass)) {

                if ($userActive == "1") {

                    // UPDATE USER LOGIN DATA
                    $t = array();
                    $t["UserStatus"] = "1";
                    $t["UserSession"] = session_id();

                    // LANGUAGE
                    setcookie("sysLanguage", $userLanguage, time() + (86400 * 90), "/");
                    define('USERLANGUAGE', $userLanguage);
                    $t["UserLanguage"] = $userLanguage;

                    // UPDATE USER DATA
                    if (update("sysusers", $t, "where UserID=" . $userID)) {

                        // USER REMINDER
                        if (isset($_POST["pW"])) {
                            if (isset($_POST['loginReminder'])) {
                                if ($_POST['loginReminder'] == '1') {
                                    setcookie("sysUserReminder", encryptor($user . "#" . $userPass . "#" . clean($_POST["wW"]) . "#" . clean($_POST["wH"]) . "#" . $userLanguage), time() + (86400 * 90), "/");
                                } else {
                                    setcookie("sysUserReminder", "", time() - 3600, "/");
                                }
                            }
                        }

                        // UPDATE KEEP ALIVE
                        query("update sysusers set UserKeepAlive = now() where UserID=" . $userID);

                        // KEY GENERATE
                        $_SESSION['loginKey'] = encryptor($user . "=" . $userID . "=" . $userLanguage);
                        $_SESSION['loginKeyMobile'] = base64_encode($userID . "," . $userLanguage . "," . date("YmdHis"));

                        // ENTER THE SYSTEM
                        deskPage($device, $version);
                    }
                } else {

                    if ($userActive == "3") {

                        $field = "LoginMail";
                        $message = "WaitActivation";
                    } else {

                        $field = "LoginMail";
                        $message = "UserSuspended";
                    }
                }
            } else {

                $field = "LoginPass";
                $message = "IncorrectPassword";
            }
        } else {

            $field = "LoginMail";
            $message = "EmailNotFound";
            if ($user == '') {
                $field = "LoginMail";
                $message = "AboveFieldIsRequired";
            }
        }
    }

    if (! isset($_SESSION['loginKey'])) {

        // TRANSLATE MESSAGE
        if ($message != '') { $message = translate($message); }

        loginHeader("login");

        // LOGIN FORM
        echo "					<form method='post' action='.' class='loginForm' accept-charset='UTF-8' autocomplete='off'>\n";

        echo "						<div class='card mb-0 login-panel'>\n";

        echo "							<div class='card-body'>\n";

        // PANEL TEXT
        $helpText = translate("EnterYourCredentials");

        // DEMO HELP
        if (DEMO) { $helpText = "<span class='demo-advice'>" . translate("DemoCredentials") . "</span>"; }

        echo "								<div class='text-center mb-3'>\n";

        // OPTIONAL PANEL HEADER
        echo "									<div class='login-panel-head'></div>\n";

        echo "									<h5 class='mb-0'>" . translate("SystemAccess") . "</h5>\n";
        echo "									<span class='d-block text-muted'>" . $helpText . "</span>\n";
        echo "								</div>\n";

        // LANGUAGE
        $sysLanguage = DEFAULTLANGUAGE;
        
        if (isset($_COOKIE['sysLanguage'])) { $sysLanguage = $_COOKIE['sysLanguage']; }
        
        if (getOptionValue("LanguageCombo")) {
            
            echo "								<div class='form-group'>\n";
            echo "									<select class='select-default login-lang' name='loginLang'>\n";
            echo "										<option value='br'";
            if ($sysLanguage == 'br') {
                echo " selected";
            }
            echo "> Portugu&ecirc;s</option>\n";
            echo "										<option value='en'";
            if ($sysLanguage == 'en') { echo " selected"; }
            echo "> English</option>\n";
            // echo " <option value='es'"; if ($sysLanguage == 'es') { echo " selected"; } echo "> Espa&ntilde;ol</option>\n";
            // echo " <option value='fr'"; if ($sysLanguage == 'fr') { echo " selected"; } echo "> Fran&ccedil;ais</option>\n";
            echo "									</select>\n";
            echo "								</div>\n";
        
        } else {
        
            echo "								<input type='hidden' name='loginLang' value='" . $sysLanguage . "'  />\n";
        }

        // E-MAIL / LOGIN
        $loginMail = '';  if (isset($_COOKIE['sysMail'])) { $loginMail = $_COOKIE['sysMail']; }
        
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        
        if (DEMO) { $loginMail = "demo"; }
        
        echo "									<input type='text' name='loginMail' class='form-control login-mail' placeholder='" . translate("YourEmailLogin") . "' maxlength='200' value='" . $loginMail . "' spellcheck='false' autocomplete='off' autofocus />\n";
        echo "									<div class='form-control-feedback'>\n";
        echo "										<i class='icon-user text-muted'></i>\n";
        echo "									</div>\n";
        if ($field == "LoginMail") { echo "									<div class='help-block'>" . $message . "</div>\n"; }
        echo "								</div>\n";

        // PASSWORD
        echo "                              <div class='form-group form-group-feedback form-group-feedback-left'>\n";
        
        $loginPass = ''; if (DEMO) { $loginPass = "demo"; }
        
        echo "                                  <input type='password' name='loginPass' class='form-control login-pass' placeholder='" . translate("YourPassword") . "' maxlength='12' value='" . $loginPass . "' spellcheck='false' autocomplete='off' />\n";
        echo "                                  <div class='form-control-feedback'>\n";
        echo "                                      <i class='icon-key text-muted'></i>\n";
        echo "                                  </div>\n";
        
        if ($field == "LoginPass") { echo "                                  <div class='help-block'>" . $message . "</div>\n"; }
        
        echo "                              </div>\n";

        echo "                              <div class='form-group d-flex align-items-center'>\n";

        // USER REMINDER
        echo "									<div class='form-check mb-0'>\n";
        echo "										<label class='form-check-label'>\n";
        echo "											<input type='checkbox' id='loginReminder' name='loginReminder' class='form-input-styled' data-fouc>\n";
        echo "											" . translate("UserReminder") . "\n";
        echo "										</label>\n";
        echo "									</div>\n";

        // SUBMIT FORM
        $block = ''; if (getOptionValue("BlockAccess")) { $block = " disabled"; }
        
        echo "									<button class='btn btn-primary login-button ml-auto'" . $block . ">" . translate("Login") . " <i class='icon-check position-right'></i></button>\n";

        echo "                              </div>\n";

        // TERMS & CONDITIONS (TODO)
        // echo " <span class='user-terms'>" . translate("ReadAndAccept") . " <a>" . translate("TermsAndConditions") . "</a><br />" . translate("and") . " <a>" . translate("CookiePolicy") . ".</a></span>\n";

        // FORGOT PASSWORD
        if (getOptionValue("ForgotPassword")) { echo "                              <div class='forgot-password'>" . translate("ForgotPassword") . "</div>\n"; }

        // NEW REGISTRATION
        if (getOptionValue("RegisterLink")) { echo "                              <div class='user-register'>" . translate("DontHaveAnAccount") . "</div>\n"; }

        echo "							</div>\n"; // /card-body

        echo "						</div>\n"; // /card

        // OPTIONAL PANEL FOOTER
        echo "						<div class='login-panel-foot'></div>\n";

        // HIDDEN FIELDS
        if ($device != '') {
            
            echo "						<input type='hidden' id='sU' name='sU' value='" . $device . "' />\n";
            echo "						<input type='hidden' id='sV' name='sV' value='" . $version . "' />\n";
        }
        
        echo "						<input type='hidden' id='pW' name='pW' value='' />\n";
        echo "						<input type='hidden' id='wW' name='wW' value='' />\n";
        echo "						<input type='hidden' id='wH' name='wH' value='' />\n";
        echo "						<input type='hidden' id='sT' name='sT' value='" . substr(session_id(), - 5) . "A' />\n";

        echo "					</form>\n";

        loginFooter("login");
    }
}

function passwordForm() {
    
    global $device; global $version;

    // GET SPECIAL POSTED DATA
    foreach (array_reverse($_POST) as $key => $value) {
        
        if ($key == 'sU') { $device = $value; }
        if ($key == 'sV') { $version = $value; }
    }
    
    $message = ''; $return = 0;

    if (isset($_POST["loginMail"])) {

        $recMail = clean($_POST["loginMail"]);

        if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $recMail)) {

            if ($r = select("UserID, UserName, UserActive, UserPassword", "sysusers", "where UserMail='" . $recMail . "'")) {

                $userPass = encryptor($r[0]['UserPassword'], 1);

                if (sendMail(translate('User'), $recMail, SYSTITLE, SYSMAIL, translate('YourPassword'), translate('YourPasswordIs') . ": " . $userPass . ".")) {

                    $message = "PasswordSended";
                    $return = 1;
                } else {

                    $message = "CannotSendEmail";
                }
            } else {

                $message = "EmailNotFound";
            }
        } else {

            $message = "AboveFieldIsRequired";
        }
    }

    loginHeader("login");

    // RECOVER PASSWORD FORM
    echo "                      <form method='post' action='.' class='passwordForm' id='passwordForm' accept-charset='UTF-8' autocomplete='off'>\n";

    echo "							<div class='card mb-0 login-panel'>\n";

    echo "								<div class='card-body'>\n";

    // PANEL TEXT
    echo "									<div class='text-center mb-3'>\n";
    echo "										<div class='login-panel-head'></div>\n";
    echo "										<h5 class='mb-0'>" . translate("PasswordRecovery") . "</h5>\n";
    echo "										<span class='d-block text-muted'>" . translate("PasswordRecoveryEmail") . "</span>\n";
    echo "									</div>\n";

    if ($return != 1) {

        echo "									<input type='hidden' id='sU' name='sU' value='" . $device . "' />\n";
        echo "									<input type='hidden' id='sV' name='sV' value='" . $version . "' />\n";
        echo "									<input type='hidden' id='a' name='a' value='1' />\n";

        // E-MAIL
        echo "									<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "										<input type='email' id='loginMail' name='loginMail' class='form-control' placeholder='" . translate("YourEmail") . "' maxlength='200' value='' spellcheck='false' autofocus />\n";
        echo "										<div class='form-control-feedback'>\n";
        echo "											<i class='icon-mail5 text-muted'></i>\n";
        echo "										</div>\n";
        
        if ($message != '') { echo "									<span class='help-block'>" . translate($message) . "</span>\n"; }
        
        echo "									</div>\n";

        // SUBMIT BUTTON
        echo "									<div class='form-group d-flex align-items-center'>\n";
        echo "										<button class='btn btn-primary pass-button ml-auto'>" . translate("Send") . " <i class='icon-check position-right'></i></button>\n";
        echo "									</div>\n";
        
    } else {

        // RESULT OK MESSAGE
        echo "									<div class='alert alert-success no-border text-center'>" . translate("EmailSended") . "</div>\n";
    }

    // BACK TO LOGIN
    echo "									<div class='back-to-login text-center'><a>" . translate("BackToLogin") . "</a></div>\n";

    echo "								</div>\n"; // /card-body

    echo "                          </div>\n"; // /card

    echo "                      </form>\n";

    loginFooter("login");
}

function registerForm() {
    
    global $device; global $version;

    // GET SPECIAL POSTED DATA
    foreach (array_reverse($_POST) as $key => $value) {
        
        if ($key == 'sU') { $device = $value; }
        if ($key == 'sV') { $version = $value; }
    }

    $message = '';
    $field = '';
    $result = 0;
    $loginName = '';
    $loginSurName = '';
    $loginMail = '';
    $temp = '';
    $temp1 = '';

    // GET USER DATA
    if (isset($_POST["loginName"])) {
        $loginName = clean($_POST["loginName"]);
    }
    if (isset($_POST["loginSurName"])) {
        $loginSurName = clean($_POST["loginSurName"]);
    }
    if (isset($_POST["loginMail"])) {
        $loginMail = strtolower(clean($_POST["loginMail"]));
    }

    // GET PASSWORD
    if (isset($_POST["loginPass"])) {
        $temp = clean($_POST["loginPass"]);
    }
    if (isset($_POST["x_loginPass"])) {
        $temp1 = clean($_POST["x_loginPass"]);
    }

    if (($loginName != '') && ($loginSurName != '') && ($loginMail != '') && ($temp != '') && ($temp == $temp1)) {

        // SAVE LANGUAGE COOKIE
        setcookie("sysLanguage", clean($_POST["loginLang"]), time() + (86400 * 90), "/");

        // VERIFY IF E-MAIL IS VALID
        $emailPattern = '/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i';

        if (! preg_match($emailPattern, strtolower($loginMail))) {

            $field = "LoginMail";
            $message = "EnterValidEmail";
        } else {

            // VERIFY IF USER ALREADY EXISTS
            if ($d = select("UserID", "sysusers", "where (UserMail='" . $loginMail . "' or UserLogin='" . $loginMail . "') order by UserID desc")) {

                $field = "LoginMail";
                $message = "EmailAlreadyExists";
            } else {

                // ENCRYPT PASSWORD
                $userPass = encryptor($temp);

                $t = array();
                $t['UserLanguage'] = clean($_POST["loginLang"]);
                $t['UserName'] = strtoupper($loginName);
                $t['UserSurName'] = strtoupper($loginSurName);
                $t['UserMail'] = $loginMail;
                $t['UserPassword'] = $userPass;

                // USER BEGINS INACTIVE (WAITING ACTIVATION)
                $t['UserActive'] = "3";

                // SAVE USER LOGIN DATA
                if (insert("sysusers", $t)) {
                    $result = 1;
                }
            }
        }
    } else {

        if (($temp != $temp1)) {

            $field = "x_LoginPass";
            $message = "PassNotMatch";
        } else {

            if (isset($_POST['b'])) {

                if ($temp1) {
                    $field = "x_LoginPass";
                }
                if ($temp == '') {
                    $field = "LoginPass";
                }
                if ($loginMail == '') {
                    $field = "LoginMail";
                }
                if ($loginSurName == '') {
                    $field = "LoginSurName";
                }
                if ($loginName == '') {
                    $field = "LoginName";
                }

                $message = "AboveFieldIsRequired";
            }
        }
    }

    loginHeader("login");

    // MESSAGE TRANSLATE
    if ($message != '') { $message = translate($message); }

    // REGISTER FORM
    echo "					<form method='post' action='.' class='registerForm' id='registerForm' accept-charset='UTF-8' autocomplete='off'>\n";

    echo "						<div class='card mb-0 login-panel'>\n";

    echo "							<div class='card-body'>\n";

    // PANEL TEXT
    echo "								<div class='text-center mb-3'>\n";
    echo "									<div class='login-panel-head'><i class='icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1'></i></div>\n";
    echo "									<h5 class='mb-0'>" . translate('NewUserRegister');
    
    if ($result != "1") { echo " <span class='d-block text-muted'>" . translate('FillFormBelow') . "</span>"; }
    
    echo "</h5>\n";
    echo "								</div>\n";

    if ($result != 1) {

        echo "								<input type='hidden' id='sU' name='sU' value='" . $device . "' />\n";
        echo "								<input type='hidden' id='sV' name='sV' value='" . $version . "' />\n";
        echo "								<input type='hidden' id='a' name='a' value='2' />\n";
        echo "								<input type='hidden' id='b' name='b' value='1' />\n";

        // LANGUAGE
        $sysLanguage = DEFAULTLANGUAGE;
        if (isset($_COOKIE['sysLanguage'])) {
            $sysLanguage = $_COOKIE['sysLanguage'];
        }
        if (getOptionValue("LanguageCombo")) {
            echo "								<div class='form-group'>\n";
            echo "									<select class='select-default login-lang' name='loginLang'>\n";
            echo "										<option value='br'";
            if ($sysLanguage == 'br') {
                echo " selected";
            }
            echo "> Portugu&ecirc;s</option>\n";
            echo "										<option value='en'";
            if ($sysLanguage == 'en') {
                echo " selected";
            }
            echo "> English</option>\n";
            // echo " <option value='es'"; if ($sysLanguage == 'es') { echo " selected"; } echo "> Espa&ntilde;ol</option>\n";
            // echo " <option value='fr'"; if ($sysLanguage == 'fr') { echo " selected"; } echo "> Fran&ccedil;ais</option>\n";
            echo "									</select>\n";
            echo "								</div>\n";
        } else {
            echo "								<input type='hidden' name='loginLang' value='" . $sysLanguage . "'  />\n";
        }

        // NAME
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "                                  <input type='text' name='loginName' class='form-control login-name' placeholder='" . translate("Name") . "' maxlength='40' value='" . $loginName . "' spellcheck='false' autofocus />\n";
        echo "                                  <div class='form-control-feedback'>\n";
        echo "                                      <i class='icon-user text-muted'></i>\n";
        echo "                                  </div>\n";
        if ($field == "LoginName") {
            echo "                                  <span class='help-block'>" . $message . "</span>\n";
        }
        echo "								</div>\n";
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "									<input type='text' name='loginSurName' class='form-control login-surname' placeholder='" . translate("SurName") . "' maxlength='40' value='" . $loginSurName . "' spellcheck='false' />\n";
        echo "									<div class='form-control-feedback'>\n";
        echo "										<i class='icon-user text-muted'></i>\n";
        echo "									</div>\n";
        if ($field == "LoginSurName") {
            echo "									<span class='help-block'>" . $message . "</span>\n";
        }
        echo "								</div>\n";

        // E-MAIL
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "									<input type='email' name='loginMail' class='form-control login-mail' placeholder='" . translate("E-mail") . "' maxlength='200' value='" . $loginMail . "' spellcheck='false' autocomplete='off' autofocus />\n";
        echo "									<div class='form-control-feedback'>\n";
        echo "										<i class='icon-envelop3 text-muted'></i>\n";
        echo "									</div>\n";
        if ($field == "LoginMail") {
            echo "									<span class='help-block'>" . $message . "</span>\n";
        }
        echo "								</div>\n";

        // PASSWORD
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "									<input type='password' name='loginPass' class='form-control' placeholder='" . translate("Password") . "' maxlength='12' value='' spellcheck='false' />\n";
        echo "									<div class='form-control-feedback'>\n";
        echo "										<i class='icon-key text-muted'></i>\n";
        echo "									</div>\n";
        if ($field == "LoginPass") {
            echo "									<span class='help-block'>" . $message . "</span>\n";
        }
        echo "								</div>\n";
        echo "								<div class='form-group form-group-feedback form-group-feedback-left'>\n";
        echo "									<input type='password' name='x_loginPass' class='form-control' placeholder='" . translate("RepeatPassword") . "' maxlength='12' value='' spellcheck='false' />\n";
        echo "									<div class='form-control-feedback'>\n";
        echo "										<i class='icon-key text-muted'></i>\n";
        echo "									</div>\n";
        if ($field == "x_LoginPass") {
            echo "									<span class='help-block'>" . $message . "</span>\n";
        }
        echo "								</div>\n";

        // SUBMIT BUTTON
        echo "								<div class='form-group d-flex align-items-center'>\n";
        echo "									<button class='btn btn-primary register-button ml-auto'>" . translate("Save") . " <i class='icon-check position-right'></i></button>\n";
        echo "								</div>\n";
    } else {

        // RESULT OK
        echo "								<div class='alert alert-success no-border text-center'>" . translate("RegisterCompleted") . "</div>\n";
    }

    // BACK TO LOGIN
    echo "								<div class='back-to-login text-center'><a>" . translate("BackToLogin") . "</a></div>\n";

    echo "							</div>\n"; // /card-body

    echo "						</div>\n"; // /card

    echo "					</form>\n";

    loginFooter("login");
}

function loginHeader($mode = 'login') {
    
    echo "<!DOCTYPE html>\n";
    echo "<html lang='en'>\n";

    echo "  <head>\n";

    echo "      <meta charset='utf-8' />\n";
    echo "      <meta http-equiv='X-UA-Compatible' content='IE=edge' />\n";
    echo "      <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />\n";

    echo "      <title>" . SYSTITLE . "</title>\n";

    // FONT
    echo "      <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet'>\n";

    // FONT ICONS
    echo addLoginCode("assets/icons/icomoon/styles.css");

    // WINDOW PLUGIN
    if ($mode != "login") {
        echo addLoginCode("core/plugins/window/window.css");
    }

    // CSS
    echo addLoginCode("core/vendor/bootstrap/bootstrap.min.css");
    echo addLoginCode("core/vendor/limitness/css/bootstrap_limitless.min.css");
    echo addLoginCode("core/vendor/limitness/css/layout.min.css");
    echo addLoginCode("core/vendor/limitness/css/components.min.css");
    echo addLoginCode("core/vendor/limitness/css/colors.min.css");

    // CUSTOM CSS LOAD
    $css = "assets/css/custom-template.css";
    if (file_exists("assets/css/custom.css")) {
        $css = "assets/css/custom.css";
    }
    echo addLoginCode($css);

    // LOGIN/DESKPAGE CSS LOAD
    $css = "assets/css/" . $mode . "-template.css";
    if (file_exists("assets/css/" . $mode . ".css")) {
        $css = "assets/css/" . $mode . ".css";
    }
    echo addLoginCode($css);

    // FAVICONS
    favicons("16");
    favicons("32");
    favicons("76");
    favicons("120");
    favicons("152");
    favicons("180");
    favicons("192");

    // JS LIBRARIES
    echo addLoginCode("core/vendor/jquery/jquery.min.js");
    echo addLoginCode("core/vendor/bootstrap/bootstrap.bundle.min.js");
    // echo addLoginCode("core/plugins/loaders/pace.min.js");
    echo addLoginCode("core/plugins/ui/nicescroll.min.js");
    echo addLoginCode("core/plugins/loaders/blockui.min.js");
    if ($mode == "login") {
        echo addLoginCode("core/plugins/forms/selects/select2.min.js");
        echo addLoginCode("core/plugins/forms/styling/uniform.min.js");
        echo addLoginCode("core/plugins/forms/styling/switchery.min.js");
    } else {
        echo addLoginCode("core/plugins/window/window.js");
        echo addLoginCode("core/plugins/notifications/bootbox.min.js");
        echo addLoginCode("core/vendor/limitness/js/app.js");
    }
    echo addLoginCode("assets/js/custom.js");
    echo addLoginCode("assets/js/" . $mode . ".js");

    echo "  </head>\n";

    echo "  <body>\n";

    // NAVBAR
    if (getOptionValue("TopNavbar")) { navBar($mode); }

    if ($mode == "login") {

        // LOGIN BACKGROUND VIDEO
        $video = "login-template";
        if (file_exists("assets/videos/background/login.mp4")) {
            $video = "login";
        }
        if (file_exists("assets/videos/background/" . $video . ".mp4")) {
            echo "		<div class='fullscreen-bg'>\n";
            echo "			<video loop muted autoplay poster='assets/videos/background/" . $video . ".png' class='fullscreen-bgvideo'>\n";
            echo "				<source src='assets/videos/background/" . $video . ".mp4' type='video/mp4'>\n";
            echo "				<source src='assets/videos/background/" . $video . ".ogv' type='video/ogg'>\n";
            echo "				<source src='assets/videos/background/" . $video . ".webm' type='video/webm'>\n";
            echo "			</video>\n";
            echo "		</div>\n";
        }

        // PAGE CONTENT
        echo "		<div class='page-content'>\n";
        echo "			<div class='content-wrapper'>\n";
        echo "				<div class='content d-flex justify-content-center align-items-center'>\n";
    }
}

function loginFooter($mode = 'login') {
    
    if ($mode == "login") {

        echo "				</div>\n"; // /content

        // COPYRIGHT / VERSION / LICENSE
        echo "				<div class='login-footer'>\n";
        $img = "assets/images/logo/copyright-template.png";
        if (file_exists("assets/images/logo/copyright.png")) {
            $img = "assets/images/logo/copyright.png";
        }
        if (file_exists($img)) {
            echo "					<img src='" . $img . "' alt='" . SYSAUTHOR . "' class='copyright-image' /><br />\n";
        }

        echo "					" . translate("Copyright") . " &copy;" . date('Y') . " <a href='" . SYSAUTHORURL . "'>" . SYSAUTHOR . "</a>\n";
        echo "					- " . translate("EiffelVersion") . ": " . EIFFELVERSION . "." . EIFFELSUBVERSION . "\n";        
        echo "					<br />" . translate("SystemVersion") . ": " . SYSVERSION . "." . SYSSUBVERSION . " - " . translate("SystemLicense") . ": " . LICENSECODE . "\n";
        echo "				</div>\n";

        echo "			</div>\n"; // /content-wrapper
        echo "		</div>\n"; // page-content

        // OPTIONAL LAYERS TO INSERT CUSTOM CONTENT
        echo "		<div class='l-1'></div><div class='l-2'></div><div class='l-3'></div><div class='l-4'></div>\n";
    }

    echo "	</body>\n";

    echo "</html>";
}

function addLoginCode($file, $buff = '1') {
    
    global $rndsec;

    if ($buff != '1') { $buff = ''; } else { $rndsec ++; $buff = "?d=" . $rndsec; }

    if (file_exists($file)) {
        
        switch (strtolower(substr(strrchr($file, '.'), 1))) {
            case "js":
                return "      <script src='" . $file . $buff . "'></script>\n";
                break;
            case "css":
                return "      <link href='" . $file . $buff . "' rel='stylesheet' />\n";
                break;
            default:
                include $file;
                break;
        }
        
    } else {
        
        switch (strtolower(substr(strrchr($file, '.'), 1))) {
            case "js":
            case "css":
                $mode = 2;
                break;
            default:
                $mode = 3;
                break;
        }
        
        echo loginErrorBox($file . " not found!", $mode);
        
        exit();
    }
}

function loginErrorBox($message, $mode = 0) {
    
    $errorBox = '';

    if ($mode == 3) {
        $errorBox = "<html><head><title>SYSTEM ERROR!</title>";
    }

    if ($mode == 2 || $mode == 3) {
        $errorBox .= "</head><body>";
    }

    $errorBox .= "<div style='padding:40px'><div style='padding:20px; color:#fff; background-color:#f00; border-radius:6px; text-align:center; font-family:arial, helvetica, sans-serif; font-weight:bold'>" . utf8_encode($message) . "</div></div>";

    if ($mode == 1 || $mode == 2 || $mode == 3) {
        $errorBox .= "</body></html>";
    }

    return $errorBox;
}

function logoutSystem() {
    
    $message = ''; if (isset($_POST["m"])) { $message = clean($_POST["m"]); }

    if (isset($_SESSION['loginKey'])) {

        // GET USER ID
        $u = encryptor($_SESSION['loginKey'], 1); $u = explode("=", $u);

        if (isset($u[1])) {

            // SET USER STATUS
            $t = array();
            $t["UserStatus"] = '0';
            $t["UserSession"] = '';

            // UPDATE KEEP ALIVE
            if (! query("update sysusers set UserKeepAlive = now() where UserID=" . $u[1])) {
                $message = translate('ErrorWriteKeepAlive');
            }

            // DISABLE USER
            if (! update("sysusers", $t, "where UserID=" . $u[1])) {
                $message = translate('LogoutError');
            }

            // WRITE LOG
            logWrite("Exit", "System", '', '', $message, $u[1]);
        }
    }

    loginForm($message, $field = "LoginPass", 1);
}

function deskPage($device = '', $version = '') {
    
    if (left($device, 3) == "MF2") {

        // DEVICE
        header("Location: ?maestro=login&token=" . base64_encode(USERID . "," . USERLANGUAGE . "," . date("YmdHis"))); die();
        
    } else {

        // BROWSER
        loginHeader("deskpage");

        echo "		<div class='page-content'>\n";

        // MAIN SIDE BAR
        // mainSidebar();

        echo "			<div class='content-wrapper'>\n";

        // OPPENING APPLICATION
        $app = getPreferenceValue("OppeningPage");
        
        if (! file_exists($app)) {
            
            $app = "appss/default/default.php";
        }
        
        if (file_exists($app)) {
            
            echo "				<iframe id='main-page' class='main-page' src='" . $app . "'></iframe>\n";
            
        } else {
            
            message("OppeningPageFail", "danger", 1);
        }

        // OPPOSITE SIDE BAR
        // oppositeSidebar();

        // WINDOW PLUGIN
        echo "				<div class='window-pane'></div>\n";

        // BASIC WINDOW
        echo "				<script type='template/text' id='basic'>\n";
        echo "					<div class='window'>\n";
        echo "						<div class='window-header'><button class='close' data-dismiss='window'><span class='icon-cross3 top-close'></span></button><h7 class='window-title'></h7></div>\n";
        echo "						<div class='window-body'></div>\n";
        echo "					</div>\n";
        echo "				</script>\n";
        
        // DEFAULT VIDEO
        echo "				<script type='template/text' id='winvideo'>\n";
        echo "					<div class='window'>\n";
        echo "						<div class='window-header'><button class='close' data-dismiss='window'><span class='icon-cross3 top-close'></span></button><h7 class='window-title'></h7></div>\n";
        echo "						<div class='window-body window-video'></div>\n";
        echo "					</div>\n";
        echo "				</script>\n";

        // DEFAULT WINDOW
        echo "				<script type='template/text' id='default'>\n";
        echo "					<div class='window'>\n";
        echo "						<div class='window-header'><button class='close' data-dismiss='window'><span class='icon-cross3 top-close'></span></button><h7 class='window-title'></h7></div>\n";
        echo "						<div class='window-body p-0'></div>\n";
        echo "					</div>\n";
        echo "				</script>\n";

        // BUTONED WINDOW
        echo "				<script type='template/text' id='butoned'>\n";
        echo "					<div class='window'>\n";
        echo "						<div class='window-header'><button class='close' data-dismiss='window'><span class='icon-cross3 top-close'></span></button><h7 class='window-title'></h7></div>\n";
        echo "						<div class='window-body p-0'></div>\n";
        echo "						<div class='window-footer'></div>\n";
        echo "					</div>\n";
        echo "				</script>\n";

        echo "			</div>\n"; // /content-wrapper

        echo "		</div>\n"; // /page-content

        loginFooter("deskpage");
    }

    // WRITE LOG
    logWrite("Enter", "System");
}

function keepAlive() {
    
    if (isset($_SESSION['loginKey'])) {

        $error = 0;

        // GET USER ID
        $u = encryptor($_SESSION['loginKey'], 1);
        $u = explode("=", $u);

        // UPDATE KEEP ALIVE
        if (query("update sysusers set UserKeepAlive = now() where UserID=" . $u[1])) {
            $error ++;
        }

        // REMOVE OLD USERS
        $d = select("UserID", "sysusers", "where UserStatus<>'0' and (UserKeepAlive <= date_sub(now(), INTERVAL 1 Minute) or (UserKeepAlive is NULL))");
        if ($d) {
            foreach ($d as $r) {

                $t = array();
                $t["UserStatus"] = '0';
                $t["UserSession"] = '';

                // SET USER DATA
                if (update("sysusers", $t, "where UserID=" . $r["UserID"])) {
                    $error ++;
                }

                // UPDATE KEEP ALIVE
                if (query("update sysusers set UserKeepAlive = now() where UserID=" . $r["UserID"])) {
                    $error ++;
                }
            }
        }

        // REPLACE TIME ON NAVBAR
        if (getOptionValue("ShowDateIcon")) {
            echo "<span class='time navbar-text-small'>" . date("h") . "<span class='navbar-text-spacer'>h</span>" . date("i") . "</span>";
        }
    } else {

        // LOGOUT
        echo "<script>document.write(\"<form action='index.php' method='post' target='_top' id='redirectForm'><input type='hidden' name='a' value='3' /></form>\"); document.getElementById('redirectForm').submit();</script>";
    }
}

function navBar($mode = "login") {
    
    echo "		<div class='navbar navbar-expand-md navbar-dark'>\n";

    // SYSTEM BRAND
    $title = "assets/images/logo/brand-" . $mode . "-template.png"; if (file_exists("assets/images/logo/brand-" . $mode . ".png")) { $title = "assets/images/logo/brand-" . $mode . ".png"; }
    
    echo "			<div class='navbar-brand'><a href='#' class='d-inline-block'><img class='navbar-title' src='" . $title . "' alt='" . SYSTITLE . "' /></a></div>\n";

    // TOP RIGHT ICONS
    echo "			<div class='d-md-none'>\n";

    // FULL SCREEN TOGGLER
    if (getOptionValue("FullScreenToggler")) {
        
        echo "				<button class='navbar-toggler full-screen float-left' type='button' title='" . translate("FullScreen") . "'><i class='icon-screen-full'></i></button>\n";
    }

    if ($mode == "login") {

        // GO TO WEBSITE
        if (getOptionValue("WebsiteButton")) {
            
            $wsl = getOptionValue("WebsiteLink");
            
            if (trim($wsl) != '') { echo "				<button class='navbar-toggler' type='button' title='" . translate("GoToWebsite") . "...'><i class='icon-display4'></i></button>\n"; }
        }
        
    } else {

        // HOME
        if (getOptionValue("ShowHomeIcon")) {
            
            $app = getOptionValue("OppeningPage");
            
            if (! file_exists($app)) { $app = "modules/default/sysfiles.php"; }
            
            echo "				<button class='navbar-toggler menu-link' type='button' id='" . $app . "' title='" . translate("InitialPage") . "'><i class='icon-home'></i></button>\n";
        }

        // COLLAPSE ICON
        echo "				<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbar-mobile' title='" . translate("MainMenu") . "'><i class='icon-paragraph-justify3'></i></button>\n";
    }

    echo "			</div>\n";

    // COLLAPSE AREA
    echo "			<div class='collapse navbar-collapse' id='navbar-mobile'>\n";

    // TOP LEFT ICONS
    navBarSide($mode);

    // CENTER PANEL
    navBarPanel($mode);

    // TOP RIGHT ICONS
    navBarIcons($mode);

    echo "			</div>\n"; // /collapse

    echo "		</div>\n"; // /navbar
}

function navBarSide($mode = "login") {

    // SEARCH FOR LEFT ICONS
    $appsFolder = "apps/";
    if ($dh = opendir($appsFolder)) {
        while (($appFolder = readdir($dh)) !== false) {
            if ($appFolder != "." && $appFolder != ".." && $appFolder != "default") {
                if (is_dir($appsFolder . "/" . $appFolder)) {
                    if ($dg = opendir($appsFolder . "/" . $appFolder)) {
                        $file = $appsFolder . "/" . $appFolder . "/navside.php";
                        if (file_exists($file)) {
                            include $file;
                        }
                        closedir($dg);
                    }
                }
            }
        }
        closedir($dh);
    }

    // DEFAULT LEFT ICONS
    $file = "modules/default/navside.php";
    if (file_exists($file)) {
        include $file;
    }
}

function navBarPanel($mode = "login") {

    // SEARCH FOR TOOLBAR PANEL
    $appsFolder = "apps/";
    if ($dh = opendir($appsFolder)) {
        while (($appFolder = readdir($dh)) !== false) {
            if ($appFolder != "." && $appFolder != ".." && $appFolder != "default") {
                if (is_dir($appsFolder . "/" . $appFolder)) {
                    if ($dg = opendir($appsFolder . "/" . $appFolder)) {
                        $file = $appsFolder . "/" . $appFolder . "/navpanel.php";
                        if (file_exists($file)) {
                            include $file;
                        }
                        closedir($dg);
                    }
                }
            }
        }
        closedir($dh);
    }

    // DEFAULT TOOLBAR PANEL
    $file = "modules/default/navpanel.php";
    if (file_exists($file)) {
        include $file;
    }
}

function navBarIcons($mode = "login") {

    // CLOSE MOBILE MENU
    $closeMobileMenu = ''; if (ISPHONE) { $closeMobileMenu = " data-toggle='collapse' data-target='#navbar-mobile'"; }

    // TOP RIGHT ICONS
    echo "                <ul class='navbar-nav'>\n";

    if ($mode == "login") {

        // FULL SCREEN TOGGLER
        if (getOptionValue("FullScreenToggler")) {
            echo "                  <li class='nav-item'><a title='" . translate("FullScreen") . "' class='navbar-nav-link full-screen'><i class='icon-screen-full'></i><span class='d-md-none ml-2'>" . translate("FullScreen") . "</span></a></li>\n";
        }

        // GO TO WEBSITE
        if (getOptionValue("WebsiteButton")) {
            $wsl = getOptionValue("WebsiteLink");
            if (trim($wsl) != '') {
                echo "                  <li class='nav-item'><a href='" . $wsl . "' title='" . translate("GoToWebsite") . "...' class='navbar-nav-link'><i class='icon-display4'></i><span class='d-md-none ml-2'>" . translate("GoToWebsite") . "</span></a></li>\n";
            }
        }
    } else {

        // USER
        if (getOptionValue("ShowUserIcon")) {
            echo "                  <li class='nav-item user-time'><a class='navbar-nav-link menu-link' id='modules/default/sysusers.php?a=e&p=1&i=" . USERID . "' title='" . translate(getUserGroupName()) . " (" . getCompanyName() . ")'" . $closeMobileMenu . "><i class='icon-user icon-size-12 mr-1'></i><span class='navbar-text-small'>" . USERFULLNAME . "</span></a></li>\n";
        }

        // TIME
        if (getOptionValue("ShowDateIcon")) {
            echo "                  <li class='nav-item user-time'><a class='navbar-nav-link'><i class='icon-alarm icon-size-12 mr-1'></i><span class='time navbar-text-small'>" . date("h") . "<span class='navbar-text-spacer'>h</span>" . date("i") . "</span></a></li>\n";
        }

        // HOME
        if (getOptionValue("ShowHomeIcon")) {
            $app = getOptionValue("OppeningPage");
            if (! file_exists($app)) {
                $app = "modules/default/sysfiles.php";
            }
            echo "                  <li class='nav-item d-none d-sm-block'><a title='" . translate("InitialPage") . "' class='navbar-nav-link menu-link' id='" . $app . "'" . $closeMobileMenu . "><i class='icon-home icon-size-14'></i><span class='d-md-none ml-2'>" . translate("InitialPage") . "</span></a></li>\n";
        }

        // SEARCH FOR APPS ICONS
        $appsFolder = "apps";
        if ($dh = opendir($appsFolder)) {
            
            while (($appFolder = readdir($dh)) !== false) {
                if ($appFolder != "." && $appFolder != ".." && $appFolder != "default") {
                    if (is_dir($appsFolder . "/" . $appFolder)) {
                        if ($dg = opendir($appsFolder . "/" . $appFolder)) {
                            $file = $appsFolder . "/" . $appFolder . "/navicons.php";
                            if (file_exists($file)) { include $file; }
                            closedir($dg);
                        }
                    }
                }
            }
            closedir($dh);
        }

        // DEFAULT APPS ICONS
        $file = "modules/default/navicons.php";
        if (file_exists($file)) { include $file; }

        // SYSTEM EXIT
        echo "                  <li class='nav-item'><a title='" . translate("SystemExit") . "' class='navbar-nav-link' onclick=\"confirmBox('" . translate('ConfirmSystemExit') . "', '" . translate('SystemExit') . "', '" . translate('SystemExit') . "', '" . translate('Cancel') . "')\"" . $closeMobileMenu . ">\n";
        echo "						<i class='icon-switch icon-size-14 system-exit'></i><span class='system-exit-button d-md-none ml-2'>" . translate("SystemExit") . "</span></a>\n";
        echo "                  </li>\n";
    }

    echo "                </ul>\n"; // /navbar-nav
}

function favicons($size) {
    
    $favicon = "assets/images/logo/favicons/favicon-" . $size . "-template.png";
    
    if (file_exists("assets/images/logo/favicons/favicon-" . $size . ".png")) {
        $favicon = "assets/images/logo/favicons/favicon-" . $size . ".png";
    }
    
    if (file_exists($favicon)) {
        echo "      <link href='" . $favicon . "' rel='shortcut icon' sizes='" . $size . "x" . $size . "' />\n";
    }
}

function download($code) {
    
    $fullname = "files/" . urldecode(utf8_decode(base64_decode($code)));

    // DEBUG
    // echo $fullname; exit;

    if (is_file($fullname)) {

         /*
         * Do any processing you'd like here:
         * 1. Increment a counter
         * 2. Do something with the DB
         * 3. Check user permissions
         * 4. Anything you want!
         */

        $fd = fopen($fullname, "rb");
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
        header("Content-Disposition: attachment; filename=\"" . $path_parts["basename"] . "\"");
        header("Content-length: $fsize");
        header("Cache-control: private");
        while (! feof($fd)) {
            $buffer = fread($fd, 1 * (1024 * 1024));
            echo $buffer;
            ob_flush();
            flush();
        }
        fclose($fd);

        // WRITE LOG
        logWrite("ExternalDownload", "FilesSystem", '', '', $fileName);

        exit();
    }
}