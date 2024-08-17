<?php

use App\Domain\Statistic\StatisticInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Статистика';

if (empty($statistic) || !($statistic instanceof StatisticInterface)) {
    throw new AppException('Statistic page: miss $statistic');
}

$totalUsers = $statistic->getTotalUsers();
$totalPosts = $statistic->getTotalPosts();
$totalComments = $statistic->getTotalComments();
$postOnUser = $totalUsers > 0 ? round($totalPosts / $totalUsers, 2) : 0;
$commentOnUser = $totalUsers > 0 ? round($totalComments / $totalUsers, 2) : 0;

?>

<h1><?= $this->title ?></h1>

<h2>Аккаунты</h2>

<ul>
    <li>
        Пользователей: <?= $totalUsers ?>
    </li>
    <li>
        Средний уровень: #
    </li>
    <li>
        Общее количество постов: <?= $totalPosts ?>
    </li>
    <li>
        Общее количество комментариев: <?= $totalComments ?>
    </li>
    <li>
        Среднее количество постов на одного пользователя: <?= $postOnUser ?>
    </li>
    <li>
        Среднее количество комментариев на одного пользователя: <?= $commentOnUser ?>
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
