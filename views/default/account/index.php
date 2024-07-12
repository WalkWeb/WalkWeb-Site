<?php

use App\Domain\Account\AccountInterface;
use WalkWeb\NW\AppException;

if (empty($account) || !($account instanceof AccountInterface)) {
    throw new AppException('view.account.index: miss account');
}

$this->title = 'Профиль пользователя ' . $account->getName();

$emailVerified = $account->isEmailVerified() ? 'да' : 'нет';
$regComplete = $account->isRegComplete() ? 'да' : 'нет';
$canLike = $account->isCanLike() ? 'да' : 'нет';

?>

<h1><?=$account->getName() ?></h1>

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
    Данные браузера: <?= $account->getUserAgent() ?><br />
    Может лайкать чужие посты/комментарии: <?= $canLike ?><br />
    Пол: <?= $account->getFloor()->getName() ?><br />
    Статус: <?= $account->getStatus()->getName() ?><br />
    Группа: <?= $account->getGroup()->getName() ?><br />
    Занятое место на диске: <?= $account->getUpload()->getUpload() ?><br />
    Доступно место на диске: <?= $account->getUpload()->getUploadMax() ?><br />
    Осталось места на диске: <?= $account->getUpload()->getUploadRemainder() ?><br />
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
