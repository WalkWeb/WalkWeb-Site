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
<div class="box head_image"></div>
<div class="box line"></div>

<div class="box flex">
    <div class="head_box">
        <div class="head_menu float_left">
            <ul>
                <li><a href="#">Интересное</a></li>
                <li><a href="#">Все подряд</a></li>
            </ul>
        </div>
        <div class="head_menu float_right">
            <ul>
                <li><a href="#">Добавить пост</a></li>
                <li><a href="#">Вход</a></li>
                <li><a href="#">Регистрация</a></li>
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
                Заголовок
            </div>
            Какой-то контент
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