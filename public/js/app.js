
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

/**
 * Обрабатывает запрос на лайк посту
 *
 * @param slug
 * @param rating
 */
function likePost(slug, rating) {
    $.ajax({
        url: '/post/like/' + slug,
        type: 'POST',
        success: function(data) {
            if (data.success === true) {
                rating++;
                updatePostRating(slug, rating);
            } else {
                createNotification(data.error);
            }
        },
        error: function() {
            alert('Ошибка! Пожалуйста, обновите страницу!');
        }
    });
}

/**
 * Обрабатывает запрос на дизлайк посту
 *
 * @param slug
 * @param rating
 */
function dislikePost(slug, rating) {
    $.ajax({
        url: '/post/dislike/' + slug,
        type: 'POST',
        success: function(data) {
            if (data.success === true) {
                rating--;
                updatePostRating(slug, rating);
            } else {
                createNotification(data.error);
            }
        },
        error: function() {
            alert('Ошибка! Пожалуйста, обновите страницу!');
        }
    });
}

/**
 * Обновляет отображение рейтинга поста
 *
 * @param slug
 * @param rating
 */
function updatePostRating(slug, rating) {
    let rating_box = document.getElementById('post_rating_box_' + slug);

    if (rating > 0) {
        rating_box.innerHTML = '<div id="post_rating_value"><span class="positiveRatingColor">' + rating + '</span></div>';
    }
    if (rating < 0) {
        rating_box.innerHTML = '<div id="post_rating_value"><span class="negativeRatingColor">' + rating + '</span></div>';
    }
    if (rating === 0) {
        rating_box.innerHTML = '<div id="post_rating_value"><span class="defaultRatingColor">' + rating + '</span></div>';
    }
}

function jsonParse(data) {
    return JSON.parse(data.replace(/\n/g, "\\n"));
}
