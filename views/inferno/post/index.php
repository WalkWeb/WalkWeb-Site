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
    $form = '<textarea rows="5" cols="50" class="input_text" name="comment" placeholder="" id="comment_content"></textarea>
             <input type="submit" class="input_submit" id="create_comment_button" onclick="' . $onclick . '" value="Добавить" />
             <p id="create_comment_button_message"></p>';
} else {
    $form = '<p class="center"> Чтобы оставлять комментарии необходимо <a href="/login" class="osnova">войти</a> или <a href="/registration" class="osnova">зарегистрироваться</a></p>';
}

?>

<div class="row_mc1" style="margin-top: -20px;">
    <div class="content_main_box">

        <div class="content_post_main">
            <div class="content_post_nowrap">
                <?= $ratingBox ?>
                <h1><?= htmlspecialchars($post->getTitle()) ?></h1>
                <?= $post->getHtmlContent() ?>
            </div>
        </div>

        <div class="post_a_line"></div>

        <div class="post_footer_c">
            <div class="post_footer_r">
                <div class="post_footer_ava_box">
                    <img src="<?= $post->getAuthor()->getAvatar() ?>" alt="" />
                </div>
                <div class="post_footer_author_box">
                    <a href="/u/<?= $post->getAuthor()->getName() ?>"><?= $post->getAuthor()->getName() ?></a>
                    <span class="post_author_lvl"><?= $post->getAuthor()->getLevel() ?></span><br />
                    Опубликован: <abbr title="<?= $post->getCreatedAt()->format('Y-m-d H:i:s') ?>"><?= $this->getCreatedAtEasyData($post->getCreatedAt()) ?></abbr>
                </div>
                <div class="tag_container">
                    <?php
                    foreach ($post->getTags() as $tag) {
                        echo '
                        <div class="tag_box">
                            <p><a href="#">
                                <img src="' . $tag->getIcon() . '" alt="" /><br>
                                ' . $tag->getName() . '</a>
                            </p>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row_mc3"></div>

<div class="cm_start">
    <div class="cm_start_c">
        <div class="cm_start_l">
            <div class="cm_start_r">
                <span class="cm_start_t" id="comment">Комментарии</span>
            </div>
        </div>
    </div>
</div>

<div class="row_com">
    <div id="comment_box">
        <?php
        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                if ($comment->getAuthorId()) {
                    $author = '<a href="/u/' . $comment->getAuthorName() . '" title="" class="cm_author_a">' . $comment->getAuthorName() . '</a> <span class="cm_lvl">' . $comment->getAuthorLevel() . '</span>';
                } else {
                    $author = '<span class="cm_author_guest">' . $comment->getAuthorName() . ' (guest)</span>';
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

                echo '
            <div class="cm_con">
                <div class="cm_con_left">
                    <div style="background-image: url(' . $comment->getAuthorAvatar() . ');" class="cm_ava"></div>
                    <div class="cm_author">' . $author . '</div>
                </div>
                <div class="cm_con_cent">
                    ' . $ratingBox . '
                    <div class="cm_date"><abbr title="' . $comment->getCreatedAt()->format('Y-m-d H:i:s') . '">' . $this->getCreatedAtEasyData($comment->getCreatedAt()) . '</abbr></div>
                    <div class="cm_comment">' . $comment->getMessage() . '</div>
                </div>
            </div>';
            }

            echo '<p id="no_comment_rvd" class="center hidden">Комментариев нет</p>';

        } else {
            echo '<p id="no_comment_rvd" class="center">Комментариев нет</p>';
        }
        ?>
    </div>
    <div class="comment_form_box"><?= $form ?></div>
</div>

<div class="newdiv_mc12"></div>

<script src="/js/post.js?v=1.00"></script>
