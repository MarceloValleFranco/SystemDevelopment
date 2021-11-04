
// DATASEND.JS 1.0 (2019/05/06)

$(function () {
	
    // STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }
	});	
	
	// STYLED SCROLL SELECT2
	$(".select2combo").select2({ minimumResultsForSearch: Infinity }).on("select2:open", function () { $('.select2-results__options').niceScroll({
		cursorcolor:"#ccc", 
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }});
	});
	
	// STYLED SCROLL SELECT2 WITH SEARCH
	$(".select2comboSearch").select2({}).on("select2:open", function () { $('.select2-results__options').niceScroll({
		cursorcolor:"#ccc", 
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }});
	});	
	
})