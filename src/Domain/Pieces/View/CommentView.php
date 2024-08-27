<?php

declare(strict_types=1);

namespace App\Domain\Pieces\View;

use App\Domain\Comment\CommentInterface;
use WalkWeb\NW\Traits\DateTrait;

class CommentView
{
    use DateTrait;

    public static function printComment(CommentInterface $comment, int $level = 0): string
    {
        $children = '';

        if (count($comment->getChildren()) > 0) {
            foreach ($comment->getChildren() as $child) {
                $children .= self::printComment($child, $level + 1);
            }
        }

        if ($comment->getAuthorId()) {
            $author = '<a href="/u/' . $comment->getAuthorName() . '" title="" class="cm_author_a">' . $comment->getAuthorName() . '</a> 
                        <span class="cm_level">' . $comment->getAuthorLevel() . '</span>';
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

        $padding = $level * 30;

        return '
            <div class="cm_con">
                <div class="cm_bias" style="padding-left: ' . $padding . 'px;">
                    <div class="cm_con_left">
                        <div class="cm_com_ava_box">
                            <div style="background-image: url(' . $comment->getAuthorAvatar() . ');" class="cm_ava"></div>
                            <div class="cm_author">' . $author . '</div>
                        </div>
                    </div>
                    <div class="cm_con_cent">
                        ' . $ratingBox . '
                        <div class="cm_date"><abbr title="' . $comment->getCreatedAt()->format('Y-m-d H:i:s') . '">' . self::getElapsedTime($comment->getCreatedAt()) . '</abbr></div>
                        <div class="cm_comment">' . str_replace([PHP_EOL], ['<br />'], $comment->getMessage()) . '</div>
                    </div>
                </div>
            </div>' . $children;
    }
}
