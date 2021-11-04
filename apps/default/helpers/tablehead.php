<?php

// TABLEHEAD.PHP 1.0 (2019/05/08)

		// TABLE HEADER
		echo "							<thead>\n"; 
		echo "								<tr>\n"; 
        echo "                                  <tr>\n";
		
            // COLUMNS LOOP
			for ($f = 1; $f <= count($columns)-1; $f++) {
				
				switch ($columns[$f]['COLUMN_NAME']) {
					
					// NO SHOW
					case "CompanyID":
					case "CustomerAddress":
					case "CustomerCity":
					case "CustomerState":
					case "CustomerPassword":
					case "CustomerPostalCode":
					case "CustomerNameOnCard":
					case "CustomerCardNumber":
					case "CustomerCardValidate":
					case "CustomerCardCVV":
					case "ItemColumns":
					case "ItemDescription":
					case "ItemLocations":
					case "ItemOrder":
					case "ItemScript";
					case "ItemScriptFile";
					case "ItemSignature":
					case "ItemTable":
					case "ItemURL":
					case "Latitude":
					case "Longitude":
					case "LocationType":
					case "LocationGroupID":				
					case "SaveOnMarkers":
                    
                    // ----------- MOBILITY -----------
                    
					case "ChargerOperatorAddress":
					case "ChargerOperatorCity":
					case "ChargerOperatorState":
					case "ChargerOperatorPassword":
					case "ChargerOperatorPostalCode":
					case "StationOwnerAddress":
				    case "StationOwnerCity":
				    case "StationOwnerState":
					case "StationOwnerPassword":
					case "StationOwnerPostalCode":
                    case "ChargerActive";
                    case "ChargerManufacturer";
                    case "ChargerModel";
                    case "ChargerCapacity";
                    case "ChargerAmount";    
                                         
                    // ----------- MOBILITY -----------
					
						break;
						
					// NO SHOW ON MOBILE
					case "ItemOrder":
					case "ShowOnMobile":
					case "ShowOnTablet":
					case "ShowOnDesktop":
						echo "                                      <th class='table-th d-none d-sm-table-cell r-120'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;	

					// ITEM NAME
					case "ItemName":
						if (($appTable == "sysapps") || ($appTable == "sysmodules")) { echo "                                      <th class='table-th l-160'>" . translate("ItemCodeName") . "</th>\n"; }
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;							
						
					// ITEM ACTIVE
					case "CompanyActive":
					case "ItemActive":
						echo "                                      <th class='table-th r-50'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;

					// COMPANY CODE
					case "CompanyCode":
						echo "                                      <th class='table-td d-none d-sm-table-cell l-200'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";

					// ----------- MOBILITY -----------

					case "StationStatus":
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						echo "                                      <th class='table-th r-50'>" . translate("StationChargerStatus") . "</th>\n";
						break;
						
					case "ChargerStatus":
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						echo "                                      <th class='table-th r-50'>" . translate("ChargerSocketStatus") . "</th>\n";
						break;	
                                        
                    case "ChargerID":
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						echo "                                      <th class='table-th r-20'>" . translate("StationName") . "</th>\n";
						break;    
                                        
					case "EndTime":
                        echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
                        echo "                                      <th class='table-th'>" . translate('TotalTime') . "</th>\n";
                        break;
					
					case "MeterStart":
                        echo "                                      <th class='table-th'>" . translate('ServiceConsumption') . "</th>\n";
                        break;
					
                    // ----------- /MOBILITY -----------						
					
					case "CustomerPasswordFlag":
					case "MeterStop";
					    break;
                        
					default:
						echo "                                      <th class='table-th'>" . translate($columns[$f]['COLUMN_NAME']) . "</th>\n";
						break;
				}					
			}
			
        echo "                                  </tr>\n";
		echo "								</tr>\n"; 
		echo "							</thead>\n";