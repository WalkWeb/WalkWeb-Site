<?php

use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Ваши уведомления';

if (empty($notices) || !($notices instanceof NoticeCollection)) {
    throw new AppException('Account notice page: miss $notices');
}

$pagination = $pagination ?? '';
$total = $total ?? 0;

echo '<div class="cr_parent">
        » <a href="/profile" title="" class="osnova">вернуться в профиль</a>
      </div>';

echo "<h3>Ваши уведомления ($total)</h3>";

if (count($notices) > 0) {
    foreach ($notices as $notice) {
        if ($notice->getTypeId() === NoticeInterface::TYPE_SUCCESS) {
            echo
            "<div class='notice_box'>
                <div class='notice_success'><p>{$notice->getType()}</p></div>
                <div class='notice_message_success'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_success'><p>{$notice->getElapsedCreatedAt()}</p></div>
            </div>";

        } elseif ($notice->getTypeId() === NoticeInterface::TYPE_WARNING) {
            echo
            "<div class='notice_box'>
                <div class='notice_warning'><p>{$notice->getType()}</p></div>
                <div class='notice_message_warning'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_warning'><p>{$notice->getElapsedCreatedAt()}</p></div>
            </div>";
        } else {
            echo
            "<div class='notice_box'>
                <div class='notice_info'><p>{$notice->getType()}</p></div>
                <div class='notice_message_info'><p>{$notice->getMessage()}</p></div>
                <div class='notice_date_info'><p>{$notice->getElapsedCreatedAt()}</p></div>
            </div>";
        }
    }

    echo "<div class='pagination'>$pagination</div>";
} else {
    echo '<p>Пока у вас нет уведомлений</p>';
}
