
// SYSPREFERENCES.JS 1.0 (2018/11/30)

$(function () {
	
    // STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }
	});	
	
	// STYLED SCROLL SELECT2
	$(".preference-select").select2({ minimumResultsForSearch: Infinity }).on("select2:open", function () { $('.select2-results__options').niceScroll({
		cursorcolor:"#ccc", 
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }});
	});	
	
    // ENABLE SAVE BUTTON
    $('.form-control, .switchery').click(function () { $('.confirm').prop('disabled', false); $('.cancel').prop('disabled', false); });
	$('.preference-select').change(function () { $('.confirm').prop('disabled', false); $('.cancel').prop('disabled', false); });

    // SUBMIT FORM
    $(".confirm").click(function () {
        block('.page-container');
        $(".preferencesForm").submit();
    });

})