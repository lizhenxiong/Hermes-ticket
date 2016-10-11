$(document).ready(function () {
    $('.js-get-ticket').on('click', function(){
        var url = $(this).data('url');
        var id = $(this).data('id');
        $.get(url,function(data){
            if (data.success) {
                $('#'+id).parent().parent().remove();
            }
        })
    });
});