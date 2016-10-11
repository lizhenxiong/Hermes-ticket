$(function(){
    $('.change-priority').on('click', function(){
        var url = $(this).data('url');
        $.get(url, function(res){
            $('#modal').html(res).modal();
        });
    });

    $('body').on('click', '#change-priority-btn',function(event){
        var url = $(this).data('url');
        var id = $(event.target).attr('data-id');
        var form = $('#change-priority-form');
        $.post(url, form.serialize(), function(data){
            console.log(data);
            $('#modal').html(data).modal('hide');
            $('#'+id).html(data);
        });
    });

    $('.distribute-tickets').on('click', function(){
        if ($("input[name='checkboxId']:checked").val()) {
            var url = $(this).data('url');
            $.get(url, function(res){
                $('#modal').html(res).modal();
            });
        } else {
            alert("请最少选择一项！");
            return false ;
        }
    });

    $('body').on('click', '#comfirm-distribute', function(){
        var checkeds = new Array();
        var i = 0;
        $("input[name='checkboxId']:checked").each(function (){
            var isCheck = this.value;
            checkeds[i++] = isCheck;
        });
        $('#selectedIds').val(checkeds);

        var $form = $('#distribute-tickets-form');

        $.post($form.attr('action'), $form.serialize(), function(data){
            $('#modal').html(data).modal('hide');
            if (data.success) {
                window.location.reload();
            }
        });
    });

    $('.form_datetime').datetimepicker({
        format: 'yyyy-mm-dd  hh:ii',
        autoclose: true,
        todayBtn: true,
        pickerPosition: 'bottom-right'
    });

    $('table tr').find('td:eq(2)').each(function(i){
        if($(this).text().length>15){
            $(this).attr("title",$(this).text());
            var text=$(this).text().substring(0,15)+"...";
            $(this).text(text);
        } 
    }); 
})