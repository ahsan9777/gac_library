/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	tinymce.create('tinymce.plugins.PVote', {
		init : function(ed, url) {
			var t = this, css = tinymce.explode(ed.settings.content_css);

			t.editor = ed;

			// Force absolute CSS urls	
			tinymce.each(css, function(u, k) {
				css[k] = ed.documentBaseURI.toAbsolute(u);
			});

			ed.addCommand('mcePVote', function() {
				ed.windowManager.open({
					file : ed.getParam("plugin_pVote_pageurl", url + "/pVote.html"),
					width : parseInt(ed.getParam("plugin_pVote_width", "550")),
					height : parseInt(ed.getParam("plugin_pVote_height", "600")),
					resizable : "yes",
					scrollbars : "yes",
					popup_css : css ? css.join(',') : ed.baseURI.toAbsolute("themes/" + ed.settings.theme + "/skins/" + ed.settings.skin + "/content.css"),
					inline : ed.getParam("plugin_pVote_inline", 1)
				}, {
					base : ed.documentBaseURI.getURI()
				});
			});

			ed.addButton('pVote', {title : 'pVote.pVote_desc', cmd : 'mcePVote'});
		},

		getInfo : function() {
			return {
				longname : 'PVote',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/pVote',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('pVote', tinymce.plugins.PVote);
})();