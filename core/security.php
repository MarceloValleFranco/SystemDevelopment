<?php

// SECURITY.PHP 1.0 (2018/12/04)

$isLogged = 0;
$message = '';
$p = array();
$q = array();

// DEBUG
//echo "[" . $_SESSION['loginKey'] . "]"; exit;

// NORMAL ACCESS
if (isset($_SESSION['loginKey'])) {

    $p = explode("=", encryptor($_SESSION['loginKey'], 1));
    
    if (isset($p[1])) {
    
		checkUser($p[1]);
    
    }

}

// DEVICE ACCESS
if (isset($_GET['token'])) {

    $q = explode(",", base64_decode($_GET['token']));
    
    if (isset($q[0])) {

		// DEBUG
		//echo "ID [" . $q[0] . "] LANGUAGE [" . $q[1] . "]"; exit;
    
		checkUser($q[0]);
    
    }

}

// DEBUG
//echo "[" . $isLogged . "]"; exit;

// UNLOGGED
if ($isLogged == 0) { exitSystem($message); }

function checkUser($userID) {

	global $isLogged;
	global $message;
	global $p;
	global $q;
	
	// DEBUG
	//echo "[" . $userID . "]"; exit;	

    // GET USER DATA       
    openDB();
        
        if ($r = select("*", "sysusers", "where UserID=" . $userID . " order by UserID limit 1")) { 

            // DEFINE USER GLOBAL VALUES
			@define("COMPANYID", $r[0]['CompanyID']);
			@define("USERID", $r[0]['UserID']);
            @define("USERNAME", $r[0]['UserName']);
            @define("USERSURNAME", $r[0]['UserSurName']);
            @define("USERMAIL", $r[0]['UserMail']);
            @define("USERLOGIN", $r[0]['UserLogin']);
            @define("USERACTIVE", $r[0]['UserActive']);
            @define("USERSTATUS", $r[0]['UserStatus']);
            @define("USERGROUPID", $r[0]['UserGroupID']);
            @define("USERLANGUAGE", $p[1]);

			// LOGIN BY TOKEN
			if (isset($_GET['token'])) { 

				if (!isset($_SESSION['loginKey'])) {

					$t = array();
					$t["UserStatus"] = "1";
					$t["UserSession"] = session_id();
					$t["UserKeepAlive="] = date("Y-m-d H:i:s");

					// LANGUAGE
					setcookie("sysLanguage", $q[1], time() + (86400 * 90), "/");
					$t["UserLanguage"] = $q[1];

					// UPDATE USER DATA
					if (update("sysusers", $t, "where UserID=" . USERID)) {

						// KEY GENERATE
						$_SESSION['loginKey'] = encryptor(USERMAIL . "=" . USERID . "=" . $q[1]);
						$_SESSION['loginKeyMobile'] = base64_encode(USERID . "," . $q[1]  . "," . date("YmdHis"));
					
					}
				}

			} else {
                      
				// IF SESSION IS CHANGED
				if ($r[0]['UserSession'] != session_id()) { $message = "UnLoggedByOtherUser"; }
    
				// IF DATA IS DIFFERENT THEN LOGIN.KEY
				if (USERMAIL != $p[0] && USERLOGIN != $p[0]) { $message = "DataDifferentFromLogin"; }

			} 
                
            // LOGGED
            if ($message == '') { if (USERACTIVE == "1") { $isLogged = 1; } else { exitSystem('UserSuspended'); } }
            
        } else {
            
            // USER NO MORE EXISTS
            exitSystem($message);
                
        }
            
    closeDB();

}