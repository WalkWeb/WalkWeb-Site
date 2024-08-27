<?php

use App\Domain\Pieces\View\PostView;
use App\Domain\Post\Collection\PostCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Интересное';

if (empty($posts) || !($posts instanceof PostCollection)) {
    throw new AppException('view.index: miss or invalid $posts');
}

foreach ($posts as $post) {
    echo PostView::printPost($post);
}
