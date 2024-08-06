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

<div class="post_footer_c">
    <div class="post_footer_r">
        <div class="post_footer_ava_box">
            <img src="<?= $post->getAuthor()->getAvatar() ?>" alt="" />
        </div>
        <div class="post_footer_author_box">
            Автор: <a href="/u/<?= $post->getAuthor()->getName() ?>"><?= $post->getAuthor()->getName() ?></a>
            <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
            Опубликован: #
        </div>
        <div class="tag_container">
            <?php
            foreach ($post->getTags() as $tag) {
                echo '
                <div class="tag_box">
                    <p><a href="#">
                        <img src="' . $tag->getIcon() . '" alt="" /><br>
                        ' . $tag->getName() . '</a>
                    </p>
                </div>';
            }
            ?>
        </div>
    </div>
</div>
