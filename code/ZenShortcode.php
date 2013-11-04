<?php

/**
 * Base shortcode class
 * extend this to create your ownh shortcode
 * MUST have $shortcode_name static
 * MUST have a static parse function
 */
class ZenShortcode extends DataObject {

	static $shortcode_name = 'Zen shortcode';

	static function shortcodeHandler($arguments, $content, $parser, $shortcode) {

		if(!isset($arguments['id'])) return null;
		
		$zenShortcode = ZenShortCode::get()->ById(Convert::raw2sql($arguments['id']));

		if(!$zenShortcode) return null;

		return $zenShortcode->parser($arguments, $content, $parser, $shortcode);

	}

}