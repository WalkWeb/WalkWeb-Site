<?php

$message = empty($error) ? 'Ошибка 404: Страница не найдена' : $error;
$this->title = $message;

?>

<div class="select_text_box">
    <div class="select_text_row">
        <h1><span class="select_text_header">Вы пытаетесь создать пост для несуществующего сообщества</span></h1>
        <p class="center">Возможно сообщество, для которого вы хотите создать пост удалено</p>
        <p class="center"><a href="/post/create/default" title="">Создать пост без привязки к сообществу</a><p>
    </div>
</div>
