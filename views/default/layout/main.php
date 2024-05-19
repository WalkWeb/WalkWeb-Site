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

        <div class="post_box">
            <a href="#" class="post_link">
                Заголовок поста #1
            </a>
            <p>
                <span class="post_details">Walk</span>
                <span class="post_details">php, web, html</span>

            </p>
            <p>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem
                ipsum dolor sit amet, consetetur sadipscing elitr.
            </p>

        </div>

        <div class="post_box">
            <a href="#" class="post_link">Очень длинный заголовок поста и еще немного текста #2</a>
            <p>
                <span class="post_details">Admin</span>
                <span class="post_details">jQuery, js, Grunt</span>
                <span class="post_details">3 Comments</span>
            </p>
            <p>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem
                ipsum dolor sit amet, consetetur sadipscing elitr.
            </p>
        </div>

        <div class="post_box">
            <a href="#" class="post_link">Заголовок поста #3</a>
            <p>
                <span class="post_details">Admin</span>
                <span class="post_details">jQuery, js, Grunt</span>
                <span class="post_details">3 Comments</span>
            </p>
            <p>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem
                ipsum dolor sit amet, consetetur sadipscing elitr.
            </p>
        </div>

        <div class="post_box">
            <a href="#" class="post_link">Заголовок поста #4</a>
            <p>
                <span class="post_details">Admin</span>
                <span class="post_details">jQuery, js, Grunt</span>
                <span class="post_details">3 Comments</span>
            </p>
            <p>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem
                ipsum dolor sit amet, consetetur sadipscing elitr.
            </p>
        </div>

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