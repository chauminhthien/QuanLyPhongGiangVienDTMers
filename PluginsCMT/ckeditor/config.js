/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
		config.language = 'vi';
		config.skin = 'office2013';
        // config.uiColor = '#AADC6E';
        
        config.toolbar_Full = [
            ['Source','-','Save','NewPage','Preview','-','Templates'],
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
            ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
            '/',
            ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['BidiLtr', 'BidiRtl' ],
            ['Link','Unlink','Anchor'],
            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
            '/',
            ['Styles','Format','Font','FontSize'],
            ['TextColor','BGColor'],
            ['Maximize', 'ShowBlocks','-','About']
            ];
        
        config.entities = false;
        //config.entities_latin = false;
         var baseUrl = '/freelancer/hnsb.vn/Plugins/ckeditor/';

        config.filebrowserBrowseUrl = baseUrl+'ckfinder/ckfinder.html';

        config.filebrowserImageBrowseUrl = baseUrl+'ckfinder/ckfinder.html?type=Images';

        config.filebrowserFlashBrowseUrl = baseUrl+'ckfinder/ckfinder.html?type=Flash';

        config.filebrowserUploadUrl = baseUrl+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

        config.filebrowserImageUploadUrl = baseUrl+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

        config.filebrowserFlashUploadUrl = baseUrl+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
		//youtube
		config.extraPlugins = 'youtube';
		// config.youtube_width = '640';
		// config.youtube_height = '480';
		// config.youtube_related = false;
		// config.youtube_older = false;
		// config.youtube_privacy = false;
};
