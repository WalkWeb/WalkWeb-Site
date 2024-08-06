<?php

use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;

if (empty($post) || !($post instanceof PostInterface)) {
    throw new AppException('post/index view: miss $post');
}

?>

<div class="content_post_main">
    <div class="content_post_nowrap">
        <h1><?= htmlspecialchars($post->getTitle()) ?></h1>
        <?= $post->getHtmlContent() ?>
    </div>
</div>

<div class="post_a_line"></div>

<div class="content_post_author">
    <div class="post_author_box">
        <div class="post_author_ava">
            <img src="<?= $post->getAuthor()->getAvatar() ?>" alt="" />
        </div>
        <div class="post_author_desc">
            Автор: <a href="/u/<?= $post->getAuthor()->getName() ?>" title="" class="osnova"><?= $post->getAuthor()->getName() ?></a>
            <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
            Опубликован: #
        </div>
    </div>
</div>
