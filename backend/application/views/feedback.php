<link href="<?php echo base_url();?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.minfd53.js?v4.0.1"></script>

<form method="post" name="feedback_form" id="feedback_form">
    <input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="jobcard_id">
    <input type="hidden" value="<?php echo $questions[0]['garage_id']; ?>" name="garage_id">
    <div class="survey-body-wrapper">
        <div>
            <table width="100%">
                <tr>
                    <td colspan="2">
                            <fieldset id="QuestionSection_2072" class="Q1 survey-question-wrapper  has-separator">
                            <span style="color: #545E6B;font-size: 15px;">Date of service: <b> <?php echo date('d-m-Y',strtotime($questions[0]['date'])); ?>.</b></span><br>
                            <span style="color: #545E6B;font-size: 15px;">Service by : <b><?php echo $questions && $questions[0]['garage_name'] ? strtolower($questions[0]['garage_name']) : ''; ?>.</b></span>
                            <br><br>
                            <legend class="question-container"><span id="question-text-span">How would you rate the service on the following areas ?</span><span id="errorSpan_question_2072" role="alert" aria-atomic="true" class="error hidden"></span>
                            </legend>
                            <div class="answer-container matrix-multipoint-question has-mobile-on">
                            <div class="table-wrapper table-responsive">
                            <table class="parent-table">
                            <thead>
                            <tr>
                            <td width="30%"></td>
                            <td width="11%"><div class="answer-options"><div class="controls">
                            <span class="control-label">Poor</span></div></div></td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Fair</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Good</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Very Good</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Excellent</span></div></div>
                            </td>
                            <td width="11%"><div class="answer-options"><div class="controls">
                            <span class="control-label">N/A</span></div></div>
                            </td>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach($questions as $k => $v) { ?>
                            <tr class=" this-height">
                            <td class="this-accordion">
                                <div class="answer-options rotate">
                                    <div class="controls">
                                    <div class="control-label">
                                    <span id="question-text-span"><?php echo $v['question']; ?></span>
                                    </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="1" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="blinker"></span>
                            <span class="control-label">Poor</span>
                            </label>
                            </td>

                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="2" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="blinker"></span><span class="control-label">Fair</span>
                            </label>
                            </td>

                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="3" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="control-label">Good</span>
                            </label>
                            </td>
                            
                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="4" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="control-label">Very Good</span>
                            </label>
                            </td>
                            
                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="5" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="blinker"></span>
                            <span class="control-label">Excellent</span>
                            </label>
                            </td>
                            
                            <td>
                            <label class="controls control-selection">
                            <input type="radio" name="answer[<?php echo $v['question_id']; ?>]" value="0" class="radio-check">
                            <span class="qp-icomoon-icons control-indicator"></span>
                            <span class="blinker"></span><span class="control-label">N/A</span>
                            <div class="control-label matrix-anchor hidden"></div>
                            </label>
                            </td>
                            </tr>
                            <?php } ?>    
                            </tbody>
                            </table>
                            </div>
                            </div>
                            </fieldset>
                    </td>
                </tr>
            </table>
            <div class="row">
            <div class="col-lg-6">
            <div class="stars starrr" data-rating="0"></div>
            <input type="hidden" name="ratings" id="ratings-hidden">
            <textarea cols="5" rows="5" name="overall_feedback" placeholder="Overall Feedback" class="form-control input-sm"></textarea><br>
            <button type="button" class="btn btn-primary btn-sm" onclick="postFeedback()"> Submit 
            </button>
            </div>
            </div>
        </div>
    </div>
<style>
 .animated {
    -webkit-transition: height 0.2s;
    -moz-transition: height 0.2s;
    transition: height 0.2s;
}

