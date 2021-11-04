// CUSTOM.JS 1.0 (2018/12/06)

$(function () {
	
    // STYLED DATATABLE SCROLL
	$(".datatable-scroll").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 2, bottom: 2 }
	});		
	
	// TOGGLES
	if (Array.prototype.forEach) {
		var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
		elems.forEach(function (html) {
			var switchery = new Switchery(html);
		});
	} else {
		var elems = document.querySelectorAll('.switchery');
		for (var i = 0; i < elems.length; i++) {
			var switchery = new Switchery(elems[i]);
		}
	}

	// NEW ITEM HANDLER
	$('.new-item').click(function () {
		block('.content-wrapper');
		window.location.href = ('?a=e&i=0');
	});

	// HOME LINK HANDLER
	$('.home-link').click(function () {
		block('.content-wrapper', '32', '#f00', 9000);
		iD = $(this).attr('id');
		if (iD == '') {
			iD = "apps/default/index.php";
		}
		$('.main-page', parent.document).attr('src', iD);
		block('.content-wrapper');
	});
	
	// FULL-SCREEN
	if ((screen.availHeight || screen.height-30) <= window.innerHeight) { $('.full-screen').hide(); } else { $('.full-screen').show(); }
	
	$(window).on('resize', function () {
		if ((screen.availHeight || screen.height-30) <= window.innerHeight) { $('.full-screen').hide(); } else { $('.full-screen').show(); }
	});
	$('.full-screen').click(function () {
		var elem = document.documentElement;		
		if (elem.requestFullscreen) { elem.requestFullscreen(); } else if (elem.mozRequestFullScreen) { elem.mozRequestFullScreen(); } 
			else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); } else if (elem.msRequestFullscreen) { elem.msRequestFullscreen();
		}
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

// BASE 64 ENCODE
function b64EncodeUnicode(str) {
	return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
		return String.fromCharCode('0x' + p1);
	}));
}

// BASE 64 DECODE
function b64DecodeUnicode(str) {
	return decodeURIComponent(Array.prototype.map.call(atob(str), function (c) {
		return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
	}).join(''));
}

// STRING FUNCTIONS

// LEFT
function Left(str, n) {
	if (n <= 0)
		return "";
	else if (n > String(str).length)
		return str;
	else
		return String(str).substring(0, n);
}

// RIGHT
function Right(str, n) {
	if (n <= 0)
		return "";
	else if (n > String(str).length)
		return str;
	else {
		var iLen = String(str).length;
		return String(str).substring(iLen, iLen - n);
	}
}

// REPLACE (ALL)
String.prototype.replaceAll = function (de, para) {
	var str = this;
	var pos = str.indexOf(de);
	while (pos > -1) {
		str = str.replace(de, para);
		pos = str.indexOf(de);
	}
	return (str);
}