! function(global, factory) {
    if ("function" == typeof define && define.amd) define("/Section/Menubar", ["exports", "jquery", "Component"], factory);
    else if ("undefined" != typeof exports) factory(exports, require("jquery"), require("Component"));
    else {
        var mod = {
            exports: {}
        };
        factory(mod.exports, global.jQuery, global.Component), global.SectionMenubar = mod.exports
    }
} (this, function(exports, _jquery, _Component2) {
    "use strict";
    Object.defineProperty(exports, "__esModule", {
        value: !0
    });
    var _jquery2 = babelHelpers.interopRequireDefault(_jquery),
        _Component3 = babelHelpers.interopRequireDefault(_Component2),
        $BODY = (0, _jquery2.default)("body"),
        $HTML = (0, _jquery2.default)("html"),
        Scrollable = function() {
            function Scrollable($el) {
                babelHelpers.classCallCheck(this, Scrollable), this.$el = $el, this.native = !1, this.api = null, this.init()
            }
            return babelHelpers.createClass(Scrollable, [{
                key: "init",
                value: function() {
                    $BODY.is(".site-menubar-native") ? this.native = !0 : this.api = this.$el.asScrollable({
                        namespace: "scrollable",
                        skin: "scrollable-inverse",
                        direction: "vertical",
                        contentSelector: ">",
                        containerSelector: ">"
                    }).data("asScrollable")
                }
            }, {
                key: "update",
                value: function() {
                    this.api && this.api.update()
                }
            }, {
                key: "enable",
                value: function() {
                    this.native || (this.api || this.init(), this.api && this.api.enable())
                }
            }, {
                key: "disable",
                value: function() {
                    this.api && this.api.disable()
                }
            }]), Scrollable
        }(),
        _class = function(_Component) {
            function _class() {
                var _ref;
                babelHelpers.classCallCheck(this, _class);
                for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) args[_key] = arguments[_key];
                var _this = babelHelpers.possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));
                return _this.setupMenu(), _this.$menuBody = _this.$el.children(".mm-panels"), _this.type = "fold", _this
            }
            return babelHelpers.inherits(_class, _Component), babelHelpers.createClass(_class, [{
                key: "initialize",
                value: function() {
                    this.$menuBody.length > 0 ? (this.initialized = !0, this.scrollable = new Scrollable(this.$menuBody), $HTML.removeClass("css-menubar").addClass("js-menubar"), this.change(this.type)) : this.initialized = !1
                }
            }, {
                key: "setupMenu",
                value: function() {
                    void 0 !== _jquery2.default.fn.mmenu && this.$el.mmenu({
                        offCanvas: !1,
                        navbars: [{
                            position: "bottom",
                            content: []
                        }]
                    })
                }
            }, {
                key: "getMenuApi",
                value: function() {
                    return this.$el.data("mmenu")
                }
            }, {
                key: "update",
                value: function() {
                    this.scrollable.update()
                }
            }, {
                key: "change",
                value: function(type) {
                    this.initialized && (this.reset(), this[type]())
                }
            }, {
                key: "animate",
                value: function(doing) {
                    var _this2 = this,
                        callback = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : function() {};
                    $BODY.addClass("site-menubar-changing"), doing.call(this), this.$el.trigger("changing.site.menubar"), setTimeout(function() {
                        callback.call(_this2), $BODY.removeClass("site-menubar-changing"), _this2.update(), _this2.$el.trigger("changed.site.menubar")
                    }, 500)
                }
            }, {
                key: "hoverTrigger",
                value: function() {
                    var _this3 = this;
                    this.$el.on("mouseenter", function() {
                        $BODY.addClass("site-menubar-hover"), setTimeout(function() {
                            _this3.scrollable.enable()
                        }, 500)
                    }).on("mouseleave", function() {
                        $BODY.removeClass("site-menubar-hover");
                        var api = _this3.getMenuApi();
                        api && api.openPanel((0, _jquery2.default)("#mm-0")), setTimeout(function() {
                            _this3.scrollable.disable()
                        }, 500)
                    })
                }
            }, {
                key: "hoverTriggerOff",
                value: function() {
                    this.$el.off("mouseenter"), this.$el.off("mouseleave")
                }
            }, {
                key: "reset",
                value: function() {
                    $BODY.removeClass("site-menubar-hide site-menubar-open site-menubar-fold site-menubar-unfold"), $HTML.removeClass("disable-scrolling")
                }
            }, {
                key: "open",
                value: function() {
                    this.animate(function() {
                        $BODY.addClass("site-menubar-open site-menubar-unfold"), $HTML.addClass("disable-scrolling")
                    }, function() {
                        this.scrollable.enable()
                    }), this.type = "open"
                }
            }, {
                key: "hide",
                value: function() {
                    this.animate(function() {
                        $BODY.addClass("site-menubar-hide site-menubar-unfold")
                    }, function() {
                        this.scrollable.enable()
                    }), this.type = "hide"
                }
            }, {
                key: "unfold",
                value: function() {
                    this.animate(function() {
                        $BODY.addClass("site-menubar-unfold"), this.hoverTriggerOff()
                    }, function() {
                        this.scrollable.enable(), this.triggerResize()
                    }), this.type = "unfold"
                }
            }, {
                key: "fold",
                value: function() {
                    this.scrollable.disable(), this.animate(function() {
                        $BODY.addClass("site-menubar-fold"), this.hoverTrigger()
                    }, function() {
                        this.triggerResize()
                    }), this.type = "fold"
                }
            }]), _class
        }(_Component3.default);
    exports.default = _class
});