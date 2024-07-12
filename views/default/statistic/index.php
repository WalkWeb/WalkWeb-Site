<?php

use App\Domain\Statistic\StatisticInterface;
use WalkWeb\NW\AppException;

$this->title = 'WalkWeb — Статистика';

if (empty($statistic) || !($statistic instanceof StatisticInterface)) {
    throw new AppException('Statistic page: miss $statistic');
}

?>

<h1>Статистика</h1>

<p>
    Пользователей: <?= $statistic->getTotalUser() ?><br />
    Средний уровень: #<br />
    Последний присоединившийся: # в #
</p>

<p>
    Постов: #<br />
    Тегов: #<br />
    Постов на пользователя: #
</p>

<p>
    Комментариев: #<br />
    Комментариев на пользователя: #
</p>

<p>
    Сообществ: #
</p>
