<?php

use App\Domain\Pieces\View\PostView;
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
</div>

<hr><br /><br />';

if (count($posts) > 0) {
    foreach ($posts as $post) {
        echo PostView::printPost($post);
    }
} else {
    echo '<p class="center">Ничего нет</p>';
}

