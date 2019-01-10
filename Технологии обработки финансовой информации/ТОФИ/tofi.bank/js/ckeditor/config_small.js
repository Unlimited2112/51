/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.height = '200px';
	config.width = '98%';
	config.language = 'ru';
	config.toolbar = 'GFSmall';
	config.skin = 'kama';
	
	config.toolbarCanCollapse = false;
	config.contentsCss = '/css/cms/wysiwyg.css';
	
	config.toolbar_GFSmall =
	[
	    ['OpenFull', 'Source'],
	    ['Bold','Italic','Underline','Strike'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	    ['Link','Unlink'],
	];
	
	config.removePlugins = 'about,colorbutton,colordialog,elementspath,flash,font,forms,horizontalrule,maximize,newpage,pagebreak,popup,preview,print,resize,save,scayt,smiley,showblocks,showborders,wysiwygarea,wsc';
	config.extraPlugins = 'openfull';
};
