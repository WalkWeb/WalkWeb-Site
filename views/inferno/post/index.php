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
        foreach ($comments as $comment) {

            if ($comment->getAuthorId()) {
                $author = '<a href="/u/' . $comment->getAuthorName() . '" title="" class="cm_author_a">' . $comment->getAuthorName() . '</a>
                           <span class="cm_lvl">' . $comment->getAuthorLevel() . '</span>';
            } else {
                $author = '<span class="cm_author_guest">' . $comment->getAuthorName() . ' (guest)</span>';
            }

            echo '
            <div class="cm_con">
                <div class="cm_con_left">
                    <div style="background-image: url(' . $comment->getAuthorAvatar() . ');" class="cm_ava"></div>
                    <div class="cm_author">' . $author . '</div>
                </div>
                <div class="cm_con_cent">
                    <div class="cm_date"><abbr title="' . $comment->getCreatedAt()->format('Y-m-d H:i:s') . '">' . $this->getCreatedAtEasyData($comment->getCreatedAt()) . '</abbr></div>
                    <div class="cm_comment">' . $comment->getMessage() . '</div>
                </div>
            </div>';
        }
        ?>
    </div>
    <div class="comment_form_box">
        <!-- TODO Form add comment -->
    </div>
</div>

<div class="newdiv_mc12"></div>
