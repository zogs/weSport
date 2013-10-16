/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	//Oembed
	//config.allowedContent = true;
	//config.extraPlugins = 'oembed';
	//config.oembed_maxWidth = '560';
	//config.oembed_maxHeight = '315';

	//config.toolbar = [
	//    [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ],
	//    [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
	//    '/',
	//    [ 'Bold', 'Italic' ]
	//];

	config.toolbar = [
		['Undo','Redo'],'-',['Scayt'],'-',['Link','Unlink','Image','Flash','Smiley','SpecialChar'],
		'/',
		['FontSize'],'-',['Bold','Italic','Underline','Strike'],['NumberedList','BulletedList','Blockquote','CreateDiv'],['JustifyLeft','JustifyRight','JustifyBlock'],
		'/',
		['oembed','MediaEmbed']
	]


};
