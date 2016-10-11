$(function () {
    $('table tr').find('td:eq(1)').find('a').each(function(i){
        if($(this).text().length>45){
            $(this).attr("title",$(this).text());
            var text=$(this).text().substring(0,45)+"...";
            $(this).text(text);
        }
    }); 
});