<?php

// ALLINLIST.PHP 1.0 (2019/11/07)

	// ADD CSS
	echo "			" . addCode("../../widgets/default/assets/css/allinlist.css");
	
	// INITIAL PANEL
	$activePanel = '1';
	if (isset($_POST['p'])) { $activePanel = $_POST['p']; }
	
	$column = 0; 
	
	echo "					<div class='all-in-list'>\n";	
	
        // APPS MENU
        makeMenu("apps");
        
        // MODULES MENU
        makeMenu("modules");
	
	echo "					</div>\n"; // /all-in-list
	
	function makeMenu($mode) {
		
		global $activePanel, $column;
		
		$j = 0; $groupCount = 0; $groupName = array(); $groupIcon = array(); $actionCount = 0; $temp = '';
		
		$d = select("*", "sys" . $mode . "groups", "where (CompanyID='" . COMPANYID . "' and ShowOnDesktop='1' and ItemActive<>'0') order by ItemOrder, ItemName");
		
		if ($d) {
		
			foreach ($d as $r) {
				
				if ($temp != $r['ItemID']) {
					if (checkApp($r['ItemName'])) { $groupCount++; $groupID[$groupCount] = $r['ItemID']; $groupIcon[$groupCount] = $r['ItemIcon']; $groupName[$groupCount] = $r['ItemName']; }
					$temp = $r['ItemID'];
				}
				
			}
		  
		}
			
		// APP/MODULE LOOP	
		if ($groupCount > 0) {
		
		   for ($c = 1; $c <= $groupCount; $c++) {
			   
				$column++;
				   
				$icon = "cog"; if ($groupIcon[$c] != '') { $icon = $groupIcon[$c]; }

				echo "						<div class='card-group-control card-group-control-right' id='accordion-control-right" . $column . "'>\n";					
		
				echo "							<div class='card'>\n";
				
				echo "								<div class='card-header'>\n";
				echo "									<h6 class='card-title'><i class='icon-" . $icon . " mr-2 width-20'></i>\n";
				echo "										<a class='collapsed text-default' data-toggle='collapse' href='#accordion-control-right-group" . $column . "'>" .  translate($groupName[$c]) . "</a>\n";
				echo "									</h6>\n";
				echo "								</div>\n";
						
				echo "								<div id='accordion-control-right-group" . $column . "' class='collapse' data-parent='#accordion-control-right" . $column . "'>\n";
				
				echo "									<div class='card-body card-body-list'>\n";
				
					// EACH APP/MODULE
					$m = "App"; $l = ''; if (strtolower($mode) == "modules") { $m = "Module"; $l = "v=1&"; }
					
					$e = select("*", "sys" .$mode, "where (CompanyID='" . COMPANYID . "' and " . $m . "GroupID='" . $groupID[$c] . "' and ShowOnMobile='1' and " . $m . "Active<>'0') order by ItemOrder, ItemName");
					
					if ($e) {
		
						foreach ($e as $s) {
							   
							if (getPermission("View", $s["ItemName"])) {

								$inactive = ''; if ($s[$m . "Active"] == "2") { $inactive = " suspended"; }
								
								echo "										<div class='thumbnail-list" . $inactive . "'";
								
                                if ($s[$m . "Active"] == "1") {
                                
                                    if (trim($s["ItemURL"]) != '') { 
                                    
                                        if (strpos($s["ItemURL"], "autocrud")) {
                                            
                                            $link = $s["ItemURL"] . "?" . $l . "n=" . $s["ItemID"];
                                            
                                        } else {
                                            
                                            $link =  $s["ItemURL"];
                                            if ($s["AddUserID"] ) { $link .= "?userid=" . USERID; }

                                        }
                                    
                                        if (strtolower($mode) == "modules") {
                                            
                                            if (left($link, 7) == "modules") { $link = str_replace("modules/", "../../modules/", $link); }
                                            
                                        } else {
                                            
                                            if (left($link, 4) == "apps") { $link = str_replace("apps/", "../../apps/", $link); } else { $link = str_replace("modules/", "../", $link); }
                                            
                                        }
                                        
                                        echo " block('.page-content'); onclick=\"window.location.href=('" . $link . "')\"";
                                    
                                    } else {
                                        
                                        $noShow = " display-none";
                                    }
                                    
                                } else {
                                 
                                    $noShow = " display-none";
                                }
								
								echo ">\n";			
								echo "											<div class='thumbnail-list-icon'><i class='icon-" . $s["ItemIcon"] . " icon-size-48'></i></div>\n";
								echo "											<div class='thumbnail-list-title'>" . translate($s["ItemName"]) . "</div>\n";
								
								echo "										</div>\n";
								
								$actionCount++;
							
							}

						}
		
					} else {
		
						// NO APP/MODULE
						echo "										<div class='alert alert-warning border-0'>" . translate("NoRecordsFound") . "</div>\n";
			
					}               
				
				echo "									</div>\n"; // /card-body
				
				echo "								</div>\n"; // /accordion-control-right-group
				
				echo "							</div>\n"; // /card
				
				echo "						</div>\n"; // /card-group-control
			
			}
			
		}	
		
	}