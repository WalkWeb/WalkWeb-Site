<?php

$this->title = APP_NAME . ' — Регистрация';
$action = '/registration/' . ($ref ?? 'undefined');

?>

<link rel="stylesheet" type="text/css" href="/inferno/styles/registration.css?ver=1.0">

<div class="ch_test_box">
    <div class="ch_test_row">
        <p class="center"><span class="ch_test_h">Ранняя стадия разработки</span></p>

        <p>
            Проект находится в ранней стадии разработки. Большая часть функционала еще не реализованна. Также,
            периодически, в процессе тестирования будут проводиться «вайпы» (полное удаление всех аккаунтов, персонажей,
            постов, комментариев и прочего)
        </p>
    </div>
</div>

<br /><br />
<?= !empty($message) ? '<p class="center"><span class="red">' . $message . '</span></p><br />' : '' ?>

<form method="POST" action="<?= $action ?>" class="reg_form" onsubmit="reg_send();">

    <div class="reg_m">
        <div class="reg_m_l">
            <div class="reg_b" align="right"><p>Имя пользователя:</p></div>
            <div class="reg_b" align="right"><p>Пароль:</p></div>
            <div class="reg_b" align="right"><p>Повторите пароль:</p></div>
            <div class="reg_b" align="right"><p>Почта:</p></div>
            <div class="reg_b" align="right"><p>Пол:</p></div>
        </div>
        <div class="reg_m_r">
            <div class="reg_b"><p id="login_correct">от 5 до 15 символов</p></div>
            <div class="reg_b"><p id="pass_correct">от 4 до 20 символов</p></div>
            <div class="reg_b"><p id="repass_correct"></p></div>
            <div class="reg_b"><p id="mail_correct"></p></div>
            <div class="reg_b"></div>
        </div>
        <div class="reg_m_c">
            <div class="reg_b_c">
                <label><input name="login" id="login_id" autocomplete="off" onkeypress="cLogin()" onfocus="cLogin()" onkeyup="cLogin()"></label>
            </div>
            <div class="reg_b_c">
                <label><input name="password" id="pass_id" type="password" onkeypress="cPass()" onfocus="cPass()" onkeyup="cPass()"></label>
            </div>
            <div class="reg_b_c">
                <label><input name="password" id="repass_id" type="password" onkeypress="rePass()" onfocus="rePass()" onkeyup="rePass()"></label>
            </div>
            <div class="reg_b_c">
                <label><input name="email" id="email_id" autocomplete="off" onkeypress="cMail()" onfocus="cMail()" onkeyup="cMail()"></label>
            </div>
            <div class="reg_b_floor">
                <label class="rfloor">
                    <input type="radio" name="floor_id" value="1" onclick="floorChange(this.value)" checked>
                    <span class="labelfloor">мужской</span>
                </label>
                <label class="rfloor">
                    <input type="radio" name="floor_id" value="2" onclick="floorChange(this.value)">
                    <span class="labelfloor">женский</span>
                </label>
            </div>
        </div>
    </div>

    <?php

    require_once DIR . '/views/inferno/character/_create.php';

    ?>

    <input type="hidden" name="ref" value="<?= ($ref ?? 'undefined') ?>">

    <div id="reg_fields"></div>

    <div class="regcenter">
        <img src="/img/loading.gif" alt="" id="reg_img_loading" />
        <button class="input_submit submit_big" id="submit_id" disabled>Зарегистрироваться</button>
    </div>

</form>
