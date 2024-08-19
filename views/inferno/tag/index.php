<?php

use App\Domain\Post\Collection\PostCollection;
use App\Domain\Post\Tag\TagInterface;
use App\Handler\Tag\TagPageHandler;
use WalkWeb\NW\AppException;

if (empty($tag) || !($tag instanceof TagInterface)) {
    throw new AppException('view.tag.index: miss or invalid $tag');
}

if (empty($posts) || !($posts instanceof PostCollection)) {
    throw new AppException('view.tag.index: miss or invalid $posts');
}

if (empty($rating) || !is_string($rating)) {
    throw new AppException('view.tag.index: miss or invalid $rating');
}

$name = htmlspecialchars(strtoupper($tag->getName()));
$slug = $tag->getSlug();
$this->title = 'Просмотр постов по тегу ' . $name . ' | ' . APP_NAME;

$all = $rating === TagPageHandler::FILTER_ALL ? 'Все подряд | ' : '<a href="/t/' . $slug . '/all">Все подряд</a>  | ';
$trend = $rating === TagPageHandler::FILTER_TREND ? TagPageHandler::RATING_TREND . '+ | ' : '<a href="/t/' . $slug . '/trend">' . TagPageHandler::RATING_TREND . '+</a> | ';
$hot = $rating === TagPageHandler::FILTER_HOT ? TagPageHandler::RATING_HOT . '+ | ' : '<a href="/t/' . $slug . '/hot">' . TagPageHandler::RATING_HOT . '+</a> | ';
$top = $rating === TagPageHandler::FILTER_TOP ? TagPageHandler::RATING_TOP . '+ | ' : '<a href="/t/' . $slug . '/top">' . TagPageHandler::RATING_TOP . '+</a> | ';
$best = $rating === TagPageHandler::BEST_POST ? 'Лучшие' : '<a href="/t/' . $slug . '/best">Лучшие</a>';

$link = $all . $trend . $hot . $top . $best;

echo '
<div class="t_box">
    <div class="t_icon" style="background-image: url(' . $tag->getIcon() . ')"></div>
    <div class="t_content">
        <span class="t_head">Просмотр постов по тегу<br />' . $name . '</span><br /><br />
        <span class="t_link">' . $link . '</span>
    </div>
</div>';

if (count($posts) > 0) {
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
} else {
    echo '<p class="center">Ничего нет</p>';
}
