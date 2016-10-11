$(function(){
    $('#operator-reply').on('click', function(){
      var param = $('form').serialize();
      $.post($(this).data('url'), param, function(data) {
         window.location.reload();
      });
    });

    $('.faq-auto-reply').on('click', function(){
        $.post($(this).data('url'), function(data) {
            window.location.reload();
        });
    });

    $('#customer-reply').on('click', function(){
        var param = $('form').serialize();
        $.post($(this).data('url'), param, function(data) {
            window.location.reload();
        });
    });

    $('.ticket-urge').click(function(){
        $.post($(this).data('url'), function(data){
            window.location.reload();
        });
    });

    $('.ticket-confirm').click(function(){
        $.post($(this).data('url'), function(data){
            window.location.reload();
        });
    });

    $('.ticket-close').click(function(){
        $.post($(this).data('url'), function(data){
            if (data.success) {
                window.location.href = data.gotoUrl;
            }
        });
    });

    $(".complaint-btn").on('click',function(){
        var url = $(this).data('url');
        $.get(url,function(res){
            $("#complaintModal").html(res).modal();
        });
    });

    $(".evaluate-btn").on('click',function(){
        var url = $(this).data('url');
        $.get(url,function(res){
            $("#evaluateModal").html(res).modal();
        });
    });


    $('#upload').HermesUpload({
        success:function( id, data ) {
            var id = parseInt(id.substr(id.length-1, 1))+1;
            var name = 'attachment'+id;
            $('.img-inputs').append('<input class="hidden" name="'+name+'" type="text" value="'+'files/'+data.uri+'">');
        },
        error:function( err ) {
            console.info( err );
        },
        buttonText : '选择文件',
        chunked:true,
        chunkSize:512 * 1024,
        fileNumLimit:5,
        fileSizeLimit:500000 * 1024,
        fileSingleSizeLimit:50000 * 1024,
    });

    var $attachmentPreview = $('.attachment-preview');

    $.each($attachmentPreview, function(name, value){
        var viewer = new Viewer(document.getElementById(this.id), {
            url: 'data-original'
        });
    });


});