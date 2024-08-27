<?php

use App\Domain\Community\CommunityCollection;
use WalkWeb\NW\AppException;

$this->title =  APP_NAME . ' — Сообщества';

if (empty($communities) || !($communities instanceof CommunityCollection)) {
    throw new AppException('community/list view: miss or invalid $communities');
}

?>
<br />

<h1><?= $this->title ?></h1>

<?php

foreach ($communities as $community) {
    echo '
    <div class="com_box">
        <div class="com_l">
            <div class="com_icon" style="background-image: url(' . $community->getIcon() . ');">
                <a href="/c/' . $community->getSlug() . '" class="full"></a>
            </div>
        </div>
        <div class="com_r">
            <a href="/c/' . $community->getSlug() . '" class="com_link">' . $community->getName() . '</a>
            <p class="com_desc">' . $community->getDescription() . '</p>
            <div class="com_hr"></div>
            <p class="com_info">
                <span class="yellow">' . $community->getTotalPostCount() . '</span> постов | 
                <span class="orange">' . $community->getTotalCommentCount() . '</span> комментариев | 
                <span class="blue">' . $community->getFollowers() . '</span> подписчиков
            </p>
        </div>
    </div>
    ';
}
