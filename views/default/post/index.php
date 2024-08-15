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

<?= $ratingBox ?>

<h1><?= htmlspecialchars($post->getTitle()) ?></h1>

<?= $post->getHtmlContent() ?>

<hr>

<div class="tag_container">
<?php
foreach ($post->getTags() as $tag) {
    echo '
    <div class="tag_box">
        <p><a href="#">
            <img src="' . $tag->getIcon() . '" alt="" class="see_also_img"><br>
            ' . $tag->getName() . '</a>
        </p>
    </div>';
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
        <div class="comment_head">
            Комментарии
        </div>
        <?php
        foreach ($comments as $comment) {

            if ($comment->getAuthorId()) {
                $author = '<a href="/u/' . $comment->getAuthorName() . '" title="" class="cm_author">' . $comment->getAuthorName() . '</a>
                           <span class="cm_level">' . $comment->getAuthorLevel() . '</span>';
                $link = '<a href="/u/' . $comment->getAuthorName() . '" class="full"></a>';
            } else {
                $author = '<span class="cm_guest">' . $comment->getAuthorName() . ' (guest)</span>';
                $link = '';
            }

            echo '<div class="comment_row">
                      <div class="comment_user_box">
                          <div class="comment_avatar" style="background-image: url(' . $comment->getAuthorAvatar() . ');">' . $link . '</div>
                          <div class="comment_name">' . $author . '</div>
                      </div>
                      <div class="comment_message">
                      ' . $comment->getMessage() . '<br /><br />
                      <abbr title="' . $comment->getCreatedAt()->format('Y-m-d H:i:s') . '"><span class="cm_date">' . $this->getCreatedAtEasyData($comment->getCreatedAt()) . '</span></abbr>
                      </div>
                  </div>';
        }
        ?>
    </div>
</div>
