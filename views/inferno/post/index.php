<?php

use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;

if (empty($post) || !($post instanceof PostInterface)) {
    throw new AppException('post/index view: miss $post');
}

if (!isset($authorize) || !is_bool($authorize)) {
    throw new AppException('post/index view: miss $authorize');
}

if (!isset($owner) || !is_bool($owner)) {
    throw new AppException('post/index view: miss $owner');
}

$ratingBox = '<div class="post_rating_box"><div id="post_rating_value">' . $post->getRating()->getRating() . '</div></div>';

if ($authorize && !$owner) {
    $likePost = "likePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
    $dislikePost = "dislikePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
    $ratingBox = '<div id="post_rating_box_' . $post->getSlug() . '" class="post_rating_box">
                      <div id="post_rating_up" onclick="' . $likePost . '">&#9650;</div>
                      <div id="post_rating_value"><span class="' . $post->getRating()->getColorClass() . '">' . $post->getRating()->getRating() . '</span></div>
                      <div id="post_rating_down" onclick="' . $dislikePost . '">&#9660;</div>
                  </div>';
}

?>

<div class="content_post_main">
    <div class="content_post_nowrap">
        <?= $ratingBox ?>
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
            <a href="/u/<?= $post->getAuthor()->getName() ?>"><?= $post->getAuthor()->getName() ?></a>
            <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
            Опубликован: <abbr title="<?= $post->getCreatedAt()->format('Y-m-d H:i:s') ?>"><?= $this->getCreatedAtEasyData($post) ?></abbr>
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
