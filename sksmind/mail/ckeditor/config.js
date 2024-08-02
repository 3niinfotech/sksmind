/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	 config.extraPlugins = 'allowsave';
	 config.height = 400;
     //filebrowserUploadUrl: '/uploader/upload.php?type=Files'
	config.extraPlugins = 'filebrowser';
	//config.filebrowserBrowseUrl = '/editor_1/ckeditor/plugins/browser/browser.php';
	//config.filebrowserUploadUrl = '/editor_1/ckeditor/plugins/browser/upload.php';
	//config.extraPlugins = 'popup';
	//  config.extraPlugins = 'imageuploader';
  config.filebrowserImageBrowseUrl = '/ckeditor/plugins/imageuploader/imgbrowser.php';
  config.filebrowserImageUploadUrl = '/ckeditor/plugins/imageuploader/imgupload.php';
  config.filebrowserBrowseUrl = '/ckeditor/plugins/pdw_file_browser/index.php?editor=ckeditor';
	  

};
