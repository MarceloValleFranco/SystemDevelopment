
// SYSFILES.JS 1.0 (2018/11/29)

$(function () {
	
	// STYLED SCROLL
	$("html").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		cursorborder: "0",
		railpadding: { top: 2, right: 2, left: 0, bottom: 2 }
	});	
  
  $('.fileName').contextMenu('OptionMenu', {
	  bindings: {
		  'filecopy': function(t) { block('body'); r = t.id.split('#'); window.location.href=('?dir='+r[0]+'&copy='+r[1]) },
		  'filepaste': function(t) { block('body'); r = t.id.split('#'); window.location.href=('?dir='+r[0]+'&paste=1') },
		  'filemove': function(t) { block('body'); r = t.id.split('#'); window.location.href=('?dir='+r[0]+'&move=1') },
		  'filedelete': function(t) { r = t.id.split('#'); confirmBox(r[0], r[1]) },
		  'filerename': function(t) { r = t.id.split('#'); renameFileBox(r[1]) },
		  'filedownload': function(t) { r = t.id.split('#'); window.open('?dir='+r[0]+'&download='+r[1], '_self') },
		  'fileshare': function(t) { r = t.id.split('#'); shareBox(r[0], r[1]) }
	  }
  });
  
})