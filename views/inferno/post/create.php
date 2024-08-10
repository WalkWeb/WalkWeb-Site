<?php

use App\Domain\Auth\AuthInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Создание нового поста';

if (empty($user) || !($user instanceof AuthInterface)) {
    throw new AppException('view.post.create: miss or invalid $user');
}

?>

<div class="select_text_box">
    <div class="select_text_row">
        <p class="center"><span class="select_text_header">Ранняя стадия разработки</span></p>

        <p>
            Проект находится в ранней стадии разработки. Большая часть функционала еще не реализованна. Также,
            периодически, в процессе тестирования будут проводиться «вайпы» (полное удаление всех аккаунтов, персонажей,
            постов, комментариев и прочего)
        </p>
    </div>
</div>

<br /><br />
<?= !empty($error) ? '<p class="center"><span class="red">' . $error . '</span></p><br />' : '' ?>

<div class="post_create_box">
    <div id="post_create_title" contenteditable="true" spellcheck="true"><br /></div>

    <div id="post_create_content">

        <div class="post_n_r" id="post_row_c_1">
            <div class="post_n_b">
                <div class="post_n_b_l"></div>
                <div class="post_create_context post_n_b_t" contenteditable="true" spellcheck="true" aria-multiline="true"><br /></div>
                <div class="post_n_b_r" onclick="rowDelete(1)">&times;</div>
            </div>
        </div>

    </div>
</div>

<div id="post_add_content_box">
    <div onclick="addText()">&#10010; Текст</div>
    <div onclick="addVideo()">&#10010; Видео</div>
    <?= ($user->getUpload()->getUpload() > $user->getUpload()->getUploadMax() ? '<span>&#10010; Изображение</span>' : '<label for="fileElem">&#10010; Изображение</label>') ?>
    <div onclick="addLine()">&#10010; Линия</div>
    <div onclick="addH2()">&#10010; Подзаголовок</div>
</div>

<p align="center">Места на диске:</p>
<div class="post_upload_box">
    <div id="pr_upload_width"
         class="<?= ($user->getUpload()->getUpload() > $user->getUpload()->getUploadMax() ? 'pr_upload_limit' : 'pr_upload') ?>"
         style="width: <?= (round($user->getUpload()->getUpload() / $user->getUpload()->getUploadMax() * 100)) ?>%;"
    ></div>
    <div class="pr_upload_text">
        <span id="pr_upload_value"><?= round($user->getUpload()->getUpload()/ 1048576, 1) ?></span> /
        <?= round($user->getUpload()->getUploadMax() / 1048576, 1) ?> Мбайт
    </div>
</div>

<input type="file" id="fileElem" accept="image/*" onchange="handleFiles(this.files)" class="pr_upload_input">

<div class="post_button_create">
    <span onclick="createPost()" class="input_submit submit_big" id="create_post_button">Создать пост</span>
</div>

<p id="create_post_button_message"></p>

<script src="/js/post.js?v=1.46"></script>
