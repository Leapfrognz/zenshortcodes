<?php

/**
 * Simple subclass of ShortcodeParser to add a method getTags, as 
 * extractTags is protected in the ShortcodeParser class
 */
class ZenShortcodeParser extends ShortcodeParser {

	public function getTags($content) {
		return $this->extractTags($content);
	}

}