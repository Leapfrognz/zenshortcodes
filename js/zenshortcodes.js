(function($) {

	$.entwine('ss', function($) {

		// open the shortcode selection dialog
		$('textarea.htmleditor').entwine({
			
			openZenShortcodeDialog: function() {

				var self = this,
						ed = this.getEditor(),
						selected = $(ed.getSelectedNode()),
						pageID = $('input[name=ID]').val(),
						dialog = $('.htmleditorfield-zenshortcodedialog'),
						url = $('#cms-editor-dialogs').data('url-zenshortcodeform') + '?pageID=' + pageID;

				if(selected.hasClass('zen-shortcode')) {
					var content = selected.html();
					url += '&content=' + encodeURIComponent(content);
				} 

				if(dialog.length) {
					dialog.html('');
					dialog.addClass('loading');
				} else {
					dialog = $('<div class="htmleditorfield-dialog htmleditorfield-zenshortcodedialog loading">');
					$('body').append(dialog);
				}

				$('.ui-dialog').css({
					width: '1000px',
					left: '450px'
				})

				dialog.open();

				$.ajax({
					url: url,
					complete: function() {
						dialog.removeClass('loading');
					},
					success: function(html) {
						$('.ui-dialog').css({
							width: '1000px',
							left: '450px'
						});
						dialog.html(html);
						dialog.getForm().setElement(self);
						dialog.trigger('ssdialogopen');
					}
				});

			}

		});

		// handle change of type
		$('select[name=ZenShortcodeType]').entwine({

			onchange: function(e) {
				
				var type = $(this).val(),
						target;

				if(type.length) {
					target = $('.' + type);
				}

				// hide others
				$('.ZenShortcodeGroup').hide();

				if(target && target.length) {
					target.show();
				}
				
			}

		});

		$('form.htmleditorfield-zenshortcodeform').entwine({

			onsubmit: function(e) {
				
				var self = this;

				var data = $(this).serialize();

				var url = $(this).attr('action');

				$.ajax({
					url: url,
					data: data,
					dataType: 'json',
					type: 'post',
					success: function(model) {
						self.modifySelection(function(ed){
						 	self.insertShortCode(ed, model);
						 	ed.repaint();
						});
						self.getDialog().close();
					}
				});

				return false;
			},

			insertShortCode: function(ed, model) {

				this.setBookmark(ed.createBookmark());

				var code = '<div class="zen-shortcode" data-shortcodeID="' + model.ID + '" contenteditable="false">[zenshortcode id="' + model.ID + '" /]</div>';

				var node = this.getSelection();

				var replacee = (node && node.is('div') && node.hasClass('zen-shortcode')) ? node : null;

				if(replacee) {
					replacee.replaceWith(code);
				}

				if(!replacee) {
					ed.insertContent(code, {skip_undo : 1});
				}

				ed.addUndo();
				ed.moveToBookmark(this.getBookmark());
				this.setBookmark(null);
				ed.repaint();

			},

		});









/*

		// handle actions on the shortcode selection dialog
		$('form.htmleditorfield-zenshortcodeform').entwine({

			onsubmit: function(e) {

				var self = this,
						type = this.find('select[name=ZenShortcodeType]').val();

				if(!type || type == '') {
					alert('Please select a shortcode type');
					return false;
				} 

				this.find('select[name=ZenShortcodeType]').val('').trigger('liszt:updated');
				this.openShortcodeDialog(type);

				return false;

			},

			openShortcodeDialog: function(type) {

				var self = this, 
						ed = this.getEditor(),
						selected = $(ed.getSelectedNode()).clone(),
						content = $('<div />').append(selected).html(),
						pageID = $('input[name=ID]').val(),
						url = $('#cms-editor-dialogs').data('url-zenshortcodebase') + type + '?pageID=' + pageID + '&content=' + encodeURIComponent(content),
						dialog = $('.htmleditorfield-zenshortcodedialog');
				
				var editor = $('#' + ed.getInstance().id);

				if(dialog.length) {
					dialog.html('');
					dialog.addClass('loading');
				} else {
					dialog = $('<div class="htmleditorfield-dialog htmleditorfield-zenshortcodedialog loading">');
					$('body').append(dialog);
				}

				dialog.open();

				$.ajax({
					url: url,
					complete: function() {
						dialog.removeClass('loading');
					},
					success: function(html) {
						dialog.html(html);
						dialog.getForm().setElement(editor);
						dialog.trigger('ssdialogopen');
					}
				});


			}

		});

*/


	});

})(jQuery);