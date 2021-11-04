
// DEFAULT.JS 1.0 (2019/04/04) 

function ajax(url, id) {
    if (document.getElementById) {
        var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
    }
    if (x) {
        x.onreadystatechange = function () {
            if (x.readyState == 4 && x.status == 200) {
                el = document.getElementById(id);
                el.innerHTML = x.responseText;
            }
        }
        x.open("GET", url, true);
        x.send(null);
    }
}

$(function () {
	
    // STYLED SCROLL
	$("body, .content").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 4, right: 2, left: 0, bottom: 4 }
	});	
	
    // REFRESH BUTTON
    $(".refresh-button").click(function () {
        block('.content');
		window.location.replace('?');
    });

	// STYLED SCROLL SELECT2
	$(".select2combo").select2({ minimumResultsForSearch: Infinity }).on("select2:open", function () { $('.select2-results__options').niceScroll({
		cursorcolor:"#ccc", 
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 2, bottom: 2 }});
	});		
		
})