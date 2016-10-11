$(function(){
    var assignedUrl = $('#workspace-assigned-ticket').data('url');
    var delayedUrl = $('#workspace-delayed-ticket').data('url');
    var unAssignedUrl = $('#workspace-unassigned-ticket').data('url');

    $.post( assignedUrl, function(data){
        $('#workspace-assigned-ticket').html(data);
    });

    $.post( unAssignedUrl, function(data){
        $('#workspace-unassigned-ticket').html(data);
    });

    $.post( delayedUrl, function(data){
        $('#workspace-delayed-ticket').html(data);
    });

    var renderAssignedTicket = function() {
        $.post( assignedUrl, function(data){
            $('#workspace-assigned-ticket').html(data);
        });
    }

    var renderUnAssignedTicket = function() {
        $.post( unAssignedUrl, function(data){
            $('#workspace-unassigned-ticket').html(data);
        });
    }

    var renderDelayedTicket = function() {
        $.post( delayedUrl, function(data){
            $('#workspace-delayed-ticket').html(data);
        });
    }

    setInterval(function(){
        var clock = $('#clock').html();
        clock = parseInt(clock)+1;
        if (clock == 10) {
            clock = 0;
        }
        $('#clock').html(clock);
    }, 1000);

    setInterval(renderAssignedTicket, 60000);
    setInterval(renderUnAssignedTicket, 60000);
    setInterval(renderDelayedTicket, 60000);

    $('body').on('click', '.js-get-ticket', function(){
        var url = $(this).data('url');
        var id = $(this).data('id');
        $.get(url,function(data){
            if (data.success) {
                $('#'+id).parent().parent().remove();
            }
            $.post( assignedUrl, function(data){
                $('#workspace-assigned-ticket').html(data);
            });
        })
    });

});