<?php

use App\Domain\Comment\CommentCollection;
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
                    if ($comment->getAuthorId()) {
                        $author = '<a href="/u/' . $comment->getAuthorName() . '" title="" class="cm_author_a">' . $comment->getAuthorName() . '</a>
                           <span class="cm_level">' . $comment->getAuthorLevel() . '</span>';
                        $link = '<a href="/u/' . $comment->getAuthorName() . '" class="full"></a>';
                    } else {
                        $author = '<span class="cm_author_guest">' . $comment->getAuthorName() . ' (guest)</span>';
                        $link = '';
                    }

                    if ($comment->isLiked()) {
                        $likePost = "likeComment('{$comment->getId()}', {$comment->getRating()->getRating()})";
                        $dislikePost = "dislikeComment('{$comment->getId()}', {$comment->getRating()->getRating()})";
                        $ratingBox = '<div id="com_' . $comment->getId() . '" class="cm_con_right">
                          <div class="cm_rating_up" onclick="' . $likePost . '">&#9650;</div>
                          <div class="cm_rating_value"><span class="' . $comment->getRating()->getColorClass() . '">' . $comment->getRating()->getRating() . '</span></div>
                          <div class="cm_rating_down" onclick="' . $dislikePost . '">&#9660;</div>
                      </div>';
                    } else {
                        $ratingBox = '<div class="cm_con_right">
                          <div class="cm_rating_value">
                              <span class="' . $comment->getRating()->getColorClass() . '">' . $comment->getRating()->getRating() . '</span>
                          </div>
                      </div>';
                    }

                    echo '<div class="cm_con">
                              <div class="cm_con_left">
                                  <div class="cm_ava" style="background-image: url(' . $comment->getAuthorAvatar() . ');">' . $link . '</div>
                                  <div class="cm_author">' . $author . '</div>
                              </div>
                              <div class="cm_con_cent">
                                  ' . $ratingBox . '
                                  <div class="cm_date"><abbr title="' . $comment->getCreatedAt()->format('Y-m-d H:i:s') . '">' . $this->getCreatedAtEasyData($comment->getCreatedAt()) . '</abbr></div>
                                  <div class="cm_comment">' . str_replace([PHP_EOL], ['<br />'], $comment->getMessage()) . '</div>
                              </div>
                          </div>';
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
