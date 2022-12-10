/*!
 * Remark Material (http://getbootstrapadmin.com/remark)
 * Copyright 2017 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */

! function (global, factory) {
	if ("function" == typeof define && define.amd) define("/Plugin/material", ["exports", "jquery", "Plugin"], factory);
	else if ("undefined" != typeof exports) factory(exports, require("jquery"), require("Plugin"));
	else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, global.jQuery, global.Plugin), global.PluginMaterial = mod.exports
	}
}(this, function (exports, _jquery, _Plugin2) {
	"use strict";

	function isChar(e) {
		return void 0 === e.which || "number" == typeof e.which && e.which > 0 && (!e.ctrlKey && !e.metaKey && !e.altKey && 8 !== e.which && 9 !== e.which)
	}
	Object.defineProperty(exports, "__esModule", {
		value: !0
	});
	var _jquery2 = babelHelpers.interopRequireDefault(_jquery),
		_Plugin3 = babelHelpers.interopRequireDefault(_Plugin2),
		FormMaterial = function (_Plugin) {
			function FormMaterial() {
				return babelHelpers.classCallCheck(this, FormMaterial), babelHelpers.possibleConstructorReturn(this, (FormMaterial.__proto__ || Object.getPrototypeOf(FormMaterial)).apply(this, arguments))
			}
			return babelHelpers.inherits(FormMaterial, _Plugin), babelHelpers.createClass(FormMaterial, [{
				key: "getName",
				value: function () {
					return "formMaterial"
				}
			}, {
				key: "render",
				value: function () {
					var $el = this.$el,
						$control = this.$control = $el.find(".form-control");
					if ($control.attr("data-hint") && $control.after("<div class=hint>" + $control.attr("data-hint") + "</div>"), $el.hasClass("floating")) {
						if ($control.hasClass("floating-label")) {
							var placeholder = $control.attr("placeholder");
							$control.attr("placeholder", null).removeClass("floating-label"), $control.after("<div class=floating-label>" + placeholder + "</div>")
						}
						null !== $control.val() && "undefined" !== $control.val() && "" !== $control.val() || $control.addClass("empty")
					}
					$control.next().is("[type=file]") && $el.addClass("form-material-file"), this.$file = $el.find("[type=file]"), this.bind(), $el.data("formMaterialAPI", this)
				}
			}, {
				key: "bind",
				value: function () {
					var _this2 = this,
						$el = this.$el,
						$control = this.$control = $el.find(".form-control");
					$el.on("keydown.site.material paste.site.material", ".form-control", function (e) {
						isChar(e) && $control.removeClass("empty")
					}).on("keyup.site.material change.site.material", ".form-control", function () {
						"" === $control.val() && void 0 !== $control[0].checkValidity && $control[0].checkValidity() ? $control.addClass("empty") : $control.removeClass("empty")
					}), this.$file.length > 0 && this.$file.on("focus", function () {
						_this2.$el.find("input").addClass("focus")
					}).on("blur", function () {
						_this2.$el.find("input").removeClass("focus")
					}).on("change", function () {
						var $this = (0, _jquery2.default)(this),
							value = "";
						_jquery2.default.each($this[0].files, function (i, file) {
							value += file.name + ", "
						}), (value = value.substring(0, value.length - 2)) ? $this.prev().removeClass("empty") : $this.prev().addClass("empty"), $this.prev().val(value)
					})
				}
			}]), FormMaterial
		}(_Plugin3.default);
	_Plugin3.default.register("formMaterial", FormMaterial), exports.default = FormMaterial
});

$(document).on("keyup change",".floating-label .form-control",function(e) {
    var input = $(e.currentTarget);
    if ($.trim(input.val()) !== '') {
        input.addClass('empty').removeClass('static');
    } else {
        input.removeClass('empty').removeClass('static');
    }
});