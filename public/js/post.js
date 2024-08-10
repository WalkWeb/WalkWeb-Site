let create_post_mode = true;
let create_post_energy = 30;
let video_id = 1;
let row_content = 2;

document.addEventListener("DOMContentLoaded", checkCreatePostEnergy);
document.addEventListener("DOMContentLoaded", addPasteEvent);

/**
 * Проверяет возможность создания поста пользователем, на основе количества его энергии
 */
function checkCreatePostEnergy() {
    let create_post_button = document.getElementById('create_post_button');
    let create_post_button_message = document.getElementById('create_post_button_message');

    if (energy < create_post_energy && create_post_mode === true) {
        create_post_mode = false;
        create_post_button.setAttribute('disabled', 'disabled');
        create_post_button_message.innerHTML = 'Недостаточно энергии для создания поста';
    }
    if (energy >= create_post_energy && create_post_mode === false) {
        create_post_mode = true;
        create_post_button.removeAttribute('disabled');
        create_post_button_message.innerHTML = '';
    }
}

/**
 * Отправляет данные на создание нового поста
 */
function createPost() {
    if (create_post_mode) {
        let elements = document.getElementsByClassName('post_create_context');
        let title = document.getElementById('post_create_title').innerHTML;
        let content = '';

        title = replaceTitle(title);

        if (validateTitle(title)) {
            elements = [...elements];

            elements.forEach(function(element) {
                if (element.className === 'post_create_context post_n_b_t') {
                    if (validateText(element.innerHTML)) {
                        content += textContentReplace(element.innerHTML);
                    }
                }
                if (element.className === 'post_create_context post_create_h2') {
                    if (validateText(element.innerHTML)) {
                        content += h2ContentReplace(element.innerHTML);
                    }
                }
                if (element.className === 'post_create_context') {
                    content += element.innerHTML;
                }
            });

            if (validateFullContent(content)) {
                $.ajax({
                    url: '/post/create',
                    data: {title: title, text: content},
                    type: 'POST',
                    success: function(data) {
                        if (data.success === 1) {
                            window.location.replace(window.location.protocol + '//' + document.domain + '/p/' + data.id);
                        } else {
                            createNotification(data.error);
                        }
                    },
                    error: function() {
                        createNotification('Ошибка! Пожалуйста, обновите страницу!');
                    }
                });
            } else {
                createNotification('Содержимое поста не может быть пустым');
            }
        }
    }
}

/**
 * Убирает перенос строки из заголовка
 *
 * @param string
 * @returns {string | void | *}
 */
function replaceTitle(string) {
    return string
        .replace(/&nbsp;/g, ' ')
        .replace(/<br ?\/?>/, '')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>');
}

/**
 * Проверяет текст на пустоту
 *
 * @param string
 * @returns {boolean}
 */
function validateText(string) {
    return string !== '<p><br></p>' && string !== '<h2><br></h2>';
}

/**
 * Проверяет общий контент поста на пустоту
 *
 * @param string
 * @returns {boolean}
 */
function validateFullContent(string) {
    string = string.replace(/[\[line\]]/g, '');
    return string !== '';
}

/**
 * Заменяет переносы строк
 *
 * @param string
 * @returns {string | void | *}
 */
function pasteContentReplace(string) {
    return string.replace(/\n/g, '<br />');
}

/**
 * Убирает из ссылки на видео лишнее
 *
 * @param string
 * @returns {string | void | *}
 */
function videoContentReplace(string) {
    return string
        .replace(/https:\/\/youtu.be\//g, '')
        .replace(/https:\/\/www.youtube.com\/watch\?v=/g, '');
}

/**
 * Заменяет теги и спецсимволы в тексте
 *
 * @param string
 * @returns {string}
 */
function textContentReplace(string) {
    return '[p]' + string
        .replace(/&nbsp;/g, ' ')
        .replace(/<div>/g, '[br]')
        .replace(/<\/div>/g, '')
        .replace(/&lt;div&gt;/g, '')
        .replace(/&lt;\/div&gt;/g, '')
        .replace(/<br>/g, '[br]')
        .replace(/<p>/g, '[p]')
        .replace(/<\/p>/g, '[/p]')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>') + '[/p]';
}

function h2ContentReplace(string) {
    return '[h2]' + string
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/<div>/g, '[br]')
        .replace(/<\/div>/g, '')
        .replace(/<br>/g, '[br]') + '[/h2]';
}

