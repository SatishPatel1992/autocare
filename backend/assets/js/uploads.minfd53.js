! function(global, factory) {
    if ("function" == typeof define && define.amd) define("/forms/uploads", ["jquery", "Site"], factory);
    else if ("undefined" != typeof exports) factory(require("jquery"), require("Site"));
    else {
        var mod = {
            exports: {}
        };
        factory(global.jQuery, global.Site), global.formsUploads = mod.exports
    }
}(this, function(_jquery, _Site) {
    "use strict";
    var _jquery2 = babelHelpers.interopRequireDefault(_jquery),
        Site = babelHelpers.interopRequireWildcard(_Site);
    (0, _jquery2.default)(document).ready(function($) {
        Site.run()
    }), (0, _jquery2.default)("#exampleUploadForm").fileupload({
        url: "../../server/fileupload/",
        dropzone: (0, _jquery2.default)("#exampleUploadForm"),
        filesContainer: (0, _jquery2.default)(".file-list"),
        uploadTemplateId: !1,
        downloadTemplateId: !1,
        uploadTemplate: tmpl('{% for (var i=0, file; file=o.files[i]; i++) { %}<div class="file-item-wrap template-upload fade col-lg-2 col-md-4 col-sm-6 {%=file.type.search("image") !== -1? "image" : "other-file"%}"><div class="file-item"><div class="preview vertical-align"><div class="file-action-wrap"><div class="file-action">{% if (!i && !o.options.autoUpload) { %}<i class="icon md-upload start" data-toggle="tooltip" data-original-title="Upload file" aria-hidden="true"></i>{% } %}{% if (!i) { %}<i class="icon md-close cancel" data-toggle="tooltip" data-original-title="Stop upload file" aria-hidden="true"></i>{% } %}</div></div></div><div class="info-wrap"><div class="title">{%=file.name%}</div></div><div class="progress progress-striped active" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" role="progressbar"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div></div></div>{% } %}'),
        downloadTemplate: tmpl('{% for (var i=0, file; file=o.files[i]; i++) { %}<div class="file-item-wrap template-download fade col-lg-2 col-md-4 col-sm-6 {%=file.type.search("image") !== -1? "image" : "other-file"%}"><div class="file-item"><div class="preview vertical-align"><div class="file-action-wrap"><div class="file-action"><i class="icon md-delete delete" data-toggle="tooltip" data-original-title="Delete files" aria-hidden="true"></i></div></div><img src="{%=file.url%}"/></div><div class="info-wrap"><div class="title">{%=file.name%}</div></div></div></div>{% } %}'),
        forceResize: !0,
        previewCanvas: !1,
        previewMaxWidth: !1,
        previewMaxHeight: !1,
        previewThumbnail: !1
    }).on("fileuploadprocessalways", function(e, data) {
        for (var length = data.files.length, i = 0; i < length; i++) data.files[i].type.match(/^image\/(gif|jpeg|png|svg\+xml)$/) ? data.files[i].filetype = "image" : data.files[i].filetype = "other-file"
    }).on("fileuploadadded", function(e) {
        var $this = (0, _jquery2.default)(e.target);
        $this.find(".file-item-wrap").length > 0 ? $this.addClass("has-file") : $this.removeClass("has-file")
    }).on("fileuploadfinished", function(e) {
        var $this = (0, _jquery2.default)(e.target);
        $this.find(".file-item-wrap").length > 0 ? $this.addClass("has-file") : $this.removeClass("has-file")
    }).on("fileuploaddestroyed", function(e) {
        var $this = (0, _jquery2.default)(e.target);
        $this.find(".file-item-wrap").length > 0 ? $this.addClass("has-file") : $this.removeClass("has-file")
    }).on("click", function(e) {
        0 === (0, _jquery2.default)(e.target).parents(".file-item-wrap").length && (0, _jquery2.default)("#inputUpload").trigger("click")
    }), (0, _jquery2.default)(document).bind("dragover", function(e) {
        var dropZone = (0, _jquery2.default)("#exampleUploadForm"),
            timeout = window.dropZoneTimeout;
        timeout ? clearTimeout(timeout) : dropZone.addClass("in");
        var found = !1,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = !0;
                break
            }
            node = node.parentNode
        } while (null !== node);
        found ? dropZone.addClass("hover") : dropZone.removeClass("hover"), window.dropZoneTimeout = setTimeout(function() {
            window.dropZoneTimeout = null, dropZone.removeClass("in hover")
        }, 100)
    }), (0, _jquery2.default)("#inputUpload").on("click", function(e) {
        e.stopPropagation()
    }), (0, _jquery2.default)("#uploadlink").on("click", function(e) {
        e.stopPropagation()
    })
});