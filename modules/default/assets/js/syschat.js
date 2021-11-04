
// SYSCHAT.JS 1.0 (2017/02/23) 

var rID = '0';

function Ajax(url, id) {
    if (document.getElementById) {
        var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
    }
    if (x) {
        x.onreadystatechange = function () {
            if (x.readyState == 4 && x.status == 200) {
                el = document.getElementById(id);
                try { el.innerHTML = x.responseText; } catch (err) { }
            }
        }
        x.open("GET", url, true);
        x.send(null);
    }
}

function initChat() {

    Ajax('syschat.php?a=u&u=' + rID + '&d=' + new Date().getTime(), 'usersWindow');
    setInterval("Ajax('syschat.php?a=u&u='+rID+'&d='+new Date().getTime(), 'usersWindow')", 5000);
    Ajax('syschat.php?a=m&u=' + rID + '&d=' + new Date().getTime(), 'messagesWindow');
    setInterval("Ajax('syschat.php?a=m&u='+rID+'&d='+new Date().getTime(), 'messagesWindow')", 4000);
    scrollBottom();
    setInterval("scrollBottom()", 4000);

}

function sendMessage(e, textarea) {

    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
        Ajax('syschat.php?a=m&u=' + rID + '&t=' + encodeURI(document.getElementById('inputText').value) + '&d=' + new Date().getTime(), 'messagesWindow');
        document.getElementById('inputText').value='';
        scrollBottom();
    }

}

function setUser(userID) { rID = userID; Ajax('syschat.php?a=u&u=' + rID + '&d=' + new Date().getTime(), 'usersWindow'); Ajax('syschat.php?a=m&u=' + rID + '&d=' + new Date().getTime(), 'messagesWindow'); }

function scrollBottom() { $('#messagesWindow').stop().animate({ scrollTop: $('#messagesWindow')[0].scrollHeight }); }