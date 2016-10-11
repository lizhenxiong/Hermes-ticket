$(document).ready(function() {  

    $(".js-add-btn").on('click',function() {
        var url = $(this).data('url');
        $.get(url,function(res){
            $("#modal").html(res).modal();
        })
    });

    $('body').on('click', '#add', function() {
        var $form = $('#addForm');
        $.post($form.attr('action'), $form.serialize(), function(data) {
            $('#modal').html(data).modal('hide');
            $('.list').prepend($(data));
        });
    });
    
    $(".js-table").on('click', function(event){
        if($(event.target).hasClass('js-update-btn')) {
            var url = $(event.target).data('url');
            $.get(url,function(res){
                $("#modal").html(res).modal();
            })    
        } else if ($(event.target).hasClass('js-delete-btn')) {
            var del_url = $(event.target).data('url');
            var id = $(event.target).data('id');
            $.ajax({
                type : 'GET',
                url : del_url,
                dataType: "json",
                success: function (data) { 
                    $('#'+id).remove();
                }
            })
        }
    });

    $('body').on('click', '#update', function() {
        var $form = $('#updateForm');
        var id = $(event.target).attr('data-id');
        $.post($form.attr('action'), $form.serialize(), function(data) {
            $('#modal').html(data).modal('hide');
            $('#'+id).html(data);
        });
    });

});