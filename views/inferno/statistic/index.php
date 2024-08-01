<?php

use App\Domain\Statistic\StatisticInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Статистика';

if (empty($statistic) || !($statistic instanceof StatisticInterface)) {
    throw new AppException('Statistic page: miss $statistic');
}

?>

<h1><?= $this->title ?></h1>

<h2>Аккаунты</h2>

<ul>
    <li>
        Пользователей: <?= $statistic->getTotalUser() ?>
    </li>
    <li>
        Средний уровень: #
    </li>
    <li>
        Общее количество постов: #
    </li>
    <li>
        Общее количество комментариев: #
    </li>
    <li>
        Среднее количество постов на один аккаунт: #
    </li>
    <li>
        Среднее количество комментариев на один аккаунт: #
    </li>
    <li>
        Последний зарегистрированный: #
    </li>
</ul>

<h2>Персонажи</h2>

<ul>
    <li>
        Общее количество персонажей: #
    </li>
    <li>
        Средний уровень персонажа: #
    </li>
    <li>
        Пройдено локаций: #
    </li>
    <li>
        Среднее количество пройденных локаций на персонажа: #
    </li>
    <li>
        Среднее количество пройденных локаций на аккаунт: #
    </li>
</ul>
