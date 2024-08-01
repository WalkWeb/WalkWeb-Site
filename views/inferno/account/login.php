<?php

use App\Handler\Account\AccountLoginPageHandler;

$this->title = APP_NAME . ' — Вход';
$postAction = '/login';

echo "<h1>{$this->title}</h1>";

if (!empty($error)) {
    if ($error === AccountLoginPageHandler::ALREADY_AUTH) {
        echo "<p><span class='red'>$error</span></p>";
    } else {
        echo "<p><span class='red'>$error</span></p>";

        echo '<div class="login_container">
              <form method="POST" action="' .$postAction . '">
                <label>
                    <span class="login_label">Логин:</span>
                    <input class="login_input" name="login" autocomplete="off" value="">
                </label>
                <label>
                    <span class="login_label">Пароль:</span>
                    <input class="login_input" name="password" autocomplete="off" value="" type="password">
                </label>
                <label>
                    <input type="hidden" name="redirect_url" value="' . ($redirectUrl ?? '') . '">
                </label>
                <label>
                    <input type="hidden" name="csrf" value="' . ($csrfToken ?? '') . '">
                </label>

                <button class="input_submit login_submit">Войти</button>
            </form>
            </div>';
    }
} else {
    echo '<div class="login_container">
          <form method="POST" action="' .$postAction . '">
            <label>
                <span class="login_label">Логин:</span>
                <input class="login_input" name="login" autocomplete="off" value="">
            </label>
            <label>
                <span class="login_label">Пароль:</span>
                <input class="login_input" name="password" autocomplete="off" value="" type="password">
            </label>
            <label>
                <input type="hidden" name="redirect_url" value="' . ($redirectUrl ?? '') . '">
            </label>
            <label>
                <input type="hidden" name="csrf" value="' . ($csrfToken ?? '') . '">
            </label>
        
            <button class="input_submit login_submit">Войти</button>
        </form>
        </div>';
}