/**
 * Проверяет корректность заголовка
 *
 * @param string
 * @returns {boolean}
 */
function validateTitle(string) {
    let min_length = 4;
    let max_length = 200;

    if (string.length >= min_length && string.length <= max_length) {
        return true;
    }

    if (string.length < min_length) {
        createNotification('Длина заголовка не может быть короче ' + min_length + ' символов');
    }
    if (string.length > max_length) {
        createNotification('Длина заголовка не может быть больше ' + max_length + ' символов');
    }

    return false;
}

/**
 * Удаление элемента
 *
 * @param id
 */
function rowDelete(id) {
    document.getElementById('post_row_c_' + id).remove();
}

/**
 * Добавляет блок текста в форме создания нового поста
 */
function addText() {
    let content = document.getElementById('post_create_content');
    let newText = document.createElement('div');

    newText.setAttribute('class', 'post_n_r');
    newText.setAttribute('id', 'post_row_c_' + row_content);

    newText.innerHTML = '' +
        '<div class="post_n_b">\n' +
        '    <div class="post_n_b_l"></div>\n' +
        '    <div class="post_create_context post_n_b_t" contenteditable="true" spellcheck="true" aria-multiline="true"><br /></div>\n' +
        '    <div class="post_n_b_r" onclick="rowDelete(' + row_content + ')">&times;</div>\n' +
        '</div>';

    content.appendChild(newText);
    row_content++;
    addPasteEvent();
}

/**
 * Добавляет блок с картинкой в форме создания нового поста
 */
function addImage(url) {
    let content = document.getElementById('post_create_content');
    let image = document.createElement('div');

    image.setAttribute('class', 'post_n_r');
    image.setAttribute('id', 'post_row_c_' + row_content);

    image.innerHTML = '' +
        '<div class="post_n_b">\n' +
        '    <div class="post_n_b_l"></div>\n' +
        '    <div class="post_n_b_img">\n' +
        '        <a href="/' + url + '" rel="gallery" class="pirobox_gall first" target="_blank" title="">\n' +
        '            <img src="/' + url + '" alt="" class="i_img" />\n' +
        '        </a>\n' +
        '    </div>\n' +
        '    <div class="post_create_context" style="display: none;">[img]' + url + '[/img]</div>\n' +
        '    <div class="post_n_b_r" onclick="rowDelete(' + row_content + ')">&times;</div>\n' +
        '</div>';

    content.appendChild(image);
    row_content++;
}

/**
 * Добавляет блок с видео в форме создания нового поста
 */
function addVideo() {
    let content = document.getElementById('post_create_content');
    let newVideo = document.createElement('div');
    let video = prompt('Укажите ссылку на видео c YouTube:');
    let url = videoContentReplace(video);

    newVideo.setAttribute('class', 'post_n_r');
    newVideo.setAttribute('id', 'post_row_c_' + row_content);

    newVideo.innerHTML = '' +
        '<div class="post_n_b">\n' +
        '    <div class="post_n_b_l"></div>\n' +
        '        <div class="post_n_b_t">\n' +
        '            <div class="video_row">\n' +
        '            <div id="p_video_' + video_id + '"><div class="video_bg" style="background: url(//img.youtube.com/vi/' + url + '/maxresdefault.jpg);"></div></div>\n' +
        '            <div id="p_play_' + video_id + '" class="video_play_box" onclick="videoActivate(' + video_id + ', \'' + url + '\')"></div>\n' +
        '            <div class="post_create_context" style="display: none;">[video]' + url + '[/video]</div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '    <div class="post_n_b_r" onclick="rowDelete(' + row_content + ')">&times;</div>\n' +
        '</div>';

    content.appendChild(newVideo);
    video_id++;
    row_content++;
}

