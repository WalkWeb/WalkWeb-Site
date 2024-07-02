<?php

$message = empty($error) ? 'Ошибка 404: Страница не найдена' : $error;
$this->title = $message;

?>

<h1><?= $message ?></h1>
