$(document).ready(function () {
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd  hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-right"
    });
    
    $('.transfer').on('click', function(){
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

    $('body').on('click', '#comfirm-transfer', function(){
        var checkeds = new Array();
        var i = 0;
        $("input[name='checkboxId']:checked").each(function (){
            var isCheck = this.value;
            checkeds[i++] = isCheck;
        });
        $('#selectedIds').val(checkeds);

        var $form = $('#transferForm');

        $.post($form.attr('action'), $form.serialize(), function(data){
            $('#modal').html(data).modal('hide');
            if (data.success) {
                $.each(checkeds,function(name,value){
                    $('#'+value).parent().parent().remove();
                });
            }
        });
    });

    $('table tr').find('td:eq(2)').find('a').each(function(i){
        if($(this).text().length>15){
            $(this).attr("title",$(this).text());
            var text=$(this).text().substring(0,15)+"...";
            $(this).text(text);
        } 
    });
});