/**
 * Добавляет блок с разделительной линией
 */
function addLine() {
    let content = document.getElementById('post_create_content');
    let line = document.createElement('div');

    line.setAttribute('class', 'post_n_r');
    line.setAttribute('id', 'post_row_c_' + row_content);

    line.innerHTML = '' +
        '<div class="post_n_b">\n' +
        '    <div class="post_n_b_l"></div>\n' +
        '    <div class="post_n_b_t">\n' +
        '        <div class="line_box"><div class="line_row"><div class="line_left"></div><div class="line_right"></div><div class="line_center">&nbsp;</div></div></div>\n' +
        '    </div>\n' +
        '    <div class="post_create_context" style="display: none;">[line]</div>\n' +
        '    <div class="post_n_b_r" onclick="rowDelete(' + row_content + ')">&times;</div>\n' +
        '</div>';

    content.appendChild(line);
    video_id++;
    row_content++;
}

/**
 * Добавляет блок с подзаголовком
 */
function addH2() {
    let content = document.getElementById('post_create_content');
    let h2 = document.createElement('div');

    h2.setAttribute('class', 'post_n_r');
    h2.setAttribute('id', 'post_row_c_' + row_content);

    h2.innerHTML = '' +
        '<div class="post_n_b">\n' +
        '    <div class="post_n_b_l"></div>\n' +
        '    <div class="post_n_b_t">' +
        '        <div class="post_create_context post_create_h2" contenteditable="true" spellcheck="true"><br /></div>' +
        '    </div>\n' +
        '    <div class="post_n_b_r" onclick="rowDelete(' + row_content + ')">&times;</div>\n' +
        '</div>';

    content.appendChild(h2);
    video_id++;
    row_content++;
    addPasteEvent();
}

/**
 * Обрабатывает вставку теста в div-контейнеры
 */
function addPasteEvent() {
    [].forEach.call(document.querySelectorAll('div[contenteditable="true"]'), function (el) {
        el.addEventListener('paste', function(e) {
            e.preventDefault();
            let text = e.clipboardData.getData("text/plain");
            text = pasteContentReplace(text);
            document.execCommand("insertHTML", false, text);
        }, false);
    });
}

/** ЗАГРУЗКА КАРТИНОК */

let upload_image;

function handleFiles(file) {
    upload_image = [...file];
    upload_image.forEach(uploadFile);
}

/**
 * Обрабатывает загрузку картинки
 *
 * @param file
 * @param i
 */
function uploadFile(file, i) {
    let url = '/image/upload/json';
    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    let data;
    let upload;
    let upload_width;

    xhr.open('POST', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = jsonParse(this.response);
            if (data.success === 1) {

                // Добавляем изображение на страницу
                addImage(data.dir + data.name);

                // Обновляем поле загруженных файлов
                upload_width = Math.round(data.upload / data.upload_max * 100);
                upload = data.upload / 1048576;
                upload = (Math.round(upload * 10) / 10);
                document.getElementById('pr_upload_width').style.width = upload_width + '%';
                document.getElementById('pr_upload_value').innerHTML = upload;

                // Проверяем, не превышен ли лимит загруженных файлов
                if (data.upload > data.upload_max) {
                    console.log('Превышен лимит загруженных файлов');
                    document.getElementById('pr_upload_width').className = 'pr_upload_limit';
                    document.getElementById('post_add_content_box').innerHTML = '<div onclick="addText()">&#10010; Текст</div><div onclick="addVideo()">&#10010; Видео</div><span>&#10010; Изображение</span><div onclick="addLine()">&#10010; Линия</div>';
                }
            } else {
                createNotification(data.error);
            }
        }
        else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.log('Ошибка загрузки картинки');
        }
    });

    formData.append('file', file);
    xhr.send(formData);
}