<?php

// REPORT.PHP 1.0 (2018/10/29)

require "../../core/config.php";
require "../../core/functions.php";

// DATABASE OPEN
openDB();

            // PAGE HEADER
            pageHeader("sysusers");

    echo "      <div class='page-container'>\n";
    echo "          <div class='page-content'>\n";
    echo "              <div class='content-wrapper'>\n";
    echo "                  <div class='content'>\n";


    if ($d = select("sysusers", "sysusers", "")) {

        // GRID
        echo "                          <table class='table table-hover datatable-highlight'>\n";
    
        echo "                              <thead>\n";
        echo "                                  <tr>\n";
        if (getOptionValue("UseAvatar")) { echo "                                      <th class='hidden-xs width-32 pl-10'>" . translate("UserAvatar") . "</th>\n"; }
        echo "                                      <th class='pl-10'>" . translate("UserName") . "</th>\n";
        echo "                                      <th>" . translate("UserMail") . "</th>\n";
		echo "                                      <th>Confirmou</th>\n";
		echo "                                      <th>Camisa</th>\n";
		echo "                                      <th>Acompanhante</th>\n";
		echo "                                      <th>Camisa Ac</th>\n";
        echo "                                      <th>" . translate("UserRegisterDate") . "</th>\n";
		echo "                                      <th>" . translate("UserUpdateDate") . "</th>\n";
        echo "                                      <th class='width-40'>" . translate("Status") . "</th>\n";
        echo "                                  </tr>\n";
        echo "                              </thead>\n";
    
        echo "                              <tbody>\n";
    
            foreach ($d as $r) {    
    
                echo "                                  <tr class='table-tr'>\n";
                
                echo "                                      <td class='pl-10'>" . $r["UserName"] . " " . $r["UserSurName"] . "</td>\n";
                echo "                                      <td class='table-td'>" . $r["UserMail"] . "</td>\n";
				echo "                                      <td class='table-td'>" . $r["UserOption"] . "</td>\n";
				echo "                                      <td class='table-td'>" . $r["UserShirt"] . "</td>\n";
                echo "                                      <td class='table-td'>" . translate($r["GuestName"]) . "</td>\n";
				echo "                                      <td class='table-td'>" . translate($r["GuestShirt"]) . "</td>\n";
                
				// REGISTER DATE
                $rDate = ''; if (isDate($r["UserRegisterDate"])) { $temp = date_create($r["UserRegisterDate"]); $rDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='table-td'>" . $rDate . "h</td>\n";

                // UPDATE DATE
                $uDate = ''; if (isDate($r["UserUpdateDate"])) { $temp = date_create($r["UserUpdateDate"]); $uDate = date_format($temp,"Y/m/d - H:i:s"); }
                echo "                                      <td class='table-td'>" . $uDate . "h</td>\n";
                
                echo "                                  </tr>\n";
    
            }
    
        echo "                              </tbody>\n";

        echo "                          </table>\n"; 
           
    echo "  </body>\n";
	
	// SAVE EVENT
	if (!isset($_GET['a'])) { logWrite(USERID, "Report", "UsersManager", "sysusers"); }
        
            // PAGE FOOTER
            pageFooter();

    }

// DATABASE CLOSE
closeDB();