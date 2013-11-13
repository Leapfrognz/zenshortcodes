<?php

if(!defined('ZENSHORTCODES_PATH')) define('ZENSHORTCODES_PATH', rtrim(basename(dirname(__FILE__))));

object::useCustomClass('HtmlEditorField_Toolbar', 'ZenHtmlEditorField_Toolbar');

// custom editor config stuff for cms 
$config = HtmlEditorConfig::get('cms');
$config->enablePlugins(array('zenshortcodes' => '/'.ZENSHORTCODES_PATH.'/editor_plugin_src.js'));
$config->addButtonsToLine(2, 'zenshortcodes');	
$config->setOption('valid_elements', 'a[href|target|rel|class],b,br,font,img[src|id|width|height|align|hspace|vspace,KeepThis,true,TB_iframe],i,li,p,h1,h2,h3,h4,h5,h6,span[class],textformat[blockindent|indent|leading|leftmargin|rightmargin|tabstops],u,table,tr,td,*[contenteditable|class],p[class]');
$config->setOption('content_css', '/'.ZENSHORTCODES_PATH.'/css/zenshortcodes.css');

ShortcodeParser::get('default')->register('zenshortcode', array('ZenShortCode', 'ShortcodeHandler'));