
// LOGIN.JS 1.0 (2018/10/17)

var LoginRegistration = function () {

    var _componentUniform = function () {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform();
    };

    return {
        initComponents: function () {
            _componentUniform();
        }
    };

}();

document.addEventListener('DOMContentLoaded', function () {
    LoginRegistration.initComponents();
});

$(function () {

    // TOGGLES
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }

    // STYLED SCROLL
	$("body").niceScroll({
		cursorcolor:"#ccc",
		cursorwidth:"8px",
		railpadding: { top: 4, right: 4, left: 0, bottom: 0 }
	});

    // ELEMENTS STYLE
    $('.styled').uniform();

    $('.select-default').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });

    // PREVENT ENTER FORM SUBMIT
    $('.login-mail').keydown(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === '13') {
            $('.login-pass').focus();
            return false;
        }
        event.stopPropagation();
    });
    $('.login-pass').keydown(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === '13') {
            $(".login-button").click();
            return false;
        }
        event.stopPropagation();
    });

    // LANGUAGE CHANGE
    $('.login-lang').on('change', function () {
        var d = new Date();
        d.setTime(d.getTime() + (90 * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = "sysLanguage=" + this.value + "; " + expires;
        block('.login-panel');
        location.reload();
    });

    // SUBMIT LOGIN FORM
    $(".login-button").click(function () {
        $("#pW").val(SHA256($("#sT").val() + $(".login-pass").val()));
        $("#wW").val($(window).width());
        $("#wH").val($(window).height());
        if ($(window).width() > 480) { $(".login-pass").val(''); }
		block('.login-panel');
        $(".loginForm").submit();
    });

    // USER REGISTER FORM
    $(".user-register").click(function () {
        $(document.body).append("<form method='post' id='regForm'><input type='hidden' name='a' value='2' /></form>");
        block('.login-panel');
        $("#regForm").submit();
    });

    // SUBMIT REGISTER FORM
    $(".register-button").click(function () {
        block('.login-panel');
        $(".registerForm").submit();
    });

    // FORGOT PASSWORD RECOVER FORM
    $(".forgot-password").click(function () {
        $(document.body).append("<form method='post' id='passForm'><input type='hidden' name='a' value='1' /></form>");
        block('.login-panel');
        $("#passForm").submit();
    });

    // SUBMIT PASSWORD RECOVER FORM 
    $(".pass-button").click(function () {
        $(".pass-button").addClass("disabled");
        block('.login-panel');
        $(".passwordForm").submit();
    });

    // BACK TO LOGIN
    $(".back-to-login").click(function () {
        var sU = $('#sU').val();
        var sV = $('#sV').val();
        $(document.body).append("<form method='post' id='loginForm'><input type='hidden' name='a' value='0' /><input type='hidden' name='sU' value='" + sU + "' /><input type='hidden' name='sV' value='" + sV + "' /></form>");
        block('.login-panel');
        $("#loginForm").submit();
    });

    // CLEAR PASSWORD FIELD
    $(".login-pass").val('');

})

// SHA256 ENCRYPT
function SHA256(e) {

    function r(e, t) {
        var n = (e & 65535) + (t & 65535);
        var r = (e >> 16) + (t >> 16) + (n >> 16);
        return r << 16 | n & 65535
    }

    function i(e, t) { return e >>> t | e << 32 - t }
    function s(e, t) { return e >>> t }
    function o(e, t, n) { return e & t ^ ~e & n }
    function u(e, t, n) { return e & t ^ e & n ^ t & n }
    function a(e) { return i(e, 2) ^ i(e, 13) ^ i(e, 22) }
    function f(e) { return i(e, 6) ^ i(e, 11) ^ i(e, 25) }
    function l(e) { return i(e, 7) ^ i(e, 18) ^ s(e, 3) }
    function c(e) { return i(e, 17) ^ i(e, 19) ^ s(e, 10) }

    function h(e, t) {
        var n = new Array(1116352408, 1899447441, 3049323471, 3921009573, 961987163, 1508970993, 2453635748, 2870763221, 3624381080, 310598401, 607225278, 1426881987, 1925078388, 2162078206, 2614888103, 3248222580, 3835390401, 4022224774, 264347078, 604807628, 770255983, 1249150122, 1555081692, 1996064986, 2554220882, 2821834349, 2952996808, 3210313671, 3336571891, 3584528711, 113926993, 338241895, 666307205, 773529912, 1294757372, 1396182291, 1695183700, 1986661051, 2177026350, 2456956037, 2730485921, 2820302411, 3259730800, 3345764771, 3516065817, 3600352804, 4094571909, 275423344, 430227734, 506948616, 659060556, 883997877, 958139571, 1322822218, 1537002063, 1747873779, 1955562222, 2024104815, 2227730452, 2361852424, 2428436474, 2756734187, 3204031479, 3329325298);
        var i = new Array(1779033703, 3144134277, 1013904242, 2773480762, 1359893119, 2600822924, 528734635, 1541459225);
        var s = new Array(64);
        var h, p, d, v, m, g, y, b, w, E;
        var S, x;
        e[t >> 5] |= 128 << 24 - t % 32;
        e[(t + 64 >> 9 << 4) + 15] = t;
        for (var w = 0; w < e.length; w += 16) {
            h = i[0];
            p = i[1];
            d = i[2];
            v = i[3];
            m = i[4];
            g = i[5];
            y = i[6];
            b = i[7];
            for (var E = 0; E < 64; E++) {
                if (E < 16) s[E] = e[E + w];
                else s[E] = r(r(r(c(s[E - 2]), s[E - 7]), l(s[E - 15])), s[E - 16]);
                S = r(r(r(r(b, f(m)), o(m, g, y)), n[E]), s[E]);
                x = r(a(h), u(h, p, d));
                b = y;
                y = g;
                g = m;
                m = r(v, S);
                v = d;
                d = p;
                p = h;
                h = r(S, x)
            }
            i[0] = r(h, i[0]);
            i[1] = r(p, i[1]);
            i[2] = r(d, i[2]);
            i[3] = r(v, i[3]);
            i[4] = r(m, i[4]);
            i[5] = r(g, i[5]);
            i[6] = r(y, i[6]);
            i[7] = r(b, i[7])
        }
        return i
    }

    function p(e) {
        var n = Array();
        var r = (1 << t) - 1;
        for (var i = 0; i < e.length * t; i += t) {
            n[i >> 5] |= (e.charCodeAt(i / t) & r) << 24 - i % 32
        }
        return n
    }

    function d(e) {
        e = e.replace(/\r\n/g, "\n");
        var t = "";
        for (var n = 0; n < e.length; n++) {
            var r = e.charCodeAt(n);
            if (r < 128) {
                t += String.fromCharCode(r)
            } else if (r > 127 && r < 2048) {
                t += String.fromCharCode(r >> 6 | 192);
                t += String.fromCharCode(r & 63 | 128)
            } else {
                t += String.fromCharCode(r >> 12 | 224);
                t += String.fromCharCode(r >> 6 & 63 | 128);
                t += String.fromCharCode(r & 63 | 128)
            }
        }
        return t
    }

    function v(e) {
        var t = n ? "0123456789ABCDEF" : "0123456789abcdef";
        var r = "";
        for (var i = 0; i < e.length * 4; i++) {
            r += t.charAt(e[i >> 2] >> (3 - i % 4) * 8 + 4 & 15) + t.charAt(e[i >> 2] >> (3 - i % 4) * 8 & 15)
        }
        return r
    }
    var t = 8;
    var n = 0;
    e = d(e);
    return v(h(p(e), e.length * t))
}