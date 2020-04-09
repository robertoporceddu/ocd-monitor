/**
 * Created by MatteoMeloni on 13/01/17.
 */
function showNotification(url,frequency) {
    var ajax_opt = {
        url: url,
        async: true,
        type: 'GET',
        cache: false,
        dataType: 'json',
    };

    $.ajax(
        ajax_opt
    ).success(function(data) {
        var i;
        var notifications = data.notifications;
        var chrome_notifications = data.chrome_notifications;

        $('#notifications #list').html('');

        for(i in notifications){
            $('#notifications #list').append(
            '<li id="notification-'+ notifications[i].id +'" class="">' +
                '<a href="#">' +
                    '<div class="pull-left">' +
                        '<img src="' + notifications[i].from_img + '" class="img img-responsive img-circle" alt="User Image" style="height: 100%;"/>' +
                    '</div>' +

                    '<h4>' +
                        notifications[i].from + '<small class="text-right"><i class="fa fa-clock-o"></i> ' + notifications[i].created + '</small>' +
                    '</h4>' +
                    '<p class="' + ((notifications[i].type == 'info') ? 'text-light-blue' : 'text-red') + '">' + notifications[i].message + '</p>' +
                    '<p>' +
                        '<button class="btn btn-link pull-right" onclick="setViewedAt(' + notifications[i].id + ')">Chiudi</button>' +
                    '</p>' +
                '</a>' +
            '</li>'
            );
        }

        if(notifications.length){
            document.title = '(' + notifications.length +') Octopus CM';
            $('#notifications .counter').html(notifications.length).removeClass('hidden');
        }

        if(chrome_notifications){
            notify('Prezzogiusto Backend','Hai ' + chrome_notifications + ' nuove notifiche!', '#');
        }
    });

    setTimeout(function(){
        showNotification(url,frequency);
    },frequency);
}

function setViewedAt(id) {
    event.stopPropagation();

    var ajax_opt = {
        url: '/notifications/' + id + '/viewed',
        async: true,
        type: 'post',
        cache: false,
        dataType: 'json',
    };

    $.ajax(
        ajax_opt
    );

    switch (id){
        case 'all' : $('#notifications #list').empty(); break;
        default: $('#notification-'+id).remove(); break;

    }

    var notifications = $('#notifications #list li').length;

    if(notifications > 1){
        document.title = '(' + notifications +') Backend | Prezzoiusto';
    } else {
        document.title = 'Backend | Prezzoiusto';
    }

    $('#notifications .counter').html(notifications);
}

function notify(title, message, onclick) {
    if (Notification.permission !== "granted")
        Notification.requestPermission();
    else {
        var notification = new Notification(title, {
            icon: '/media/ico_fox.png',
            body: message,
        });

        notification.onclick = function () {
            window.open(onclick);
        };

    }
}