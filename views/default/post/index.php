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
    $likePost = "likePost({$post->getSlug()}}, {$post->getRating()->getRating()})";
    $dislikePost = "dislikePost({$post->getSlug()}, {$post->getRating()->getRating()})";
    $ratingBox = '<div id="post_rating_box_' . $post->getSlug() . '" class="post_rating_box">
                      <div id="post_rating_up" onclick="' . $likePost . '">&#9650;</div>
                      <div id="post_rating_value">' . $post->getRating()->getRating() . '</div>
                      <div id="post_rating_down" onclick="' . $dislikePost . '">&#9660;</div>
                  </div>';
}

?>

<?= $ratingBox ?>

<h1><?= htmlspecialchars($post->getTitle()) ?></h1>

<?= $post->getHtmlContent() ?>

<hr>

<div class="tag_container">
<?php
foreach ($post->getTags() as $tag) {
    echo '
    <div class="tag_box">
        <p><a href="#">
            <img src="' . $tag->getIcon() . '" alt="" class="see_also_img"><br>
            ' . $tag->getName() . '</a>
        </p>
    </div>';
}
?>
</div>

<hr>

<p>
    Автор: <a href="/u/<?= $post->getAuthor()->getName() ?>" title="" class="osnova"><?= $post->getAuthor()->getName() ?></a>
    <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
    Опубликован: #
</p>
