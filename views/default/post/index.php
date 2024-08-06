<?php

use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;

if (empty($post) || !($post instanceof PostInterface)) {
    throw new AppException('post/index view: miss $post');
}

?>

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
