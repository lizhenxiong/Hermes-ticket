$(function() {
    var data_url = $('.notification-count').attr('data-url');
    $.ajax({
        type: 'GET',
        url: data_url,
        dataType: "json",
        success: function(data) {
        $('.notification-count').html(data.count);
        }
    });

    $('#notification-list').on('click', function() {
        var data_url = $('#notification-list').attr('data-url');
        $.ajax({
            type: 'GET',
            url: data_url,
            dataType: "json",
            success: function(data) {
                $('#notification-part-list').empty();                
                for (var i = 0; i < data.notifications.length; i++) {
                    var li = 
                    '<li class="message">'+
                        '<a title="'+data.notifications[i].message+'" href="'+data.notification[i].url+'">'+
                            '<i class="fa fa-users text-aqua" ></i>'+
                            data.notifications[i].message+
                        '</a>'+
                    '</li>';

                    $('#notification-part-list').append(li);
                }    
            }
        });
    });

});

var int = self.setInterval('clock()',60000);
    function clock() {
        var t = new Date();
        var data_url = $('.notification-count').attr('data-url');
        $.ajax({
            type: 'GET',
            url: data_url,
            dataType: "json",
            success: function(data) {
                $('.notification-count').html(data.count)
            }
        });
        $('.notification-count').value = t;
    }


