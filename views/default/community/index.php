<?php

use App\Domain\Community\CommunityInterface;
use App\Domain\Post\Collection\PostCollection;
use WalkWeb\NW\AppException;

if (empty($community) || !($community instanceof CommunityInterface)) {
    throw new AppException('community/index view: miss or invalid $community');
}

if (empty($posts) || !($posts instanceof PostCollection)) {
    throw new AppException('community/index view: miss or invalid $posts');
}

$this->title = $community->getName() . ' | ' . APP_NAME;

?>

<div class="c_box">
    <div class="c_l">
        <div class="c_icon" style="background-image: url(<?= $community->getIcon() ?>);"></div>
    </div>
    <div class="c_content">
        <span class="c_head"><?= $community->getName() ?></span><br /><br />
        <span class="c_link"><?= $community->getDescription() ?></span><br /><br />
        <span class="c_link">
                <span class="yellow"><?= $community->getTotalPostCount() ?></span> постов |
                <span class="orange"><?= $community->getTotalCommentCount() ?></span> комментариев |
                <span class="blue"><?= $community->getFollowers() ?></span> подписчиков
        </span><br /><br />
        <span class="c_link">Все подряд | <a href="#">3+</a> | <a href="#">5+</a> | <a href="#">10+</a> | <a href="#">Лучшие</a></span>
    </div>
    <div>
        <div class="c_r">
            <a href="#">Подписаться</a>
        </div>
    </div>
</div>
<div class="c_hr"></div>

<?php

if (count($posts) === 0) {
    echo '<p class="center">В сообществе пока нет материалов</p>';
}
