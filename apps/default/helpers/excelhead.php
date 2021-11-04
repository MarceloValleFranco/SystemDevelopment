<?php

// EXCELHEAD.PHP 1.0 (2019/05/06)

			// HEADER LINE
			for ($f = 0; $f <= count($columns)-1; $f++) {
				
				switch ($columns[$f]['COLUMN_NAME']) {
							
					// NO SHOW
					case "ItemColumns":
					case "ItemScript";
                    case "CompanyID";
						break;
                        
                    // CASES GOES HERE...
				
					default:
						echo $apost . strtoupper(translate($columns[$f]['COLUMN_NAME'])) . $apost . ";";
						break;
				}	
				
			}
			
			// END OF FIRST LINE
			echo chr(13) . chr(10);