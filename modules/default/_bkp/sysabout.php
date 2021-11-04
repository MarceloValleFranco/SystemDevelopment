<?php

// SYSABOUT.PHP 1.0 (2018/12/20)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// GET UPDATE INFO
$sysUpdate = SYSUPDATEURL; if (right($sysUpdate, 1) != "/") { $sysUpdate .= "/"; }
$ctx = @stream_context_create(array('http' => array('timeout' => 15)));
$note = ''; //file_get_contents($sysUpdate . '?l=' . LICENCECODE . "&d=" . LICENCEDATA . "&s=" . @$_SERVER['HTTP_HOST'] . "&t=i", false, $ctx);

// DATABASE OPEN
openDB();

    // TEST MESSAGE
    //$note = "<a href='" . SYSAUTHORURL . "' target='_blank' class='label label-danger note'> " . translate("NewVersionAvailable") . " </a>";

    // PAGE HEADER
    pageHeader("sysabout");

        echo "  <body>\n";
        
        echo "      <div class='page-container'>\n";
		
		echo "          <div class='sysheader'>\n";
        
        $img = "../../assets/images/logo/about-template.png";
        if (file_exists("../../assets/images/logo/about.png")) { $img = "../../assets/images/logo/about.png"; }
        if (file_exists($img)) { echo "          	<div class='syslogo'><img src='" . $img . "' class='syslogoimg' alt='" . SYSTITLE . "' /></div>\n"; }
		
		// LICENSE CODE
		$licenseCode = LICENSECODE; if ($licenseCode == "DEMOLICENSE") { $licenseCode = translate("LicenseVersion"); }       
        echo "          	<div class='systitle'><h6>" . translate("SystemVersion") . ": " . SYSVERSION . "." . SYSSUBVERSION . " - " . translate("SystemLicense") . ": " . $licenseCode . "</h6></div>\n";
        
        if ($note != '') { echo "       	<div class='sysnote'>" . $note . "  </div>\n"; }
		
        if (getOptionValue("SystemAboutNotice")) {
            if (strtolower(DEFAULTLANGUAGE) == 'br') {
                echo "          	<div class='sysnotice'>\n\n\tESTE SOFTWARE É FORNECIDO PELO DETENTOR DO COPYRIGHT 'NO ESTADO EM QUE SE ENCONTRA' E QUAISQUER GARANTIAS EXPRESSAS OU IMPLÍCITAS, INCLUINDO, MAS NÃO SE LIMITANDO A, GARANTIAS IMPLÍCITAS DE COMERCIABILIDADE E ADEQUAÇÃO A UM PROPÓSITO ESPECÍFICO. EM NENHUMA CIRCUNSTÂNCIA O AUTOR OU OS CONTRIBUIDORES SERÃO RESPONSÁVEIS POR QUAISQUER DANOS DIRETOS, INDIRETOS, INCIDENTAIS, ESPECIAIS, EXEMPLARES OU CONSEQÜENCIAIS (INCLUINDO, MAS NÃO SE LIMITANDO À, AQUISIÇÃO DE BENS OU SERVIÇOS SUBSTITUTOS, PERDA DE USO, DADOS OU LUCROS; OU INTERRUPÇÃO DE NEGÓCIOS), QUALQUER CAUSA E QUALQUER TEORIA DE RESPONSABILIDADE, SEJA POR CONTRATO, RESPONSABILIDADE ESTRITA OU DANO (INCLUINDO NEGLIGÊNCIA OU QUALQUER OUTRA MANEIRA) DECORRENTE DE QUALQUER FORMA DO USO DESTE SOFTWARE, MESMO SE AVISADO DA POSSIBILIDADE DE TAIS DANOS.\n\n\t</div>\n";
            } else {
                echo "          	<div class='sysnotice'>\n\n\tTHIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER 'AS IS' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.\n\n\t</div>\n";
            }
        }		
          
		echo "          </div>\n";
		  
        echo "          <div class='sysfooter'>\n";
        
        $img = "../../assets/images/logo/logo.png";
        if (file_exists("../../assets/images/logo/logo.png")) { $img = "../../assets/images/logo/logo.png"; }
        if (file_exists($img)) { echo "              <div class='sysauthor'><a href='" . SYSAUTHORURL . "' target='_blank'><img src='" . $img . "' class='sysauthorimg' alt='" . SYSAUTHOR . "' /></a></div>\n"; }        
        
        echo "              <div class='syscopyright'>Copyright &copy;2016-" . date('Y'). " " . SYSAUTHOR . "</div>\n";
               
        echo "          </div>\n";
        
        // DEBUG
        echo "\n\t<!--\n";

		echo "\n\t\t" . str_pad('', 240, "-");

		echo "\n\n\t\tSYSTEM DEBUG 1.0:\n"; 
        
		// BROWSER
        $ua = getBrowser(); echo "\n\t\tBrowser [" . $ua['name'] . " (" . $ua['version'] . ")] Platform [" . $ua['platform'] . "] Agent [" . $ua['userAgent'] . "]\n\n";

		// LIST SESSIONS
        echo "\t\tSESSION:\n"; foreach($_SESSION as $key=>$value) { echo "\t\t\t" . $key . "=[" . @utf8_encode($value) . "]\n"; }

		// LIST COOKIES
        echo "\n\t\tCOOKIE:\n"; foreach($_COOKIE as $key=>$value) { echo "\t\t\t" . $key . "=[" . $value . "]\n"; } 

		// LIST SERVER VARIABLES
        echo "\n\t\tSERVER:\n"; foreach($_SERVER as $key=>$value) { echo "\t\t\t" . $key . "=[" . $value . "]\n"; }

		// LIST PHP INI VALUES
        echo "\n\t\tPHP.INI:\n"; foreach(ini_get_all(null, false) as $key=>$value) { echo "\t\t\t" . $key . "=[" . $value . "]\n"; }

		// LIST CONSTANT VARIABLES
        echo "\n\t\tCONSTANT:\n\t\t\t"; foreach(get_defined_constants() as $key=>$value) { echo $key . "=[" . utf8_encode($value) . "] "; }

		echo "\n\t\t" . str_pad('', 240, "-");
    
        echo "\n\n\t-->\n\n";  
        
        echo "      </div>\n";
        
        echo "  </body>\n";
        
    // PAGE FOOTER
    pageFooter();
    
    // WRITE LOG
    logWrite("View", "SystemAbout"); 

// DATABASE CLOSE
closeDB();