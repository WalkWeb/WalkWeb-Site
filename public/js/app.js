
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
