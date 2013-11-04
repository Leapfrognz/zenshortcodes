<?php

class ZenHtmlEditorField_Toolbar extends HtmlEditorField_Toolbar {
	
	/**
	 * TODO: 
	 * Nice to be able to drag the shortcode around the content.
	 * Double click the short code to automatically open the dialog
	 * Disable all other buttons and enable the shortcode button when shortcode is selected
	 */

	private static $allowed_actions = array(
		'LinkForm',
		'MediaForm',
		'viewfile',
		'ZenShortcodeForm'
	);

	/**
	 * Add in zenshortcode requirements
	 * @param [type] $controller
	 * @param [type] $name
	 */
	public function __construct($controller, $name) {
		parent::__construct($controller, $name);
		Requirements::javascript(ZENSHORTCODES_PATH.'/js/zenshortcodes.js');
		Requirements::css(ZENSHORTCODES_PATH.'/css/zenshortcodes.css');
	}

	/**
	 * Override forTemplate, adding in another data-url for zenshortcodes
	 * @return string
	 */
	public function forTemplate() {

		$zen = Injector::inst()->get('ZenShortcodeController', true);

		$str = sprintf(
			'<div id="cms-editor-dialogs" data-url-linkform="%s" data-url-mediaform="%s" data-url-zenshortcodeform="%s"></div>',
			Controller::join_links($this->controller->Link(), $this->name, 'LinkForm', 'forTemplate'),
			Controller::join_links($this->controller->Link(), $this->name, 'MediaForm', 'forTemplate'),
			Controller::join_links('ZenShortcodeController', 'ZenShortcodeForm', 'forTemplate')
		);
		return $str;
	}

}




