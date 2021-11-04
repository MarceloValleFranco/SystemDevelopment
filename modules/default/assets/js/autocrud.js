
// AUTOCRUD.JS 1.0 (2019/01/10)

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

	// STYLED SCROLL MULTIPLE SELECT2
	$(".select2multiple").select2({ minimumResultsForSearch: Infinity }).on("select2:open", function () { });		
	
    // ADD BUTTON HANDLER
    $(".add-button").click(function () {
        block('.content');
		r = $(this).attr('id').split('#');
		window.location.replace('?a=c&v=' + r[0] + '&n=' + r[1]);
    });
	
    // SAVE BUTTON HANDLER
    $(".save-button").click(function () {
        block('.content');
		$('.form-vertical').submit();
    });	

    // CANCEL BUTTON HANDLER
    $(".cancel-button").click(function () {
        block('.content');
		r = $(this).attr('id').split('#');
		window.location.replace('?v=' + r[0] + '&n=' + r[1]);
    });
	
	// DELETE BUTTON HANDLER
	$('.delete-button').click(function () {
		r = $(this).attr('id').split('#');
		confirmBox(r[0], r[1]); 
	});
	
    // BREADCRUMBS BUTTONS HANDLER
    $(".breadcrumb-elements-item").click(function () { block('.page-content'); window.location.replace($(this).attr('id')); });	

})