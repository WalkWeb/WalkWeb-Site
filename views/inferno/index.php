<?php

use App\Domain\Post\Collection\PostCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Интересное';

if (empty($posts) || !($posts instanceof PostCollection)) {
    throw new AppException('view.index: miss or invalid $posts');
}

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
                    <div class="post_r_box" id="post_rating_box_' . $post->getSlug() . '">' . $ratingBox . '</div>
                    <h2><a href="/p/' . $post->getSlug() . '" title="" class="news_link">' . $post->getTitle() . '</a></h2>
                    ' . $post->getHtmlContent() . '
                </div><div class="news_preview_c_b">Показать полностью</div>
                <div class="news_a_line"></div>
                <p style="float: left;">
                    <a href="/u/' . $post->getAuthorName() . '" title="" class="osnova">' . $post->getAuthorName() . '</a>
                    <span class="news_n_date"> | ' . $this->getCreatedAtEasyData($post) . '</span>
                </p>
                <p style="float: right;"><img src="/inferno/images/comment.gif" alt="" /> ' . $post->getCommentCount() . '</p>
                
            </div>
        </div>
        <div class="row_mc3 news_bottom_line"></div>';
}
