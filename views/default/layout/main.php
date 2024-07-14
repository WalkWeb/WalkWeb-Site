<?php
use App\Domain\Auth\AuthInterface;

$this->title = 'WalkWeb — Интересное';

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
                <?php
                if (!$this->container->exist('user')) {
                    echo '<li><a href="/login">Вход</a></li>
                          <li><a href="/registration/main">Регистрация</a></li>';
                }
                ?>
                <li><a href="/statistic">Статистика</a></li>
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
                echo '<div class="right_content_ava" style="background-image: url(/img/default_avatar.jpg);"></div>
                      <div class="exp_background"></div>
                      <div class="exp_fill" style="width: ' . $user->getLevel()->getExpBarWeight() . '%"></div>
                      <div class="exp_text">
                      <p><abbr title="Ваш опыт">' . $user->getLevel()->getExpAtLevel() . '/' . $user->getLevel()->getExpToLevel() . '</abbr></p>
                      </div>
                      <div class="right_content_body">
                      <p><span class="lvl">' . $user->getLevel()->getLevel() . '</span> <a href="/u/' . $user->getName() . '" class="profile_link" title="Профиль">' . $user->getName() . '</a></p>
                      </div>
                      <div class="energy_background"></div>
                      <div class="energy_fill" style="width: ' . $user->getEnergy()->getEnergyWeight() . '%;"></div>
                      <div class="energy_text">
                      <p><abbr title="Ваша энергия">' . $user->getEnergy()->getEnergy() . '/' . $user->getEnergy()->getMaxEnergy() . '</abbr></p>
                      </div>
                      <div class="residue_background"></div>
                      <div class="residue_fill" style="width: ' . $user->getEnergy()->getRestoreWeight() . '%;"></div>';


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