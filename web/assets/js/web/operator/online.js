$(function(){
    $('#online').on('click', function(event){
        var on_url = $(event.target).data('url');
        $.post(on_url, {status: 'online'}, function(data){
            var res = '<i class="fa fa-check-circle text-aqua"></i> 在线';
            $('#onoffline').html(res);
        });
    });

    $('#offline').on('click', function(event){
        var on_url = $(event.target).data('url');
        $.post(on_url, {status: 'offline'}, function(data){
            var res = '<i class="fa fa-times-circle text-yellow"></i> 离线';
            $('#onoffline').html(res);
        });
    });
});