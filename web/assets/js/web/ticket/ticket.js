$(function(){
    $(".js-add-btn").on('click',function(){
        var url = $(this).data('url');
        $.get(url,function(res){
            $("#modal").html(res).modal();
        })
    })

    $('body').on('click', '#add', function(){
        var $form = $('#addticketform');
        $.post($form.attr('action'), $form.serialize(), function(data){
            $('#modal').html(data).modal('hide');
            $('.list').append(data);
        });
    });
});