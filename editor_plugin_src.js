(function() {

	var availableLangs = ['en'];
	if(jQuery.inArray(tinymce.settings.language, availableLangs) != -1) {
		tinymce.PluginManager.requireLangPack('zenshortcodes');
	}

	var each = tinymce.each;

	tinymce.create('tinymce.plugins.ZenShortcodes', {

		getInfo : function() {
			return {
				longname : 'Zen shortcode inserter for tinymce for SilverStripe 3.1',
				author : 'James Ayers',
				version : "1.0"
			};
		},

		init : function(ed, url) {

			ed.addButton('zenshortcodes', {
				title: ed.getLang('tinymce_zenshortcodes.insertZenShortcode'), 
				cmd: 'zenshortcodes', 
				image: url + '/img/zenshortcode.gif'
			}); 

			ed.addCommand('zenshortcodes', function(ed) {
				jQuery('#' + this.id).entwine('ss').openZenShortcodeDialog();
			});

			ed.onClick.add(function(ed, e) {
				ed.dom.removeClass(tinyMCE.activeEditor.dom.select('div.zen-shortcode-selected'), 'zen-shortcode-selected')
				var target = jQuery(e.target);
				if(target.hasClass('zen-shortcode')) {
        	target.addClass('zen-shortcode-selected');
        	//jQuery('#' + this.id).entwine('ss').openZenShortcodeDialog();
        }
      });

      ed.onKeyUp.add(function(ed, e) {
      	if(e.keyCode == 8 || e.keyCode == 46) {
      		var selected = jQuery(ed.selection.getNode());
      		if(selected.hasClass('zen-shortcode')) {

						// ajax call to delete do
						var id = selected.data('shortcodeID'),
								form = jQuery(ed.formElement),
								url = form.attr('action') + '/delete/' + id; // TODO security


						selected.remove();

						// TODO  ajax call top delete the dataobject. 
						// make sure that we prevent undo in this instance so they cant re-add the shortcode back in once deleted.

						// if(id === undefined) {
						// 	selected.remove();
						// 	return;
						// }
							
						// jQuery.ajax({
						// 	url: url,
						// 	success: function() {
						// 		selected.remove();
						// 	}
						// });

      		}
      	}
      });

      ed.onDblClick.add(function(ed, e) {
      	ed.dom.removeClass(tinyMCE.activeEditor.dom.select('div.zen-shortcode-selected'), 'zen-shortcode-selected')
				var target = jQuery(e.target);
				if(target.hasClass('zen-shortcode')) {
					target.addClass('zen-shortcode-selected');
					jQuery('#' + this.id).entwine('ss').openZenShortcodeDialog();
				}
      });

		}

	});

	tinymce.PluginManager.add('zenshortcodes', tinymce.plugins.ZenShortcodes);

})();
