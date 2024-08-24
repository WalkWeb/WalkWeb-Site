<?php

$message = empty($error) ? 'Ошибка 403:  Доступ запрещен' : $error;
$this->title = $message;

?>

<h1><?= $message ?></h1>

<p>У вас нет прав для доступа к этой странице</p>

<p><a href="/" title="">Перейти на главную</a></p>
