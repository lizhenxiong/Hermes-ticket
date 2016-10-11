$(function () {
    $('.longcontent li').each(function(i){
    	var a = $(this).find('a');
        if(a.text().length>10){
        	a.attr("title",a.text());
            var text=a.text().substring(0,10)+"...";
            a.text(text);
        } 
    }); 
});