<?php

$title = $this->title ?: APP_NAME . ' — Интересное';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= htmlspecialchars($this->title) ?></title>
    <meta name="Description" content="<?= htmlspecialchars($this->description) ?>">
    <meta name="Keywords" content="<?= htmlspecialchars($this->keywords) ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" type="text/css" href="/lightweb/css/lw.css">
    <script src="/js/jquery.js"></script>
    <script src="/js/app.js?v=1.00"></script>
    <link rel="icon" type="image/x-icon" href="/icon64.png">
</head>
<body id="app">
<div class="line"></div>
<div class="box head_image">
    <a href="/" class="full"></a>
</div>
<div class="box line"></div>

<div class="box flex">
    <div class="head_box">
        <div class="head_menu float_left">
            <ul>
                <li><a href="/">Интересное</a></li>
                <li><a href="#">Все подряд</a></li>
            </ul>
        </div>
        <div class="head_menu float_right">
            <ul>
                <li><a href="/post/create">Добавить пост</a></li>
                <?php
                if (!$this->container->exist('user')) {
                    echo '<li><a href="/login">Вход</a></li>
                          <li><a href="/registration/main">Регистрация</a></li>';
                }
                ?>
                <li><a href="#">Рейтинги</a></li>
                <li><a href="/statistic">Статистика</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="box content_box flex">