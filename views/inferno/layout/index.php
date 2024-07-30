<?php

use App\Domain\Auth\AuthInterface;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $this->title ?></title>
    <meta name="Description" content="<?= $this->description ?>">
    <meta name="Keywords" content="<?= $this->keywords ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" type="text/css" href="/inferno/styles/inferno.css?ver=1.00">
    <link rel="icon" type="image/x-icon" href="/dw-icon-64.png">
</head>
<body id="app">
<div id="main_box">
    <div class="nw_head">
        <div class="go_home_box">
            <a href="/" class="go_home" title="На главную"></a>
        </div>
        <?php
            if ($this->container->exist('user')) {
                /** @var AuthInterface $user */
                $user = $this->container->get('user');
                echo '
            <div class="nw_acc_cont">
                <div class="nw_acc_ava" style="background-image: url(' . $user->getAvatar() . ');">
                    <a href="/profile"></a>
                </div>
                <div class="nw_exp_box">
                    <div id="auth_exp_width" class="nw_exp" style="width: ' . $user->getLevel()->getExpBarWeight() . '%;"></div>
                </div>
                <div class="nw_exp_text_box">
                    <span id="auth_exp_at_lvl">' . $user->getLevel()->getExpAtLevel() . '</span> / <span id="auth_exp_to_lvl">' . $user->getLevel()->getExpToLevel() . '</span>
                </div>
                <div class="nw_acc_e">
                    <div class="nw_acc_ea" id="energy_bar_div" style="width: ' . $user->getEnergy()->getEnergyWeight() . '%;"></div>
                </div>
                <div class="nw_acc_et">
                    <span id="energy">' . $user->getEnergy()->getEnergy() . '</span> / <span id="energy_max">' . $user->getEnergy()->getMaxEnergy() . '</span>
                </div>
                <div class="nw_acc_t">
                    <div class="nw_acc_tt" id="second_bar_div" style="width: ' . $user->getEnergy()->getRestoreWeight() . '%;"></div>
                </div>
            </div>';
            } else {
                echo '<div class="nw_guest_cont"><a href="/login" title="">Авторизация</a> / <a href="/registration/main" title="">Регистрация</a></div>';
            }
        ?>
    </div>
    <div id="menu_box">
        <span id="menu_r"></span><span id="menu_l"></span>
        <div id="menu_m">
            <div class="menu_head_box">
                <div class="menu_head_el" id="menu_head_1">
                    <a href="/" title="DUSK WORLD" class="head_text">ПОСТЫ</a>
                </div>
                <div class="menu_head_el" id="menu_head_2">
                    <a href="#" title="ДЕМО ПОДЗЕМЕЛЬЯ" class="head_text">ДЕМО ПОДЗЕМЕЛЬЯ</a>
                </div>
                <div class="menu_head_el" id="menu_head_3">
                    <a href="#" title="РЕЙТИНГИ" class="head_text">РЕЙТИНГИ</a>
                </div>
                <div class="menu_head_el" id="menu_head_4">
                    <a href="#" title="ОТЗЫВЫ" class="head_text">ОТЗЫВЫ</a>
                </div>
                <div class="menu_head_el" id="menu_head_5">
                    <a href="#" title="ДНЕВНИКИ РАЗРАБОТКИ" class="head_text">ДНЕВНИКИ РАЗРАБОТКИ</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row_mc">
        <div class="row_mc0">
            <div class="news_top_line"></div>
            <div class="mr_box">
                <div>
                    <div class="mr_tl"></div>
                    <div class="mr_tr"></div>
                    <div class="mr_tc"></div>
                </div>
                <div class="mr_content">
                    <div class="mr_el"><p>» <a href="#">FAQ</a></p></div>
                    <div class="mr_el"><p>» <a href="#">Аккаунты</a></p></div>
                    <div class="mr_el"><p>» <a href="#">База Знаний</a></p></div>
                    <div class="mr_el"><p>» <a href="#">Статистика</a></p></div>
                </div>
                <div>
                    <div class="mr_bl"></div>
                    <div class="mr_br"></div>
                    <div class="mr_bc"></div>
                </div>
            </div>
            <div class="content_center">
                <?= $content ?? '' ?>
            </div>
            <div class="main_both"></div>
            <div class="demon_footer"></div>
        </div>
    </div>
    <div id="footer_box">
        <span id="footer_r"></span>
        <span id="footer_l"></span>
        <div id="footer_m"></div>
    </div>
    <div id="new_back_to_top">
        <a rel="nofollow" href="#app" title="Наверх"></a>
    </div>
</div>
<?php
if ($this->container->exist('user')) {
    /** @var AuthInterface $user */
    $user = $this->container->getUser();

    if ($count = count($user->getNotices())) {

        echo '<div class="up_notice_box">
                <div id="up_open_notice" onclick="openNotice()">
                    <p><span>' . $count . '</span></p>
                </div>
                <div id="up_notice_content">';

        foreach ($user->getNotices() as $notice) {
            echo    '<div class="up_notice_row" id="notice_' . $notice->getId() . '">
                        <div class="up_notice_row_l">
                            <p>' . $notice->getMessage() . '</p>
                        </div>
                        <div class="up_notice_row_r">
                            <span onclick="closeNotice(\'' . $notice->getId() . '\')">×</span>
                        </div>
                    </div>';
        }

        if ($count > 2) {
            echo '<div class="up_notice_car">
                    <p><span onclick="closeAllNotice()">закрыть все</span></p>
                  </div>';
        }

        echo '</div></div>';
    }

    echo
        '<script>
            let interval = 1000;
            let expected = Date.now() + interval;
            let energy = ' . $user->getEnergy()->getEnergy() . ';
            let energy_max = ' . $user->getEnergy()->getMaxEnergy() . ';
            let second = ' . $user->getEnergy()->getResidue() . ';
            let second_max = ' . ENERGY_RESTORE . ';
            let energy_bar;
            let second_bar;
        </script>
        <script src="/js/energy.js?v=1.0"></script>';
}
?>
</body>
</html>