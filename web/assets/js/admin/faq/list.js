$(function(){
    var $attachmentPreview = $('.attachment-preview');

    $.each($attachmentPreview, function(name, value){
        var viewer = new Viewer(document.getElementById(this.id), {
            url: 'data-original'
        });
    });
});