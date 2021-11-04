// SATELLITE.JS 1.0 (2019/11/07)

$(function () {
	
	setInterval("var w = parseInt((window.innerWidth/3) - 128); if (window.innerWidth < 900) { w = window.innerWidth - 134; }; var nw = new Date(); $('#chart1').attr('src', 'http://201.33.0.36/cacti/graph_image.php?local_graph_id=21612&graph_start=-6000&graph_end=-1&graph_width=' + w +'&graph_height=180&t=' + nw.getTime());", 4000);
	setInterval("var w = parseInt((window.innerWidth/3) - 128); if (window.innerWidth < 900) { w = window.innerWidth - 134; }; var nw = new Date(); $('#chart2').attr('src', 'http://201.33.0.36/cacti/graph_image.php?local_graph_id=21610&graph_start=-6000&graph_end=-1&graph_width=' + w +'&graph_height=180&t=' + nw.getTime());", 5000);
	setInterval("var w = parseInt((window.innerWidth/3) - 128); if (window.innerWidth < 900) { w = window.innerWidth - 134; }; var nw = new Date(); $('#chart3').attr('src', 'http://201.33.0.36/cacti/graph_image.php?local_graph_id=21611&graph_start=-6000&graph_end=-1&graph_width=' + w +'&graph_height=180&t=' + nw.getTime());", 6000);
	setInterval("var w = parseInt((window.innerWidth/3) - 128); w = (w + w) + 120; if (window.innerWidth < 900) { w = window.innerWidth - 134; }; var nw = new Date(); $('#chart4').attr('src', 'http://201.33.0.36/cacti/graph_image.php?local_graph_id=21615&graph_start=-6000&graph_end=-1&graph_width=' + w +'&graph_height=222&t=' + nw.getTime());", 7000);
	setInterval("var w = parseInt((window.innerWidth/3) - 128); if (window.innerWidth < 900) { w = window.innerWidth - 134; }; var nw = new Date(); $('#chart5').attr('src', 'http://201.33.0.36/cacti/graph_image.php?local_graph_id=21616&graph_start=-6000&graph_end=-1&graph_width=' + w +'&graph_height=180&t=' + nw.getTime());", 8000);

    // STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 2, bottom: 2 }
	});	
	
})

// BLOCK (LOADING ICON)
function block(e, s, c, v) {

	if (s === undefined) {
		s = '32';
	}
	if (c === undefined) {
		c = '#fff';
	}
	if (v === undefined) {
		v = 4000;
	}

	$(e).block({
		message: '<i class="icon-cog spinner icon-size-' + s + '"></i>',
		timeout: v,
		overlayCSS: {
			backgroundColor: c,
			opacity: 0.8,
			cursor: 'wait'
		},
		css: {
			border: 0,
			padding: 0,
			backgroundColor: 'transparent'
		}
	});

}