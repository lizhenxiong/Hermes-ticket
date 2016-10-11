$(document).ready(function(){   
    $(".js-delete-btn").on('click',function(){
        var status = confirm("确认删除吗？");
        if(!status){
            return false;
        }
        var del_url = $(this).data('url');
        var id = $(this).data('id');
        $.ajax({
            type : 'GET',
            url : del_url,
            dataType: "json",
            success: function (data) { 
                $('#'+id).remove();
            },
            error: function() {
                alert('删除失败!');
            }
        })
    });
});