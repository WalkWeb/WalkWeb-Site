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

    $communityLink = '';

    if ($post->getCommunitySlug()) {
        $communityLink = '<a href="/c/' . $post->getCommunitySlug() . '" class="com_tl">' . $post->getCommunityName() . '</a> » ';
    }

    echo '
    <div class="post_box">
        ' . $ratingBox . '
        ' . $communityLink . '<a href="/p/' . $post->getSlug() . '" class="post_link">
            ' . $post->getTitle() . '
        </a>
        <p>
            <span class="post_details"><a href="/u/' . $post->getAuthorName() . '" title="" class="osnova">' . $post->getAuthorName() . '</a></span>
            <span class="post_details">php, web, html</span>
        </p>
        ' . $post->getHtmlContent() . '
    </div>';
}
