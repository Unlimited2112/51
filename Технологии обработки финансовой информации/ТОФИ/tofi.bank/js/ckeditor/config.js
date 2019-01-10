/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.height = '440px';
	config.width = '98%';
	config.language = 'ru';
	config.toolbar = 'GF';
	config.skin = 'kama';
	
	config.toolbarCanCollapse = false;
	
	config.format_tags = 'p;h3;h4';
	config.stylesCombo_stylesSet = 'GF:/js/ckeditor/styles.js';
	config.contentsCss = '/css/cms/wysiwyg.css';

	config.toolbar_GF =
	[
	    ['Save','Source','-','Cut','Copy','Paste','PasteText','PasteFromWord',],
	    ['Undo','Redo','-','Find','Replace','-','RemoveFormat'],
	    ['Link','Unlink','Anchor'],
	    ['Image','MediaEmbed','Table','SpecialChar'],
	    '/',
	    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	    ['Format']
	];
	
	config.removePlugins = 'about,colorbutton,colordialog,elementspath,flash,font,forms,horizontalrule,maximize,newpage,pagebreak,popup,preview,print,resize,save,scayt,smiley,showblocks,showborders,wysiwygarea,wsc';
	config.extraPlugins = 'maximizeonstart,saveform,MediaEmbed';
};
