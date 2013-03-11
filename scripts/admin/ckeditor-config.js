CKEDITOR.editorConfig = function( config )
{
    
    config.LimeReplacementFieldsType = $(this.element).data('LRFtype');
    config.LimeReplacementFieldsSID = $(this.element).data('LRFsid');
    config.LimeReplacementFieldsGID = $(this.element).data('LRFgid');
    config.LimeReplacementFieldsQID = $(this.element).data('LRFqid');
    config.LimeReplacementFieldsAction = $(this.element).data('LRFaction');
    config.LimeReplacementFieldsPath = LS.createUrl('admin/limereplacementfields/sa/index/');
    
    config.filebrowserBrowseUrl = LS.data.baseUrl + '/third_party/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = LS.data.baseUrl + '/third_party/kcfinder/browse.php?type=images'; 
    config.filebrowserFlashBrowseUrl = LS.data.baseUrl + '/third_party/kcfinder/browse.php?type=flash';

    config.filebrowserUploadUrl = LS.data.baseUrl + '/third_party/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = LS.data.baseUrl + '/third_party/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = LS.data.baseUrl + '/third_party/kcfinder/upload.php?type=flash';

    config.skin = 'ls-office2003';
    config.toolbarCanCollapse = false;
    config.resize_enabled = false;
    config.autoParagraph = false;
    config.entities = false;    
	
	if($('html').attr('dir') == 'rtl') {
		config.contentsLangDirection = 'rtl';
	}

    config.toolbar_popup =
    [
        ['Save','Createlimereplacementfields'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat','Source'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['BidiLtr', 'BidiRtl'],
        ['Link','Unlink','Anchor','Iframe'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        [ 'ShowBlocks','Templates']
    ];
    
    config.toolbar_inline =
    [
        ['Maximize','Createlimereplacementfields'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat','Source'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['BidiLtr', 'BidiRtl'],
        ['Link','Unlink','Anchor','Iframe'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        [ 'ShowBlocks','Templates'],
    ];


   

   config.toolbar_inline2 =
    [
        ['Maximize','Createlimereplacementfields'],
        ['Bold','Italic','Underline'],
        ['NumberedList','BulletedList','-','Outdent','Indent'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Image'],
        ['Source']
    ];


    config.extraPlugins = "ajax,limereplacementfields";
    config.toolbarStartupExpanded = true;
    config.toolbar = 'inline';
    config.smiley_path = "/upload/images/smiley/msn";
    config.width = 660;
};

(function () {
    CKEDITOR.plugins.addExternal('limereplacementfields', CKEDITOR.basePath + '../../scripts/admin/limereplacementfields/', 'plugin.js');
})();
