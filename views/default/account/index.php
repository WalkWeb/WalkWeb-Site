<?php

use App\Domain\Account\AccountInterface;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use WalkWeb\NW\AppException;

if (empty($account) || !($account instanceof AccountInterface)) {
    throw new AppException('view.account.index: miss account');
}

if (empty($mainCharacter) || !($mainCharacter instanceof MainCharacterInterface)) {
    throw new AppException('view.account.index: miss mainCharacter');
}

$this->title = 'Профиль пользователя ' . $account->getName();

$emailVerified = $account->isEmailVerified() ? 'да' : 'нет';
$regComplete = $account->isRegComplete() ? 'да' : 'нет';
$canLike = $account->isCanLike() ? 'да' : 'нет';

?>

<h1><?=$account->getName() ?></h1>

<p>id: <?= $account->getId() ?></p>
<p>Логин: <?= htmlspecialchars($account->getLogin()) ?></p>
<p>Имя: <?= htmlspecialchars($account->getName()) ?></p>
<p>Email: <?= htmlspecialchars($account->getEmail()) ?></p>
<p>Email подтвержден: <?= $emailVerified ?></p>
<p>Регистрация завершена: <?= $regComplete ?></p>
<p>Используемый шаблон: <?= $account->getTemplate() ?></p>
<p>IP регистрации: <?= $account->getIp() ?></p>
<p>Реферал: <?= $account->getRef() ?></p>
<p>Данные браузера: <?= $account->getUserAgent() ?></p>
<p>Может лайкать чужие посты/комментарии: <?= $canLike ?></p>
<p>Пол: <?= $account->getFloor()->getName() ?></p>
<p>Статус: <?= $account->getStatus()->getName() ?></p>
<p>Группа: <?= $account->getGroup()->getName() ?></p>
<p>Занятое место на диске: <?= $account->getUpload()->getUpload() ?></p>
<p>Доступно место на диске: <?= $account->getUpload()->getUploadMax() ?></p>
<p>Осталось места на диске: <?= $account->getUpload()->getUploadRemainder() ?></p>
<p>Дата регистрации: <?= $account->getCreatedAt()->format('Y-m-d H:i:s') ?></p>
<p>Последнее обновление данных: <?= $account->getUpdatedAt()->format('Y-m-d H:i:s') ?></p>
<br />
<hr>
<br />
<p>Уровень: <?= htmlspecialchars($mainCharacter->getLevel()->getLevel()) ?></p>
<p>Опыта: <?= htmlspecialchars($mainCharacter->getLevel()->getExp()) ?></p>
<p>Опыта до следующего уровня: <?= htmlspecialchars($mainCharacter->getLevel()->getExpToLevel()) ?></p>
<p>Эра: <?= htmlspecialchars($mainCharacter->getEra()->getName()) ?></p>
<p>Бонус к энергии: <?= htmlspecialchars($mainCharacter->getEnergyBonus()) ?></p>
<p>Бонус к месту на диске: <?= htmlspecialchars($mainCharacter->getUploadBonus()) ?></p>
<p>Свободных очков для распределения: <?= htmlspecialchars($mainCharacter->getLevel()->getStatPoints()) ?></p>
<br />
