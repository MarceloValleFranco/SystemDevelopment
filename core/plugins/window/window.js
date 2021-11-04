
/* WINDOW.JS (2018/07/30) - Based on https://github.com/earmbrust/bootstrap-window */

var Window=null;

 ! function(a) {
	"use strict";

	Window=function(b) {
		b=b || {}

		;

		var c= {
			selectors: {
				handle: ".window-header",
					title: ".window-title",
					body: ".window-body",
					footer: ".window-footer"
			}

			,
			elements: {
				handle: null,
					title: null,
					body: null,
					footer: null
			}

			,
			references: {
				body: a("body"),
					window: a(window)
			}

			,
			parseHandleForTitle: !0,
			title: "No Title",
			bodyContent: "",
			footerContent: ""
		}

		;

		return this.options=a.extend( !0, {}

		, c, b),
		this.initialize(this.options),
		this
	}

	,
	Window.prototype.initialize=function(b) {
		var c=this;
		if (b.fromElement) this.$el=b.fromElement instanceof jQuery ? b.fromElement: b.fromElement instanceof Element ? a(b.fromElement): a(b.fromElement);

		else {
			if ( !b.template) throw new Error("No template specified for window.");
			this.$el=a(b.template)
		}

		b.elements.handle=this.$el.find(b.selectors.handle),
		b.elements.title=this.$el.find(b.selectors.title),
		b.elements.body=this.$el.find(b.selectors.body),
		b.elements.footer=this.$el.find(b.selectors.footer),
		b.elements.title.html(b.title),
		b.fromElement && c.$el.find("[data-dismiss=window]").length <=0 && b.elements.title.append('<button class="close" data-dismiss="window">x</button>'),
		b.elements.body.html(b.bodyContent),
		b.elements.footer.html(b.footerContent),
		this.undock(),
		this.setSticky(b.sticky)
	}

	,
	Window.prototype.undock=function() {

		this.$el.css("visibility", "hidden"),
		this.$el.appendTo("body"),
		this.centerWindow(),
		/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && this.options.references.window.bind("orientationchange resize", function() {
			_this.centerWindow()
		}

		),
		this.$el.on("touchmove", function(a) {
			a.stopPropagation()
		}

		),
		this.initHandlers(),
		this.$el.hide(),
		this.id=this.options.id ? this.options.id : "",
		this.show()
	}

	,
	Window.prototype.show=function() {
		this.$el.css("visibility", "visible"),
		this.$el.fadeIn()
	}

	,
	Window.prototype.centerWindow=function() {
		var a,
		b,
		c,
		d=parseInt(this.options.references.body.position().top, 10) + parseInt(this.options.references.body.css("paddingTop"), 10);
		this.options.sticky ? (b=this.options.references.window.width() / 2 - this.$el.width() / 2, a=this.options.references.window.height() / 2 - this.$el.height() / 2): (b=this.options.references.window.width() / 2 - this.$el.width() / 2, a=this.options.references.window.height() / 2 - this.$el.height() / 2), d > a && (a=d), c=this.options.references.window.height() - d - (parseInt(this.options.elements.handle.css("height"), 10) + parseInt(this.options.elements.footer.css("height"), 10)) - 45, this.options.elements.body.css("maxHeight", c), this.$el.css("left", b), this.$el.css("top", a)
	}

	,
	Window.prototype.close=function() {
		var a=this;

		this.$el.trigger("close"),
		this.options.parent ? (this.options.parent.clearBlocker(), this.options.window_manager && this.options.window_manager.setFocused(this.options.parent)) : this.options.window_manager && this.options.window_manager.windows.length > 0 && this.options.window_manager.setNextFocused(),
		this.$el.fadeOut(function() {
			a.$el.remove()
		}

		),
		this.$windowTab && this.$windowTab.fadeOut(400, function() {
			a.$windowTab.remove()
		}

		)
	}

	,
	Window.prototype.setActive=function(a) {
		a ? (this.$el.addClass("active"), this.$windowTab && this.$windowTab.addClass("label-primary")): (this.$el.removeClass("active"), this.$windowTab && (this.$windowTab.removeClass("label-primary"), this.$windowTab.addClass("label-default")))
	}

	,
	Window.prototype.setIndex=function(a) {
		this.$el.css("zIndex", a)
	}

	,
	Window.prototype.setWindowTab=function(a) {
		this.$windowTab=a
	}

	,
	Window.prototype.getWindowTab=function() {
		return this.$windowTab
	}

	,
	Window.prototype.getTitle=function() {
		return this.options.title
	}

	,
	Window.prototype.getElement=function() {
		return this.$el
	}

	,
	Window.prototype.setSticky=function(a) {

		this.options.sticky=a,
		a=== !1 ? this.$el.css( {
			position: "absolute"
		}

		) : this.$el.css( {
			position: "fixed"
		}

		)
	}

	,
	Window.prototype.getSticky=function() {
		return this.options.sticky
	}

	,
	Window.prototype.setManager=function(a) {
		this.options.window_manager=a
	}

	,
	Window.prototype.initHandlers=function() {
		var b=this;

		this.$el.find("[data-dismiss=window]").on("click", function() {
			b.options.blocker || b.close()
		}

		),
		this.$el.off("mousedown"),
		this.$el.on("mousedown", function() {
			return b.options.blocker ? (b.options.blocker.getElement().trigger("focused"), b.options.blocker.blink(), void 0) : (b.$el.trigger("focused"), (b.$el.hasClass("ns-resize") || b.$el.hasClass("ew-resize")) && (a("body > *").addClass("disable-select"), b.resizing= !0, b.offset= {}

			, b.offset.x=event.pageX, b.offset.y=event.pageY, b.window_info= {
				top: b.$el.position().top,
					left: b.$el.position().left,
					width: b.$el.width(),
					height: b.$el.height()
			}

			, event.offsetY < 5 && b.$el.addClass("north"), event.offsetY > b.$el.height() - 5 && b.$el.addClass("south"), event.offsetX < 5 && b.$el.addClass("west"), event.offsetX > b.$el.width() - 5 && b.$el.addClass("east")), void 0)
		}

		),
		b.options.references.body.on("mouseup", function() {
			b.resizing= !1, a("body > *").removeClass("disable-select"), b.$el.removeClass("west"), b.$el.removeClass("east"), b.$el.removeClass("north"), b.$el.removeClass("south")
		}

		),
		b.options.elements.handle.off("mousedown"),
		b.options.elements.handle.on("mousedown", function(c) {
			b.options.blocker || (b.moving= !0, b.offset= {}

			, b.offset.x=c.pageX - b.$el.position().left, b.offset.y=c.pageY - b.$el.position().top, a("body > *").addClass("disable-select"))
		}

		),
		b.options.elements.handle.on("mouseup", function() {
			b.moving= !1, a("body > *").removeClass("disable-select")
		}

		),
		b.options.references.body.on("mousemove", function(a) {
			if (b.moving) {
					{
					b.options.elements.handle.position().top, b.options.elements.handle.position().left
				}

				b.$el.css("top", a.pageY - b.offset.y), b.$el.css("left", a.pageX - b.offset.x)
			}

			b.options.resizable && b.resizing && (b.$el.hasClass("east") && b.$el.css("width", a.pageX - b.window_info.left), b.$el.hasClass("west") && (b.$el.css("left", a.pageX), b.$el.css("width", b.window_info.width + (b.window_info.left - a.pageX))), b.$el.hasClass("south") && b.$el.css("height", a.pageY - b.window_info.top), b.$el.hasClass("north") && (b.$el.css("top", a.pageY), b.$el.css("height", b.window_info.height + (b.window_info.top - a.pageY))))
		}

		),
		this.$el.on("mousemove", function(a) {
			b.options.blocker || b.options.resizable && (a.offsetY > b.$el.height() - 5 || a.offsetY < 5 ? b.$el.addClass("ns-resize"): b.$el.removeClass("ns-resize"), a.offsetX > b.$el.width() - 5 || a.offsetX < 5 ? b.$el.addClass("ew-resize"): b.$el.removeClass("ew-resize"))
		}

		)
	}

	,
	Window.prototype.resize=function(a) {
		a=a || {}

		,
		a.top && this.$el.css("top", a.top),
		a.left && this.$el.css("left", a.left),
		a.height && this.$el.css("height", a.height),
		a.width && this.$el.css("width", a.width)
	}

	,
	Window.prototype.setBlocker=function(a) {
		this.options.blocker=a,
		this.$el.find(".disable-shade").remove();
		var b='<div class="disable-shade"></div>';
		this.options.elements.body.append(b),
		this.options.elements.body.addClass("disable-scroll"),
		this.options.elements.footer.append(b),
		this.$el.find(".disable-shade").fadeIn(),
		this.options.blocker.getParent() || this.options.blocker.setParent(this)
	}

	,
	Window.prototype.getBlocker=function() {
		return this.options.blocker
	}

	,
	Window.prototype.clearBlocker=function() {

		this.options.elements.body.removeClass("disable-scroll"),
		this.$el.find(".disable-shade").fadeOut(function() {
			this.remove()
		}

		),
		delete this.options.blocker
	}

	,
	Window.prototype.setParent=function(a) {
		this.options.parent=a,
		this.options.parent.getBlocker() || this.options.parent.setBlocker(this)
	}

	,
	Window.prototype.getParent=function() {
		return this.options.parent
	}

	,
	Window.prototype.blink=function() {
			{

			var a=this,
			b=this.$el.hasClass("active"),
			c=setInterval(function() {
				a.$el.toggleClass("active")
			}

			, 250);

			setTimeout(function() {
				clearInterval(c), b && a.$el.addClass("active")
			}

			, 1e3)
		}
	}

	,
	a.fn.window=function(b) {
		b=b || {}

		;

		var c,
		d=a.extend( {

			fromElement: this,
			selectors: {}
		}

		, b || {}

		);

		if ("object"==typeof b) d.selectors.handle && this.find(d.selectors.handle).css("cursor", "move"),
		a(this).hasClass("window") || a(this).addClass("window"),
		c=new Window(a.extend( {}

		, d, d)),
		this.data("window", c);

		else if ("string"==typeof b) switch (b) {
			case "close":
				this.data("window").close();
			break;
			case "show":
				this.data("window").show()
		}

		return this
	}

	,
	a("[data-window-target]").off("click"),
	a("[data-window-target]").on("click", function() {

		var b=a(this),
		c= {
			selectors: {}
		}

		;
		b.data("windowTitle") && (c.title=b.data("windowTitle")), b.data("titleHandle") && (c.selectors.title=b.data("titleHandle")), b.data("windowHandle") && (c.selectors.handle=b.data("windowHandle")), a(b.data("windowTarget")).window(c)
	}

	)
}

