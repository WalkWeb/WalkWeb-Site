<?php
use App\Domain\Auth\AuthInterface;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= htmlspecialchars($this->title) ?></title>
    <meta name="Description" content="<?= htmlspecialchars($this->description) ?>">
    <meta name="Keywords" content="<?= htmlspecialchars($this->keywords) ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" type="text/css" href="/css/default.css">
</head>
<body>
<div class="line"></div>
<div class="box head_image">
    <a href="/" class="to_main"></a>
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
                <li><a href="#">Добавить пост</a></li>
                <li><a href="/login">Вход</a></li>
                <li><a href="/registration/main">Регистрация</a></li>
                <li><a href="#">Статистика</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="box content_box flex">
    <div class="content">
        <?= $content ?? 'Отсутствует контент для отображения' ?>
    </div>
    <div class="right_box">
        <div class="right_content">
            <div class="right_content_head">
                Персона
            </div>
            <?php
            if ($this->container->exist('user')) {
                /** @var AuthInterface $user */
                $user = $this->container->get('user');
                echo '<div class="right_content_ava"></div><div class="right_content_body">';
                echo "<p>({$user->getLevel()}) {$user->getName()}</p></div>";
            } else {
                echo '<div class="right_content_body">
                        <p>
                            <a href="/login" title="">Вход</a> / <a href="/registration/main" title="">Регистрация</a>
                        </p>
                      </div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="box line"></div>
<div class="box footer_image"></div>
<div class="footer_box">
    <div class="box footer_content">
        <p>Footer content</p>
    </div>
</div>
</body>
</html>