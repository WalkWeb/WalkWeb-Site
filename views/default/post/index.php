<?php

use App\Domain\Comment\CommentCollection;
use App\Domain\Pieces\View\CommentView;
use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;

if (empty($post) || !($post instanceof PostInterface)) {
    throw new AppException('post/index view: miss $post');
}

if (empty($comments) || !($comments instanceof CommentCollection)) {
    throw new AppException('post/index view: miss $comments');
}

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

if (isset($auth) && $auth === true) {
    $onclick = "addComment('{$post->getSlug()}')";
    $form = '<div class="cm_form">
                 <textarea name="comment" id="comment_content" class="form cm_add"></textarea>
                 <input type="submit" class="button center" id="create_comment_button" onclick="' . $onclick . '" value="Добавить" />
             </div>
             <p id="create_comment_button_message"></p>';
} else {
    $form = '<p class="center"> Чтобы оставлять комментарии необходимо <a href="/login" class="osnova">войти</a> или <a href="/registration" class="osnova">зарегистрироваться</a></p>';
}

?>

<?= $ratingBox ?>

<h1><?= htmlspecialchars($post->getTitle()) ?></h1>

<?= $post->getHtmlContent() ?>

<hr>

<div class="tag_container">
    <?php
    foreach ($post->getTags() as $tag) {
        if ($tag->getIcon()) {
            echo '
            <div class="tag_box">
                <p><a href="/t/' . $tag->getSlug() . '/all">
                    <img src="' . $tag->getIcon() . '" alt="" /><br>
                    ' . $tag->getName() . '</a>
                </p>
            </div>';
        } else {
            echo '
            <div class="tag_box">
                <p><a href="/t/' . $tag->getSlug() . '/all">' . $tag->getName() . '</a></p>
            </div>';
        }
    }
    ?>
</div>

<hr>

<p>
    Автор: <a href="/u/<?= $post->getAuthor()->getName() ?>" title="" class="osnova"><?= $post->getAuthor()->getName() ?></a>
    <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
    Опубликован: <abbr title="<?= $post->getCreatedAt()->format('Y-m-d H:i:s') ?>"><?= $this->getCreatedAtEasyData($post->getCreatedAt()) ?></abbr>
</p>

<div class="comment_container">
    <div class="comment_content">
        <div class="comment_head" id="comment">
            Комментарии
        </div>
        <div id="comment_box">
            <?php
            if (count($comments) > 0) {
                foreach ($comments as $comment) {
                    echo CommentView::printComment($comment);
                }

                echo '<p id="no_comment_rvd" class="center" style="display: none;">Комментариев нет</p>';

            } else {
                echo '<p id="no_comment_rvd" class="center">Комментариев нет</p>';
            }
            ?>

            <?= $form ?>
        </div>
    </div>
</div>



<script src="/js/post.js?v=1.00"></script>
