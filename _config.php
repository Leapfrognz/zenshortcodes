<?php

object::useCustomClass('HtmlEditorField_Toolbar', 'ZenHtmlEditorField_Toolbar');

// custom editor config stuff for cms 
$config = HtmlEditorConfig::get('cms');
$config->enablePlugins(array('zenshortcodes' => '/zenshortcodes/editor_plugin_src.js'));
$config->addButtonsToLine(2, 'zenshortcodes');	
$config->setOption('valid_elements', 'a[href|target|rel|class],b,br,font,img[src|id|width|height|align|hspace|vspace,KeepThis,true,TB_iframe],i,li,p,h1,h2,h3,h4,h5,h6,span[class],textformat[blockindent|indent|leading|leftmargin|rightmargin|tabstops],u,table,tr,td,*[contenteditable|class]');
$config->setOption('content_css', '/zenshortcodes/css/zenshortcodes.css');

ShortcodeParser::get('default')->register('zenshortcode', array('ZenShortCode', 'ShortcodeHandler'));