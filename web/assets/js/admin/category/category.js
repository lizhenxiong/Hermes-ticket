$(document).ready(function(){   
    $(".js-add-btn").on('click',function(){
        var url = $(this).data('url');
        $.get(url,function(res){
            $("#modal").html(res).modal();
        })
    });

    $(".add-child-btn").on('click', function(){
        var url = $(this).data('url');
        $.get(url, function(data){
            $("#modal").html(data).modal();
        });
    });

    $(".js-update-btn").on('click', function(){
        var url = $(this).data('url');
        $.get(url, function(data){
            $("#modal").html(data).modal();
        });
    });

    $('body').on('click', '#add', function(){
        var $form = $('#createCategoryForm');
        $.post($form.attr('action'), $form.serialize(), function(data){
            $('#modal').html(data).modal('hide');
            window.location.reload();
        });
    });

    $('body').on('click', '#update', function(){
        var $form = $('#updateCategoryForm');
        var id = $(this).attr('data-id');
        $.post($form.attr('action'), $form.serialize(), function(data){
            $('#modal').html(data).modal('hide');
            window.location.reload();
        });
    });

    $('body').on('click', '.delete-category', function(){
        if (!confirm('确定要删除该分类及其子分类吗?')) {
            return;
        };
        var url = $(this).data('url');
        $.post(url, function(data){
            $('#modal').html(data).modal('hide');
            window.location.reload();
        });
    });
});