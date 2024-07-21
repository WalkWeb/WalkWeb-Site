
function openNotice() {
    document.getElementById('up_open_notice').style.display = 'none';
    document.getElementById('up_notice_content').style.display = 'block';
}

function closeNotice(id) {
    let notice = document.getElementById('notice_' + id);
    notice.remove();

    // Отправляем запрос на удаление уведомления
    $.ajax({
        url: '/notice/close/' + id,
        type: 'POST',
        success: function(data) {
            if (data.success === 1) {
                console.log('closeNotice: Ok');
            } else {
                // TODO
                //createNotification(data.error);
            }
        },
        error: function() {
            alert('Ошибка! Пожалуйста, обновите страницу!');
        }
    });
}

function closeAllNotice() {
    document.getElementById('up_notice_content').style.display = 'none';

    $.ajax({
        url: '/notice/all/close',
        type: 'POST',
        success: function(data) {
            if (data.success === 1) {
                console.log('closeNotice: Ok');
            } else {
                // TODO
                //createNotification(data.error);
            }
        },
        error: function() {
            alert('Ошибка! Пожалуйста, обновите страницу!');
        }
    });
}

function reducedEnergy() {
    $.ajax({
        url: '/reduced/energy',
        type: 'POST',
        success: function(data) {
            if (data.success === true) {
                console.log('reducedEnergy: Ok');
                editEnergy(-40)
            } else {
                console.log('reducedEnergy: ' + data.error);
                createNotification(data.error);
            }
        },
        error: function() {
            alert('Ошибка! Пожалуйста, обновите страницу!');
        }
    });
}

function editEnergy(value) {
    energy += value;
    document.getElementById('energy').innerHTML = energy;
    energy_bar = Math.round((energy/energy_max) * 100);
    document.getElementById('energy_bar_div').style.width = energy_bar + '%';
    timer();
}

function createNotification(message) {
    let notification = document.createElement('div');
    let body = document.getElementById('app');

    notification.setAttribute('id', 'notification_bg');
    notification.setAttribute('class', 'notification_bg');
    notification.setAttribute('onclick', 'closeNotification()');

    notification.innerHTML =
        '<div class="notification_box" onclick="event.stopPropagation()">' +
        '<p>' + message + '</p>' +
        '<p align="center"><span class="notification_close" onclick="closeNotification()">&times;</span></p>' +
        '</div>';

    body.appendChild(notification);
}

function closeNotification() {
    let notification = document.getElementById('notification_bg');
    notification.remove();
}