.stars
{
    margin: 20px 0;
    font-size: 24px;
    color: #d17581;
}
</style>
</form>
<script>
    function postFeedback() {
        $.ajax({
                method:'POST',
                url:'Transcation/InsertOperation',
                data: $('#feedback_form').serialize()+'&table_name=tbl_feedback',
                success:function(result) {
                    alert("Thanks for your feedback!.");
                    window.close();
                    
                    
                }
            });
    }
    (function(e) {
    var t, o = {
            className: "autosizejs",
            append: "",
            callback: !1,
            resizeDelay: 10
        },
        i = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',
        n = ["fontFamily", "fontSize", "fontWeight", "fontStyle", "letterSpacing", "textTransform", "wordSpacing", "textIndent"],
        s = e(i).data("autosize", !0)[0];
    s.style.lineHeight = "99px", "99px" === e(s).css("lineHeight") && n.push("lineHeight"), s.style.lineHeight = "", e.fn.autosize = function(i) {
        return this.length ? (i = e.extend({}, o, i || {}), s.parentNode !== document.body && e(document.body).append(s), this.each(function() {
            function o() {
                var t, o;
                "getComputedStyle" in window ? (t = window.getComputedStyle(u, null), o = u.getBoundingClientRect().width, e.each(["paddingLeft", "paddingRight", "borderLeftWidth", "borderRightWidth"], function(e, i) {
                    o -= parseInt(t[i], 10)
                }), s.style.width = o + "px") : s.style.width = Math.max(p.width(), 0) + "px"
            }

            function a() {
                var a = {};
                if (t = u, s.className = i.className, d = parseInt(p.css("maxHeight"), 10), e.each(n, function(e, t) {
                        a[t] = p.css(t)
                    }), e(s).css(a), o(), window.chrome) {
                    var r = u.style.width;
                    u.style.width = "0px", u.offsetWidth, u.style.width = r
                }
            }

            function r() {
                var e, n;
                t !== u ? a() : o(), s.value = u.value + i.append, s.style.overflowY = u.style.overflowY, n = parseInt(u.style.height, 10), s.scrollTop = 0, s.scrollTop = 9e4, e = s.scrollTop, d && e > d ? (u.style.overflowY = "scroll", e = d) : (u.style.overflowY = "hidden", c > e && (e = c)), e += w, n !== e && (u.style.height = e + "px", f && i.callback.call(u, u))
            }

            function l() {
                clearTimeout(h), h = setTimeout(function() {
                    var e = p.width();
                    e !== g && (g = e, r())
                }, parseInt(i.resizeDelay, 10))
            }
            var d, c, h, u = this,
                p = e(u),
                w = 0,
                f = e.isFunction(i.callback),
                z = {
                    height: u.style.height,
                    overflow: u.style.overflow,
                    overflowY: u.style.overflowY,
                    wordWrap: u.style.wordWrap,
                    resize: u.style.resize
                },
                g = p.width();
            p.data("autosize") || (p.data("autosize", !0), ("border-box" === p.css("box-sizing") || "border-box" === p.css("-moz-box-sizing") || "border-box" === p.css("-webkit-box-sizing")) && (w = p.outerHeight() - p.height()), c = Math.max(parseInt(p.css("minHeight"), 10) - w || 0, p.height()), p.css({
                overflow: "hidden",
                overflowY: "hidden",
                wordWrap: "break-word",
                resize: "none" === p.css("resize") || "vertical" === p.css("resize") ? "none" : "horizontal"
            }), "onpropertychange" in u ? "oninput" in u ? p.on("input.autosize keyup.autosize", r) : p.on("propertychange.autosize", function() {
                "value" === event.propertyName && r()
            }) : p.on("input.autosize", r), i.resizeDelay !== !1 && e(window).on("resize.autosize", l), p.on("autosize.resize", r), p.on("autosize.resizeIncludeStyle", function() {
                t = null, r()
            }), p.on("autosize.destroy", function() {
                t = null, clearTimeout(h), e(window).off("resize", l), p.off("autosize").off(".autosize").css(z).removeData("autosize")
            }), r())
        })) : this
    }
})(window.jQuery || window.$);

var __slice = [].slice;
(function(e, t) {
    var n;
    n = function() {
        function t(t, n) {
            var r, i, s, o = this;
            this.options = e.extend({}, this.defaults, n);
            this.$el = t;
            s = this.defaults;
            for (r in s) {
                i = s[r];
                if (this.$el.data(r) != null) {
                    this.options[r] = this.$el.data(r)
                }
            }
            this.createStars();
            this.syncRating();
            this.$el.on("mouseover.starrr", "span", function(e) {
                return o.syncRating(o.$el.find("span").index(e.currentTarget) + 1)
            });
            this.$el.on("mouseout.starrr", function() {
                return o.syncRating()
            });
            this.$el.on("click.starrr", "span", function(e) {
                return o.setRating(o.$el.find("span").index(e.currentTarget) + 1)
            });
            this.$el.on("starrr:change", this.options.change)
        }
        t.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function(e, t) {}
        };
        t.prototype.createStars = function() {
            var e, t, n;
            n = [];
            for (e = 1, t = this.options.numStars; 1 <= t ? e <= t : e >= t; 1 <= t ? e++ : e--) {
                n.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"))
            }
            return n
        };
        t.prototype.setRating = function(e) {
            if (this.options.rating === e) {
                e = void 0
            }
            this.options.rating = e;
            this.syncRating();
            return this.$el.trigger("starrr:change", e)
        };
        t.prototype.syncRating = function(e) {
            var t, n, r, i;
            e || (e = this.options.rating);
            if (e) {
                for (t = n = 0, i = e - 1; 0 <= i ? n <= i : n >= i; t = 0 <= i ? ++n : --n) {
                    this.$el.find("span").eq(t).removeClass("glyphicon-star-empty").addClass("glyphicon-star")
                }
            }
            if (e && e < 5) {
                for (t = r = e; e <= 4 ? r <= 4 : r >= 4; t = e <= 4 ? ++r : --r) {
                    this.$el.find("span").eq(t).removeClass("glyphicon-star").addClass("glyphicon-star-empty")
                }
            }
            if (!e) {
                return this.$el.find("span").removeClass("glyphicon-star").addClass("glyphicon-star-empty")
            }
        };
        return t
    }();
    return e.fn.extend({
        starrr: function() {
            var t, r;
            r = arguments[0], t = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var i;
                i = e(this).data("star-rating");
                if (!i) {
                    e(this).data("star-rating", i = new n(e(this), r))
                }
                if (typeof r === "string") {
                    return i[r].apply(i, t)
                }
            })
        }
    })
})(window.jQuery, window);

$(function() {
    return $(".starrr").starrr()
})

$(function() {
  var ratingsField = $('#ratings-hidden');
  $('.starrr').on('starrr:change', function(e, value) {
    ratingsField.val(value);
  });
});
</script>