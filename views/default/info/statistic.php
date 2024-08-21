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

<h1>Статистика</h1>

<p>
    Пользователей: <?= $totalUsers ?><br />
    Средний уровень: #<br />
    Последний присоединившийся: # в #
</p>

<p>
    Постов: <?= $totalPosts ?><br />
    Тегов: <?= $statistic->getTotalTags() ?><br />
    Постов на пользователя: <?= $postOnUser ?>
</p>

<p>
    Комментариев: <?= $totalComments ?><br />
    Комментариев на пользователя: <?= $commentOnUser ?>
</p>

<p>
    Игр: #
</p>

<p>
    Сообществ: #
</p>
