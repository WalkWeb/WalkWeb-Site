<?php

use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeInterface;
use WalkWeb\NW\AppException;

$this->title = 'WalkWeb — Ваши уведомления';

if (empty($notices) || !($notices instanceof NoticeCollection)) {
    throw new AppException('Account notice page: miss $notices');
}

$pagination = $pagination ?? '';

echo "<h1>Ваши уведомления</h1>";

if (count($notices) > 0) {
    foreach ($notices as $notice) {
        if ($notice->getTypeId() === NoticeInterface::TYPE_SUCCESS) {
            echo
            "<div class='notice_box'>
                <div class='notice_success'><p>{$notice->getType()}</p></div>
                <div class='notice_message_success'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_success'><p>{$notice->getCreatedAt()->format('Y-m-d H:i:s')}</p></div>
            </div>";

        } elseif ($notice->getTypeId() === NoticeInterface::TYPE_WARNING) {
            echo
            "<div class='notice_box'>
                <div class='notice_warning'><p>{$notice->getType()}</p></div>
                <div class='notice_message_warning'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_warning'><p>{$notice->getCreatedAt()->format('Y-m-d H:i:s')}</p></div>
            </div>";
        } else {
            echo
            "<div class='notice_box'>
                <div class='notice_info'><p>{$notice->getType()}</p></div>
                <div class='notice_message_info'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_info'><p>{$notice->getCreatedAt()->format('Y-m-d H:i:s')}</p></div>
            </div>";
        }
    }

    echo "<div class='pagination'>$pagination</div>";
} else {
    echo '<p>Пока у вас нет уведомлений</p>';
}
