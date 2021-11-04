<?php

// SYSCHAT.PHP 1.0 (2018/10/17)



include "../../core/config.php";
include "../../core/functions.php";
include "../../core/security.php";

// DATABASE OPEN
openDB();

    $a = ""; if (isset($_GET["a"])) { $a = strip_tags($_GET["a"]); }

    switch ($a) {
        case "m": chatMessages(); break;
        case "u": chatUsers(); break;
        default: chatContainer(); break;
    }

// DATABASE CLOSE
closeDB();

function chatContainer() {

    ;

    // PAGE HEADER
    pageHeader("syschat");

        echo "  <body onload='initChat()'>\n";
  
            echo "          <div class='page-content'>\n";
            echo "              <div class='content-wrapper'>\n";
            echo "                  <div class='content'>\n";        
    
            echo "                      <div id='usersWindow'></div>\n";
            echo "                      <div id='messagesWindow'></div>\n";
            echo "                      <div id='inputWindow'>\n";
            	chatInput();
            echo "                      </div>\n";
            
            echo "                  </div>\n";
            echo "              </div>\n";
            echo "          </div>\n";
  
        echo "  </body>\n";

    // PAGE FOOTER
    pageFooter();

}

function chatUsers() {

    ;

    // RECEIVER USER SET
    $rID = $_GET['u'];
      
    // SET MESSAGES VIEWED
    //$db->execSQL("update syschats set MessageViewed='1' where (MessageReceiverID=" . USERID . " and MessageSenderID=" . $_GET['u'] . ")");   
    $t = array();
    $t["MessageViewed"] = '1';
    update("syschats", $t, "where (MessageReceiverID=" . USERID . " and MessageSenderID=" . $_GET['u'] . ")");
    
    //$db->execSQL("update syschats set MessageViewed='1' where (MessageReceiverID=0 and MessageSenderID<>" . USERID . ")");
    $t = array();
    $t["MessageViewed"] = '1';    
    update("syschats", $t, "where (MessageReceiverID=0 and MessageSenderID<>" . USERID . ")");
        
    // USERS QUERY
    $d = select("*", "sysusers", "where (UserActive='1' and UserID<>" . USERID . ") order by UserStatus desc, UserName asc");
    
    $div1 = "           <div style='margin:8px; padding:1px 0 8px 0; background-color:#CAE3EC; border-radius:6px'>";
    $div2 = "           <div style='margin-bottom:8px; cursor:pointer' onclick=\"setUser('0')\">";
    
    // ALL USERS
    if ($rID == '0') { echo $div1; } else { echo $div2; }
    echo "<table style='margin:8px 8px 0 8px'><tr><td style='width:40px'><img src='" . "../../assets/images/avatar/0.png'  class='userPhoto' /></td><td style='padding:0 0 0 16px; font-size:11px; line-height:13px'>" . translate('AllUsers') . "</td></tr></table>\n";
    echo "</div>";
    
    // USERS LIST
    foreach ($d as $r) {
    
        // USER PHOTO
        $userPhoto = "../../custom/assets/images/avatar/" . $r['UserAvatar'];
        if (!file_exists($userPhoto)) { $userPhoto = "../../assets/images/avatar/0.png"; }
        $userPhoto = "<img src='" . $userPhoto . "' class='userPhoto' />\n";
        
        // USER STATUS
        $status = "<span style='color:#080'>" . translate('Online') . "</span>";
        if ($r['UserStatus'] == '0') { $status = "<span style='color:#800'>" . translate('Offline') . "</span>"; }
            
        // USER WITH NO MESSAGE
        $div2 = "           <div style='margin-bottom:8px; cursor:pointer' onclick=\"setUser('" . $r['UserID'] . "')\">";
            
        // USER WITH MESSAGES
        //$e = $db->rsSelect("select * from syschats where (MessageSenderID=" . $r['UserID'] . " and MessageReceiverID=" . USERID . " and MessageViewed='0')");
        $e = select("*", "syschats", "where (MessageSenderID=" . $r['UserID'] . " and MessageReceiverID=" . USERID . " and MessageViewed='0')");
        if (count($e) > 0) { $div2 = "           <div style='margin:8px 8px 0 8px; padding:1px 0 8px 0; background-color:#F0E2C2; border-radius:6px; cursor:pointer' onclick=\"setUser('" . $r['UserID'] . "')\">"; }

        // SETTED OR NOT USER
        if ($rID == $r['UserID']) { echo $div1; } else { echo $div2; }
        echo "<table style='margin:8px 8px 0 8px'><tr><td style='width:40px'>" . $userPhoto . "</td><td style='padding:0 0 0 16px; font-size:11px; line-height:13px'>" . $r['UserName'] . " " . $r['UserSurName'] . "<br />" . $status . "</td></tr></table>\n";
        echo "</div>";
    
    }
    
    // DEBUG
    //echo "Receiver ID [" . $rID . "]";

}

function chatInput() {

    echo "          <div><input type='text' id='inputText' placeholder='" . translate('TypeYourMessageHere...') . "' onKeyPress='sendMessage(event, this)' /></div>\n";
    
}

function chatMessages() {

    ;
    
    // RECEIVER USER SET
    $rID = $_GET['u'];

    // SAVE MESSAGE
    if (isset($_GET['t'])) {
        if ($_GET['t'] != '') {
            $t = array();
            $t["MessageSenderID"] = USERID;
            $t["MessageReceiverID"] = $rID;
            $t["MessageText"] = $_GET['t'];
            $n = insert("syschats", $t);
        }
    }
        
    // GET MESSAGES
    $d = select("*", "syschats", "where (MessageReceiverID=0 or ((MessageSenderID=" . $rID . " and MessageReceiverID=" . USERID . ") or (MessageReceiverID=" . $rID . " and MessageSenderID=" . USERID . "))) order by MessageID");
    
    // LIST MESSAGES
    if (count($d) > 0) {
   
    	echo "            <div>\n";    
    
        foreach ($d as $r) {
        
            // USER PHOTO
            $userPhoto = "../../custom/assets/images/avatar/" . getUserAvatar($r['MessageSenderID']);
            if (!file_exists($userPhoto)) { $userPhoto = "../../assets/images/avatar/0.png"; }
            $userPhoto = "<img src='" . $userPhoto . "' class='userPhoto' />\n";            
            
            echo "              <table style='width:100%; margin-bottom:8px'><tr>";
   
            if ($r['MessageSenderID'] == USERID) { $align = "right"; $bubble = "me"; } else { $align = "left"; $bubble = "you"; }
  
            $colPhoto = "<td style='width:40px; padding:4px 8px 0 8px; vertical-align:top'>" . $userPhoto . "</td>";
            if ($r['MessageReceiverID'] == '0') { $rName = translate("AllUsers"); } else { $rName = getUserName($r['MessageReceiverID']); }
            $colMessage = "<td><div class='bubble " . $bubble . "'><div class='infoNote' style='text-align:" . $align . "'><i class='icon-gear'></i>" . $r['MessageDate'] . "H<span class='fui-user user'></span>" . getUserName($r['MessageSenderID']) . "<span class='fui-triangle-right-large triangle'></span>" . $rName . "</div><div style='text-align:" . $align . "'>" . $r['MessageText'] . "</div></div></td>";
   
            if ($r['MessageSenderID'] == USERID) { echo $colMessage . $colPhoto; } else { echo $colPhoto . $colMessage; }
  
            echo "</tr></table>\n";
            
        }

        echo "            </div>\n";
 
    }
    
    // DEBUG
    //echo "Receiver ID [" . $rID . "]<br />SQL [" . $sql . "]";
    
}