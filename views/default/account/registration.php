<?php

$this->title = APP_NAME . ' — Регистрация';
$action = '/registration/' . ($ref ?? 'undefined');

?>

<link rel="stylesheet" type="text/css" href="/lightweb/css/registration.css?ver=1.0">

<br /><br />
<?= !empty($error) ? '<p class="center"><span class="red">' . $error . '</span></p><br />' : '' ?>

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
            <div class="reg_h"><p id="login_correct">от 5 до 15 символов</p></div>
            <div class="reg_h"><p id="pass_correct">от 4 до 20 символов</p></div>
            <div class="reg_h"><p id="repass_correct"></p></div>
            <div class="reg_h"><p id="mail_correct"></p></div>
            <div class="reg_h"></div>
        </div>
        <div class="reg_m_c">
            <div class="reg_b_c">
                <label><input class="form" name="login" id="login_id" autocomplete="off" onkeypress="cLogin()" onfocus="cLogin()" onkeyup="cLogin()" value="<?= empty($login) ? '' : $login ?>"></label>
            </div>
            <div class="reg_b_c">
                <label><input class="form" name="password" id="pass_id" type="password" onkeypress="cPass()" onfocus="cPass()" onkeyup="cPass()"></label>
            </div>
            <div class="reg_b_c">
                <label><input class="form" name="password" id="repass_id" type="password" onkeypress="rePass()" onfocus="rePass()" onkeyup="rePass()"></label>
            </div>
            <div class="reg_b_c">
                <label><input class="form" name="email" id="email_id" autocomplete="off" onkeypress="cMail()" onfocus="cMail()" onkeyup="cMail()" value="<?= empty($email) ? '' : $email ?>"></label>
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

    <div class="r_races_cont">
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="1" class="dnone" onclick="raceChange(this.value)" checked>
                <span>Аналитик</span>
            </label>
        </div>
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="2" class="dnone" onclick="raceChange(this.value)">
                <span>Дизайнер</span>
            </label>
        </div>
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="3" class="dnone" onclick="raceChange(this.value)">
                <span>Devops</span>
            </label>
        </div>
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="4" class="dnone" onclick="raceChange(this.value)">
                <span>Стажер</span>
            </label>
        </div>
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="5" class="dnone" onclick="raceChange(this.value)">
                <span>Программист</span>
            </label>
        </div>
        <div class="rcbox r_16" align="center">
            <label>
                <input type="radio" name="genesis_id" value="6" class="dnone" onclick="raceChange(this.value)">
                <span>Менеджер</span>
            </label>
        </div>
    </div>

    <div class="r_n_c_c">
        <div class="r_n_l" id="class_list">
            <div class="r_class">
                <label>
                    <input type="radio" name="profession_id" value="1" onclick="classChange(this.value)" checked>
                    <span>Default-1</span>
                </label>
            </div>
        </div>
    </div>

    <div class="r_ava_c" id="avatars_list"></div><div style="width: 100%; clear: both;"></div>

    <input type="hidden" name="ref" value="<?= ($ref ?? 'undefined') ?>">
    <input type="hidden" name="csrf" value="<?= $csrfToken ?? '' ?>">

    <div id="reg_fields"></div>

    <div class="regcenter">
        <img src="/img/loading.gif" alt="" id="reg_img_loading" />
        <button class="input_submit submit_big" id="submit_id" disabled>Зарегистрироваться</button>
    </div>

</form>

<script src="/lightweb/js/reg.js?v=1.0"></script>
