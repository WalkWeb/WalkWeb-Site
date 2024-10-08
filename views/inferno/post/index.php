<?php

use App\Domain\Comment\CommentCollection;
use App\Domain\Community\BlankCommunity;
use App\Domain\Community\CommunityInterface;
use App\Domain\Pieces\View\CommentView;
use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;

if (empty($post) || !($post instanceof PostInterface)) {
    throw new AppException('post/index view: miss or invalid $post');
}

if (empty($comments) || !($comments instanceof CommentCollection)) {
    throw new AppException('post/index view: miss or invalid $comments');
}

if (empty($community) || !($community instanceof CommunityInterface)) {
    throw new AppException('post/index view: invalid $community');
}

$this->title = htmlspecialchars($post->getTitle()) . ' | ' . APP_NAME;

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

$mainDiv = 'class="row_mc1"';

if (!($community instanceof BlankCommunity)) {

    $mainDiv = 'class="news_preview" style="margin-top: -20px;"';

    // TODO Add community menu, delete description

    echo '
    <div class="c_box">
        <div class="c_l">
            <div class="c_icon_small" style="background-image: url(' . $community->getIcon() . ');">
                <a href="/c/' . $community->getSlug() . '" class="full"></a>
            </div>
        </div>
        <div class="c_content">
            <a href="/c/' . $community->getSlug() . '" class="c_head">' . $community->getName() . '</a><br /><br />
            <span class="c_link">' . $community->getDescription() . '</span><br /><br />
            <span class="c_link">
                    <span class="yellow">' . $community->getTotalPostCount() . '</span> постов |
                    <span class="orange">' . $community->getTotalCommentCount() . '</span> комментариев |
                    <span class="blue">' . $community->getFollowers() . '</span> подписчиков
            </span>
        </div>
    </div>';
}

?>

<div <?= $mainDiv?> >
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
                echo CommentView::printComment($comment);
            }

            echo '<p id="no_comment_rvd" class="center hidden">Комментариев нет</p>';

        } else {
            echo '<p id="no_comment_rvd" class="center">Комментариев нет</p>';
        }
        ?>
    </div>
    <div class="comment_form_box"><?= $form ?></div>
</div>

<script src="/js/post.js?v=1.00"></script>
