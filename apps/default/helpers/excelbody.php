<?php

// EXCELBODY.PHP 1.0 (2019/05/09)

			// RECORDS LOOP
			foreach ($d as $r) { 
			
                // COLUMNS LOOP
				for ($f = 0; $f <= count($columns)-1; $f++) {
					
					switch ($columns[$f]['COLUMN_NAME']) {
						
						// NO SHOW
						case "ItemColumns":
						case "ItemScript";
                        case "CompanyID";
							break;						
							
						// ITEM ACTIVE
						case "CompanyActive":
						case "ItemActive":
							$no = translate("Inactive");
							if ($r[$columns[$f]['COLUMN_NAME']] == '1') { $no = translate("Active"); }                
							echo $apost . $no . $apost . ";";						
							break;
							
							
						// ACTIVE
						case "AppActive":
						case "ModuleActive":
						case "WidgetActive":
						case "ItemStatus":
							switch($r[$columns[$f]['COLUMN_NAME']]) {
								case "0": $n = translate("Inactive"); break;
								case "2": $n = translate("Disabled"); break;
								default: $n = translate("Active"); break;                
							} 
							echo $apost . $n . $apost . ";";							
							break;
                            
                        // ----- MOBILITY ------
                        
                        // CUSTOMER NAME
                        case "CustomerID":
                            
                            $d = select("*", "appcustomers", "where CustomerID=" . $r["CustomerID"]);
                            if ($d) {
                                if ($d[0]["CustomerName"] == '') { echo $apost . $apost . ";"; } else { echo $apost . $d[0]["CustomerName"] . " (" . $d[0]["CustomerCpf"] . ")" . $apost . ";"; }
                            }
                            break;                        
                        
                         // CHARGER NAME
                        case "ChargerID":
                            $d = select("*", "appchargers", "where ChargerID=" . $r["ChargerID"]);
                            if ($d) {
                                if ($d[0]["ChargerID"] == '') { echo $apost . $apost . ";"; } else { echo $apost . $d[0]["ChargerName"] . $apost . ";"; }
                            }
                            break; 

                        // SOCKET NAME
                        case "SocketID":
                            $d = select("*", "appsockets", "where SocketID=" . $r["SocketID"]);
                            if ($d) {
                                if ($d[0]["SocketID"] == '') { echo $apost . $apost . ";"; } else { echo $apost . $d[0]["SocketName"] . $apost . ";"; }
                            }
                            break;

                        // SERVICE PRICE
                        case "ServicePrice":
                            $value = $r["ServicePrice"];
                            if ($value == '0' || $value == '') { echo $apost . "0" . $apost . ";"; } else { echo $apost . number_format($value / 100, 2, ',', '.') . $apost . ";"; }
                            break;
                        
                        // ----- /MOBILITY ------
							
                        // MORE CASES GOES HERE...

						// DEFAULT
						default:
							echo $apost . $r[$columns[$f]['COLUMN_NAME']] . $apost . ";";
							break;
					}			
				}
				
				// END OF EACH LINE
				echo chr(13) . chr(10);			
			}