
// CONTEXTMENU.JS 1.0 (2016/12/29)

(function (e) {
    function a(r, u, a, l) {
        var c = s[r];
        i = e("#" + c.id).find("ul:first").clone(true);
        i.css(c.menuStyle).find("li").css(c.itemStyle).hover(function () {
            e(this).css(c.itemHoverStyle)
        }, function () {
            e(this).css(c.itemStyle)
        }).find("img").css({
            verticalAlign: "middle",
            paddingRight: "2px"
        });
        t.html(i);
        if (!!c.onShowMenu) t = c.onShowMenu(a, t);
        e.each(c.bindings, function (n, r) {
            e("#" + n, t).bind("click", function (e) {
                f();
                r(u, o)
            })
        });
        t.css({
            left: a[c.eventPosX],
            top: a[c.eventPosY]
        }).show();
        if (c.shadow) n.css({
            width: t.width(),
            height: t.height(),
            left: a.pageX + 2,
            top: a.pageY + 2
        }).show();
        e(document).one("click", f)
    }

    function f() {
        t.hide();
        n.hide()
    }
    var t, n, r, i, s, o;
    var u = {
        menuStyle: {
            listStyle: "none",
            padding: "8px 0 8px 0",
            margin: "0",
            backgroundColor: "#fff",
            borderRadius: "3px",
            border: "solid 1px #ddd",
            boxShadow: "0 1px 3px rgba(0, 0, 0, .1)"
        },
        itemStyle: {
            margin: "0",
            color: "#444",
            display: "block",
            cursor: "default",
            padding: "8px 30px 8px 18px",
            fontSize: "13px",
            fontWeight: "normal",
            borderBottom: "0",
            backgroundColor: "#fff"
        },
        itemHoverStyle: {
            backgroundColor: "#f5f5f5",
            borderRadius: "0",
            color: "#111",
            cursor: "pointer"
        },
        eventPosX: "pageX",
        eventPosY: "pageY",
        shadow: false,
        onContextMenu: null,
        onShowMenu: null
    };
    e.fn.contextMenu = function (r, i) {
        if (!t) {
            t = e('<div id="jqContextMenu"></div>').hide().css({
                position: "absolute",
                zIndex: "500"
            }).appendTo("body").bind("click", function (e) {
                e.stopPropagation()
            })
        }
        if (!n) {
            n = e("<div></div>").css({
                backgroundColor: "#000",
                position: "absolute",
                opacity: .2,
                zIndex: 499
            }).appendTo("body").hide()
        }
        s = s || [];
        s.push({
            id: r,
            menuStyle: e.extend({}, u.menuStyle, i.menuStyle || {}),
            itemStyle: e.extend({}, u.itemStyle, i.itemStyle || {}),
            itemHoverStyle: e.extend({}, u.itemHoverStyle, i.itemHoverStyle || {}),
            bindings: i.bindings || {},
            shadow: i.shadow || i.shadow === false ? i.shadow : u.shadow,
            onContextMenu: i.onContextMenu || u.onContextMenu,
            onShowMenu: i.onShowMenu || u.onShowMenu,
            eventPosX: i.eventPosX || u.eventPosX,
            eventPosY: i.eventPosY || u.eventPosY
        });
        var o = s.length - 1;
        e(this).bind("click", function (e) {
            var t = !!s[o].onContextMenu ? s[o].onContextMenu(e) : true;
            if (t) a(o, this, e, i);
            return false
        });
        return this
    };
    e.contextMenu = {
        defaults: function (t) {
            e.each(t, function (t, n) {
                if (typeof n == "object" && u[t]) {
                    e.extend(u[t], n)
                } else u[t] = n
            })
        }
    }
})(jQuery);
$(function () {
    $("div.context-menu").hide()
});