<?php

$this->title = 'Регистрация';
$postAction = '/registration';

echo '<h1>' . htmlspecialchars($this->title) . '</h1>';

if (!empty($error)) {
    echo "<p>$error</p>";
}

if (empty($floor)) {
    $floorMale = 'checked=""';
    $floorFemale = '';
} else {
    $floorMale = $floor === 1 ? 'checked=""' : '';
    $floorFemale = $floor === 1 ? '' : 'checked=""';
}

?>

<form method="POST" action="<?= $postAction ?>">
    <label>Логин:
        <input class="form" name="login" autocomplete="off" value="<?= empty($login) ? '' : $login ?>">
    </label>
    <label>Email:
        <input class="form" name="email" autocomplete="off" value="<?= empty($email) ? '' : $email ?>">
    </label>
    <label>Пароль:
        <input class="form" name="password" autocomplete="off" value="" type="password">
    </label>
    <label>
        <input type="radio" name="floor_id" value="1" <?= $floorMale ?>>
        <span>мужской</span>
    </label>
    <label>
        <input type="radio" name="floor_id" value="2" <?= $floorFemale ?>>
        <span>женский</span>
    </label>
    <label>
        <input class="form" type="hidden" name="csrf" value="<?= $csrfToken ?? '' ?>">
    </label>
    <button type="submit">Зарегистрироваться</button>
</form>
