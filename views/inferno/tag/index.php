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
</div>';

foreach ($posts as $post) {

    if ($post->isLiked()) {
        $likePost = "likePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
        $dislikePost = "dislikePost('{$post->getSlug()}', {$post->getRating()->getRating()})";
        $ratingBox = '<div id="post_rating_box_' . $post->getSlug() . '" class="post_rating_box_list">
                      <div id="post_rating_up" onclick="' . $likePost . '">&#9650;</div>
                      <div id="post_rating_value"><span class="' . $post->getRating()->getColorClass() . '">' . $post->getRating()->getRating() . '</span></div>
                      <div id="post_rating_down" onclick="' . $dislikePost . '">&#9660;</div>
                  </div>';
    } else {
        $ratingBox = '<div class="post_rating_box_list">
                          <div id="post_rating_value">
                              <span class="' . $post->getRating()->getColorClass() . '">' . $post->getRating()->getRating() . '</span>
                          </div>
                      </div>';
    }

    echo '<div class="news_preview">
            <div class="news_preview_box">
                <div class="news_preview_content">
                    ' . $ratingBox . '
                    <h2><a href="/p/' . $post->getSlug() . '" title="" class="news_link">' . $post->getTitle() . '</a></h2>
                    ' . $post->getHtmlContent() . '
                </div><div class="news_preview_c_b">Показать полностью</div>
                <div class="news_a_line"></div>
                <p style="float: left;">
                    <a href="/u/' . $post->getAuthorName() . '" title="" class="osnova">' . $post->getAuthorName() . '</a>
                    <span class="news_n_date"> | ' . $this->getCreatedAtEasyData($post->getCreatedAt()) . '</span>
                </p>
                <p style="float: right;"><img src="/inferno/images/comment.gif" alt="" /> ' . $post->getCommentCount() . '</p>
                
            </div>
        </div>
        <div class="row_mc3 news_bottom_line"></div>';
}