(jQuery);
var WindowManager=null;

 ! function(a) {
	"use strict";

	WindowManager=function(a) {

		return this.windows=[],
		a=a || {}

		,
		this.initialize(a),
		this
	}

	,
	WindowManager.prototype.findWindowByID=function(b) {
		var c=null;

		return a.each(this.windows, function(a, d) {
			console.log(arguments), d.id===b && (c=d)
		}

		),
		c
	}

	,
	WindowManager.prototype.destroyWindow=function(b) {
		var c=this;

		a.each(this.windows, function(a, d) {
			d===b && (c.windows.splice(a, 1), c.resortWindows())
		}

		)
	}

	,
	WindowManager.prototype.resortWindows=function() {
		var b=900;

		a.each(this.windows, function(a, c) {
			c.setIndex(b + a)
		}

		)
	}

	,
	WindowManager.prototype.setFocused=function(b) {
		for (var c;
		b.getBlocker();
		) b=b.getBlocker();

		a.each(this.windows, function(a, d) {
			d.setActive( !1), d===b && (c=a)
		}

		),
		this.windows.push(this.windows.splice(c, 1)[0]),
		b.setActive( !0),
		this.resortWindows()
	}

	,
	WindowManager.prototype.initialize=function(b) {
		this.options=b,
		this.options.container && a(this.options.container).addClass("window-pane")
	}

	,
	WindowManager.prototype.setNextFocused=function() {
		this.setFocused(this.windows[this.windows.length - 1])
	}

	,
	WindowManager.prototype.addWindow=function(b) {
		var c=this;

		return b.getElement().on("focused", function() {
			c.setFocused(b)
		}

		),
		b.getElement().on("close", function() {
			c.destroyWindow(b), b.getWindowTab() && b.getWindowTab().remove()
		}

		),
		this.options.container && (b.setWindowTab(a('<span class="label label-default">'+ b.getTitle() + '<button class="close">x</button></span>')), b.getWindowTab().find(".close").on("click", function() {
			b.close()
		}

		), b.getWindowTab().on("click", function() {
			c.setFocused(b), b.getSticky() && window.scrollTo(0, b.getElement().position().top)
		}

		), a(this.options.container).append(b.getWindowTab())),
		this.windows.push(b),
		b.setManager(this),
		this.setFocused(b),
		b
	}

	,
	WindowManager.prototype.createWindow=function(a) {
		var b=Object.create(a);
		this.options.windowTemplate && !b.template && (b.template=this.options.windowTemplate);
		var c=new Window(b);
		return this.addWindow(c)
	}
}

(jQuery);