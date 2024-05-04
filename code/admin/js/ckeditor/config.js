/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	/*
	config.filebrowserBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserImageBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserFlashBrowseUrl = 'index.php?route=common/filemanager';
	config.filebrowserUploadUrl = 'index.php?route=common/filemanager';
	config.filebrowserImageUploadUrl = 'index.php?route=common/filemanager';
	config.filebrowserFlashUploadUrl = 'index.php?route=common/filemanager';		
	*/
	config.height = '550px';
	config.filebrowserWindowWidth = '800';
	config.filebrowserWindowHeight = '500';
	config.emailProtection = 'encode';
	config.resize_enabled = false;

	config.allowedContent = false;
    // The default plugins included in the basic setup define some buttons that
	// are not needed in a basic editor. They are removed here.
	config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';
	// Dialog windows are also simplified.
	config.removeDialogTabs = 'link:advanced';

	config.htmlEncodeOutput = false;
	config.entities = false;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.EnterMode = 'br';
	config.autoParagraph = false;
	
	//config.toolbar = 'Full';
	//config.toolbar = 'Basic';
	config.toolbar = 'Custom';

	config.toolbar_Custom = [
		['Source'],
		['Bold','Italic','Underline'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
		['Font','FontSize'],
		['TextColor','BGColor'],
	];
	
	// config.toolbar_Full = [
	// 	['Source'],
	// 	['Cut','Copy','Paste'],
	// 	['Undo','Redo'],
	// ];

	// config.toolbar_Full = [
	// 	['Source','-','Save','NewPage','Preview','-','Templates'],
	// 	['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
	// 	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	// 	['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
	// 	'/',
	// 	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	// 	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	// 	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	// 	['Link','Unlink','Anchor'],
	// 	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	// 	'/',
	// 	['Styles','Format','Font','FontSize'],
	// 	['TextColor','BGColor'],
	// 	['Maximize', 'ShowBlocks']
	// ];

};
CKEDITOR.on( 'instanceCreated', function( event ) {
	 editor.on( 'configLoaded', function() {

	  editor.config.basicEntities = false;
	  editor.config.entities_greek = false; 
	  editor.config.entities_latin = false; 
	  editor.config.entities_additional = '';

	 });
	});
