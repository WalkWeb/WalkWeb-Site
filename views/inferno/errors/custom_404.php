<?php

$message = empty($error) ? 'Ошибка 404: Страница не найдена' : $error;
$this->title = $message;

?>

<div class="select_text_box">
    <div class="select_text_row">
        <h1><span class="select_text_header"><?= $message ?></span></h1>
        <p class="center">Возможно страница удалена, а возможно её никогда и не существовало</p>
        <p class="center"><a href="/" title="">Перейти на главную</a><p>
    </div>
</div>
