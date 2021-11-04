
// SYSGROUPSMANAGER.JS 1.0 (2018/12/10)

$(function () {
	
    // STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }
	});	

    // ADD BUTTON
    $(".add-button").click(function () {
        block('.content');
		window.location.replace('?a=c');
    });
	
    // SAVE BUTTON
    $(".save-button").click(function () {
        block('.content');
		$('.form-vertical').submit();
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