
// DESKPAGE.JS 1.0 (2019/11/31)

// WINDOW MANAGER
var wmcount = 0;

$(function () {
	
	var wm = new WindowManager({
		container: '.window-pane',
		windowTemplate: $('#basic').html()
	});
	window.wm = wm;
	
	var wv = new WindowManager({
		container: '.window-pane',
		windowTemplate: $('#winvideo').html()
	});
	window.wv = wv;
	
	var wd = new WindowManager({
		container: '.window-pane',
		windowTemplate: $('#default').html()
	});
	window.wd = wd;	
	
	var wb = new WindowManager({
		container: '.window-pane',
		windowTemplate: $('#butoned').html()
	});
	window.wb = wb;		
})

function mWindow(head, body, foot) {
	wmcount++;
	wm.createWindow({
		title: head,
		bodyContent: body
	});
}

function vWindow(head, body) {
	wmcount++;
	wv.createWindow({
		title: head,
		bodyContent: body
	});
}

function dWindow(head, body) {
	wmcount++;
	wd.createWindow({
		title: head,
		bodyContent: body
	});
}

function bWindow(head, body, foot) {
	wmcount++;
	wb.createWindow({
		title: head,
		bodyContent: body,
		footerContent: "<button type='button' class='btn btn-default btn-cancel' data-dismiss='window'><i class='icon-cross3'></i></button>",
		maximizable: true
	});
}

$(function () {
	
    // LISTENER / KEEP-ALIVE
    setInterval("$.post('index.php', { a: '4' }, function (data, status) { $('.time').html(data); });", 5000);

    // MENU LINK HANDLER 
    $('.menu-link').click(function () { block('.page-content'); $('.main-page').attr('src', $(this).attr('id')); });
	
	// MAIN AREA RESIZE
	setInterval(function() {
		var h = window.innerHeight;
		var w = window.innerWidth;
		var iframe = document.getElementById('main-page');
		iframe.style.height = (h - 48) + "px";
		iframe.style.width = w + "px";	
	}, 500);

})

// SYSTEM CHAT MODAL
function chatBox(m, t, f) {
    var wC = '<iframe src=' + m + ' class=chat-frame style=height:' + (screen.height - 360) + 'px></iframe>';
    bootbox.dialog({
        message: wC, title: '<b>' + t + '</b>', backdrop: true//,
            //buttons: {
            //    cancelBtn: { label: '<i class="icon-cross mr-2"></i>' + f, className: 'btn-default' }
            //}
    });
}

// SYSTEM ABOUT MODAL
function aboutBox(m, t, f) {
    var wC = '<iframe src=' + m + ' class=about-frame></iframe>';
    bootbox.dialog({
        message: wC, title: '<b>' + t + '</b>', backdrop: true//,
            //buttons: {
            //    cancelBtn: { label: '<i class="icon-cross mr-2"></i>' + f, className: 'btn-default' }
            //}
    });
}

// SYSTEM EXIT CONFIRM MODAL
function confirmBox(m, t, f, c) {
    bootbox.dialog({
        message: m, title: '<b>' + t + '</b>',
            buttons: {
                cancelBtn: { label: '<i class="icon-chevron-left mr-2"></i>' + c, className: 'btn-default' },
                confirmBtn: { label: '<i class="icon-check mr-2"></i>' + f, className: 'btn-danger', callback: function () { block('.content-wrapper', '32', '#000'); $(document.body).append("<form method='post' id='exitForm'><input type='hidden' name='a' value='3' /></form>"); $('#exitForm').submit(); } }
            }
    });
};