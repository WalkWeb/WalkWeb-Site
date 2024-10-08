<?php

use App\Domain\Account\AccountInterface;
use WalkWeb\NW\AppException;

if (empty($account) || !($account instanceof AccountInterface)) {
    throw new AppException('view.account.profile: miss account');
}

$this->title = APP_NAME . ' — Ваш профиль';

$emailVerified = $account->isEmailVerified() ? 'да' : 'нет';
$regComplete = $account->isRegComplete() ? 'да' : 'нет';
$canLike = $account->isCanLike() ? 'да' : 'нет';

?>

<h1><?=$account->getName() ?></h1>

<p>
    <a href="/add/exp" class="button">Добавить 60 опыта</a>
    <a class="button" onclick="reducedEnergy()">-40 энергии</a>
</p>

<p>
    id: <?= $account->getId() ?><br />
    Логин: <?= htmlspecialchars($account->getLogin()) ?><br />
    Имя: <?= htmlspecialchars($account->getName()) ?><br />
    Email: <?= htmlspecialchars($account->getEmail()) ?><br />
    Email подтвержден: <?= $emailVerified ?><br />
    Регистрация завершена: <?= $regComplete ?><br />
    Используемый шаблон: <?= $account->getTemplate() ?><br />
    IP регистрации: <?= $account->getIp() ?><br />
    Реферал: <?= $account->getRef() ?><br />
    Количество постов: <?= $account->getPostCount() ?><br />
    Количество комментариев: <?= $account->getCommentCount() ?><br />
    Данные браузера: <?= $account->getUserAgent() ?><br />
    Может лайкать чужие посты/комментарии: <?= $canLike ?><br />
    Пол: <?= $account->getFloor()->getName() ?><br />
    Статус: <?= $account->getStatus()->getName() ?><br />
    Группа: <?= $account->getGroup()->getName() ?><br />
    Карма: <span class="<?= $account->getCarma()->getCarmaColoClass() ?>"><?= $account->getCarma()->getCarmaSign() . $account->getCarma()->getCarma() ?></span>
    Дата регистрации: <?= $account->getCreatedAt()->format('Y-m-d H:i:s') ?><br />
    Последнее обновление данных: <?= $account->getUpdatedAt()->format('Y-m-d H:i:s') ?>
</p>
<br />
<hr>
<br />
<p>
    Уровень: <?= htmlspecialchars($account->getMainCharacter()->getLevel()->getLevel()) ?><br />
    Опыта: <?= htmlspecialchars($account->getMainCharacter()->getLevel()->getExp()) ?><br />
    Опыта до следующего уровня: <?= htmlspecialchars($account->getMainCharacter()->getLevel()->getExpToLevel()) ?><br />
    Эра: <?= htmlspecialchars($account->getMainCharacter()->getEra()->getName()) ?><br />
    Бонус к энергии: <?= htmlspecialchars($account->getMainCharacter()->getEnergyBonus()) ?><br />
    Бонус к месту на диске: <?= htmlspecialchars($account->getMainCharacter()->getUploadBonus()) ?><br />
    Свободных очков для распределения: <?= htmlspecialchars($account->getMainCharacter()->getLevel()->getStatPoints()) ?>
</p>
<br />
<hr>
<br />
<p>
    Занятое место на диске: <?= $account->getUpload()->getUpload() ?><br />
    Доступно место на диске: <?= $account->getUpload()->getUploadMax() ?><br />
    Осталось места на диске: <?= $account->getUpload()->getUploadRemainder() ?><br />
</p>

<div class="upload_background">
    <div class="upload_fill" style="width: <?= $account->getUpload()->getUploadBarWeight() ?>%"></div>
</div>
<div class="upload_text"><?= $account->getUpload()->getUploadMb() ?> мб / <?= $account->getUpload()->getUploadMaxMb() ?> мб</div>
<br />
<hr>
<br />
<p><a href="/logout" class="button">Выйти</a></p>
