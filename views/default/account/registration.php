<?php

$this->title = 'Регистрация';
$postAction = '/registration';

echo '<h1>' . htmlspecialchars($this->title) . '</h1>';

if (!empty($error)) {
    echo "<p>$error</p>";
}

?>

<form method="POST" action="<?= $postAction ?>">
    <label>Логин:
        <input class="form" name="login" autocomplete="off" value="">
    </label>
    <label>Email:
        <input class="form" name="email" autocomplete="off" value="">
    </label>
    <label>Пароль:
        <input class="form" name="password" autocomplete="off" value="" type="password">
    </label>
    <label>
        <input type="radio" name="floor_id" value="1" checked="">
        <span>мужской</span>
    </label>
    <label>
        <input type="radio" name="floor_id" value="2">
        <span>женский</span>
    </label>
    <label>
        <input class="form" type="hidden" name="csrf" value="<?= $csrfToken ?? '' ?>">
    </label>
    <button type="submit">Зарегистрироваться</button>
</form>
