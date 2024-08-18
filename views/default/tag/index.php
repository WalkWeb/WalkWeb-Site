<?php

use App\Domain\Post\Collection\PostCollection;
use App\Domain\Post\Tag\TagInterface;
use WalkWeb\NW\AppException;

if (empty($tag) || !($tag instanceof TagInterface)) {
    throw new AppException('view.tag.index: miss or invalid $tag');
}

if (empty($posts) || !($posts instanceof PostCollection)) {
    throw new AppException('view.tag.index: miss or invalid $posts');
}

$name = htmlspecialchars(strtoupper($tag->getName()));
$this->title = 'Просмотр постов по тегу ' . $name . ' | ' . APP_NAME;

echo '
<div class="t_box">
    <div class="t_icon" style="background-image: url(' . $tag->getIcon() . ')"></div>
    <div class="t_content">
        <span class="t_head">Просмотр постов по тегу<br />' . $name . '</span><br /><br />
        <span class="t_link">Все подряд | <a href="#">5+</a> | <a href="#">10+</a> | <a href="#">20+</a> | <a href="#">Лучшие</a></span>
    </div>
</div>

<hr><br /><br />';

foreach ($posts as $post) {
    if ($post->isLiked()) {
        $likePost = "likePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
        $dislikePost = "dislikePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
        $ratingBox = '<div id="post_rating_box_' . $post->getSlug() . '" class="post_rating_box">
                      <div id="post_rating_up" onclick="' . $likePost . '">&#9650;</div>
                      <div id="post_rating_value"><span class="' . $post->getRating()->getColorClass() . '">' . $post->getRating()->getRating() . '</span></div>
                      <div id="post_rating_down" onclick="' . $dislikePost . '">&#9660;</div>
                  </div>';
    } else {
        $ratingBox = '<div class="post_rating_box">
                          <div id="post_rating_value">
                              <span class="' . $post->getRating()->getColorClass() . '">' . $post->getRating()->getRating() . '</span>
                          </div>
                      </div>';
    }

    echo '
    <div class="post_box">
        ' . $ratingBox . '
        <a href="/p/' . $post->getSlug() . '" class="post_link">
            ' . $post->getTitle() . '
        </a>
        <p>
            <span class="post_details"><a href="/u/' . $post->getAuthorName() . '" title="" class="osnova">' . $post->getAuthorName() . '</a></span>
            <span class="post_details">php, web, html</span>
        </p>
        ' . $post->getHtmlContent() . '
    </div>';
}

