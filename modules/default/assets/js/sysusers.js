
// SYSUSERS.JS 1.0 (2019/01/22)

$(function () {
	
    // STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 2, bottom: 2 }
	});	
	
	// STYLED SCROLL SELECT2
	$(".select2combo").select2({ minimumResultsForSearch: Infinity }).on("select2:open", function () { $('.select2-results__options').niceScroll({
		cursorcolor:"#ccc", 
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 2, bottom: 2 }});
	});	

    // ADD BUTTON
    $(".add-button").click(function () {
        block('.content');
		window.location.replace('?a=c');
    });
	
    // SAVE BUTTON
    $(".save-button").click(function () {
		block('.content'); $('.form-vertical').submit();
    });	

    // CANCEL BUTTON
    $(".cancel-button").click(function () {
        block('.content');
		window.location.replace('?');
    });		
	
	// DELETE BUTTON
	$('.delete-button').click(function () {
		r = $(this).attr('id').split('#');
		confirmBox(r[0], r[1]); 
	});

	// USER REMINDER
	$(".switch2").change(function () {
		var v = $(".switch2").prop("checked");
		if (v) {
			v = '1';
		} else {
			v = '0';
		}
		$("#UserReminder").val(v);
	}).change();	
	
	// AJAX UPLOAD
	$('.file-upload-ajax').on('change', function () {
		$("#UserAvatar").attr("src", '../../assets/images/preloaders/128x128/preloader1.gif');
		var formdata = new FormData($("#uploadForm")[0]);
		$.ajax({
			type: "POST",
			url: "?a=u&i=" + $('#userID').val(),
			enctype: 'multipart/form-data',
			data: formdata,
			async: false,
			contentType: false,
			processData: false,
			cache: false,
			success: function (msg) {
				$response = $.parseJSON(msg);
				//alert($('#userID').val());
				$('.error-message').text($response.message);
				if (Left($response.response_html, 1) != '') {
					$('.file-upload-ajax').val('');
					$('#UserAvatarFile').val($response.response_html);
					$("#userAvatar").attr("src", $('#z').val() + $response.response_html);
				} else {
					$("#userAvatar").attr("src", $('#z').val() + '0.png');
				}
			}
		});
	});

})

/* SPECIAL FORM FIELDS */

function MascaraCNPJ(cnpj){
        if(mascaraInteiro(cnpj)==false){
                event.returnValue = false;
        }       
        return formataCampo(cnpj, '00.000.000/0000-00', event);
}

function MascaraCep(cep){
                if(mascaraInteiro(cep)==false){
                event.returnValue = false;
        }       
        return formataCampo(cep, '00.000-000', event);
}

function MascaraData(data){
        if(mascaraInteiro(data)==false){
                event.returnValue = false;
        }       
        return formataCampo(data, '00/00/0000', event);
}

function MascaraTelefone(tel){  
        if(mascaraInteiro(tel)==false){
                event.returnValue = false;
        }       
        return formataCampo(tel, '(00) 0000-0000', event);
}

function MascaraCPF(cpf){
        if(mascaraInteiro(cpf)==false){
                event.returnValue = false;
        }       
        return formataCampo(cpf, '000.000.000-00', event);
}

function ValidaTelefone(tel){
        exp = /\(\d{2}\)\ \d{4}\-\d{4}/
        if(!exp.test(tel.value))
                alert('Numero de Telefone Invalido!');
}

function ValidaCep(cep){
        exp = /\d{2}\.\d{3}\-\d{3}/
        if(!exp.test(cep.value))
                alert('Numero de Cep Invalido!');               
}

function ValidaData(data){
        exp = /\d{2}\/\d{2}\/\d{4}/
        if(!exp.test(data.value))
                alert('Data Invalida!');                        
}

function ValidarCPF(Objcpf){
        var cpf = Objcpf.value;
        exp = /\.|\-/g
        cpf = cpf.toString().replace( exp, "" ); 
        var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
        var soma1=0, soma2=0;
        var vlr =11;

        for(i=0;i<9;i++){
                soma1+=eval(cpf.charAt(i)*(vlr-1));
                soma2+=eval(cpf.charAt(i)*vlr);
                vlr--;
        }       
        soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
        soma2=(((soma2+(2*soma1))*10)%11);

        var digitoGerado=(soma1*10)+soma2;
        if(digitoGerado!=digitoDigitado) {        
                document.getElementById('UserCPFerror').style.display = 'block';
				return 0; 
		} else {
				return 1;
		}
}

function mascaraInteiro(){
        if (event.keyCode < 48 || event.keyCode > 57){
                event.returnValue = false;
                return false;
        }
        return true;
}

function ValidarCNPJ(ObjCnpj){
        var cnpj = ObjCnpj.value;
        var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        var dig1= new Number;
        var dig2= new Number;

        exp = /\.|\-|\//g
        cnpj = cnpj.toString().replace( exp, "" ); 
        var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

        for(i = 0; i<valida.length; i++){
                dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
                dig2 += cnpj.charAt(i)*valida[i];       
        }
        dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
        dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

        if(((dig1*10)+dig2) != digito)  
                alert('CNPJ Invalido!');

}

function formataCampo(campo, Mascara, evento) { 
        var boleanoMascara;

        var Digitato = evento.keyCode;
        exp = /\-|\.|\/|\(|\)| /g
        campoSoNumeros = campo.value.toString().replace( exp, "" ); 

        var posicaoCampo = 0;    
        var NovoValorCampo="";
        var TamanhoMascara = campoSoNumeros.length;; 

        if (Digitato != 8) { 
                for(i=0; i<= TamanhoMascara; i++) { 
                        boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")
                                                                || (Mascara.charAt(i) == "/")) 
                        boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(") 
                                                                || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " ")) 
                        if (boleanoMascara) { 
                                NovoValorCampo += Mascara.charAt(i); 
                                  TamanhoMascara++;
                        }else { 
                                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
                                posicaoCampo++; 
                          }              
                  }      
                campo.value = NovoValorCampo;
                  return true; 
        }else { 
                return true; 
        }
}