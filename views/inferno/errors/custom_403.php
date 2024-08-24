<?php

$message = empty($error) ? 'Ошибка 403: Доступ запрещен' : $error;
$this->title = $message;

?>

<div class="select_text_box">
    <div class="select_text_row">
        <h1><span class="select_text_header"><?= $message ?></span></h1>
        <p class="center">У вас нет прав для доступа к этой странице</p>
        <p class="center"><a href="/" title="">Перейти на главную</a><p>
    </div>
</div>
