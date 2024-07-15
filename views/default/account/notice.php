<?php

use App\Domain\Account\Notice\NoticeCollection;
use WalkWeb\NW\AppException;

$this->title = 'WalkWeb — Ваши уведомления';

if (empty($notices) || !($notices instanceof NoticeCollection)) {
    throw new AppException('Account notice page: miss $notices');
}

$pagination = $pagination ?? '';

echo "<h1>Ваши уведомления</h1>";

if (count($notices) > 0) {
    foreach ($notices as $notice) {
        echo
        "<div class='notice_box'>
            <div class='notice_info'><p>{$notice->getType()}</p></div>
            <div class='notice_message'><p>{$notice->getMessage()}</p></div>
            <div class='notice_date'><p>{$notice->getCreatedAt()->format('Y-m-d H:i:s')}</p></div>
        </div>";
    }

    echo "<div class='pagination'>$pagination</div>";
} else {
    echo '<p>Пока у вас нет уведомлений</p>';
}
