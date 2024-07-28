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
    </div>
    <div id="menu_box">
        <span id="menu_r"></span><span id="menu_l"></span>
        <div id="menu_m">
            <div class="menu_head_box">
                <div class="menu_head_el" id="menu_head_1">
                    <a href="#" title="DUSK WORLD" class="head_text">ПОСТЫ</a>
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
                <div class="row_mc1" style="margin-top: -20px;">
                    <div class="content_main_box">
                        <?= $content ?? '' ?>
                    </div>
                </div>
                <div class="row_mc3"></div>
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
<div id="element_description"></div>
</body>
</html>