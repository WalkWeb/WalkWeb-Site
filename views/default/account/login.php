<?php

use App\Handler\Account\AccountLoginPageHandler;

$this->title = 'Вход';
$postAction = '/login';

echo "<h1>{$this->title}</h1>";

if (!empty($error)) {
    if ($error === AccountLoginPageHandler::ALREADY_AUTH) {
        echo "<p><span class='red'>$error</span></p>";
    } else {
        echo "<p><span class='red'>$error</span></p>";

        echo '<form method="POST" action="' .$postAction . '">
                <label>Логин:
                    <input class="form" name="login" autocomplete="off" value="">
                </label>
                <label>Пароль:
                    <input class="form" name="password" autocomplete="off" value="" type="password">
                </label>
                <label>
                    <input type="hidden" name="redirect_url" value="' . ($redirectUrl ?? '') . '">
                </label>
                <label>
                    <input type="hidden" name="csrf" value="' . ($csrfToken ?? '') . '">
                </label>

                <button>Войти</button>
            </form>';
    }
} else {
    echo '<form method="POST" action="' .$postAction . '">
            <label>Логин:
                <input class="form" name="login" autocomplete="off" value="">
            </label>
            <label>Пароль:
                <input class="form" name="password" autocomplete="off" value="" type="password">
            </label>
            <label>
                <input type="hidden" name="redirect_url" value="' . ($redirectUrl ?? '') . '">
            </label>
            <label>
                <input type="hidden" name="csrf" value="' . ($csrfToken ?? '') . '">
            </label>
        
            <button>Войти</button>
        </form>';
}
