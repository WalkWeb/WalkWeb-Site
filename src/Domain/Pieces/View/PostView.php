<?php

declare(strict_types=1);

namespace App\Domain\Pieces\View;

use App\Domain\Post\Collection\PostListInterface;
use WalkWeb\NW\Traits\DateTrait;

class PostView
{
    use DateTrait;

    public static function printPost(PostListInterface $post, bool $skipCommunityLink = false): string
    {
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

        $communityLink = '';

        if (!$skipCommunityLink && $post->getCommunitySlug()) {
            $communityLink = '<a href="/c/' . $post->getCommunitySlug() . '" class="com_tl">' . $post->getCommunityName() . '</a> » ';
        }

        return '<div class="news_preview">
            <div class="news_preview_box">
                <div class="news_preview_content">
                    ' . $ratingBox . '
                    <h2>' . $communityLink . '<a href="/p/' . $post->getSlug() . '" title="" class="news_link">' . $post->getTitle() . '</a></h2>
                    ' . $post->getHtmlContent() . '
                </div><div class="news_preview_c_b">Показать полностью</div>
                <div class="news_a_line"></div>
                <div class="news_footer">
                    <div class="news_fl">
                        <a href="/u/' . $post->getAuthorName() . '" title="">' . $post->getAuthorName() . '</a>
                        <span class="news_n_date"> | ' . self::getElapsedTime($post->getCreatedAt()) . '</span>
                    </div>
                    <div class="news_fr">
                        <div class="news_com_icon"></div> ' . $post->getCommentCount() . '
                    </div>
                </div>
            </div>
        </div>
        <div class="row_mc3 news_bottom_line"></div>';
    }
}
