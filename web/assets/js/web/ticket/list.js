$(function () {
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd  hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-right"
    });

    $('table tr').find('td:eq(1)').find('a').each(function(i){
        if($(this).text().length>15){
            $(this).attr("title",$(this).text());
            var text=$(this).text().substring(0,15)+"...";
            $(this).text(text);
        } 
    }); 
});
