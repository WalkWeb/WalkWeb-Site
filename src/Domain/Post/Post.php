<?php

declare(strict_types=1);

namespace App\Domain\Post;

use DateTimeInterface;
use App\Domain\Post\Author\AuthorInterface;
use App\Domain\Post\Rating\RatingInterface;
use App\Domain\Post\Status\PostStatusInterface;
use App\Domain\Post\Tag\TagCollection;

class Post implements PostInterface
{
    private string $id;
    private string $title;
    private string $slug;
    private string $content;
    private string $htmlContent;
    private PostStatusInterface $status;
    private AuthorInterface $author;
    private RatingInterface $rating;
    private int $commentsCount;
    private bool $published;
    private TagCollection $tags;
    private bool $isLiked;
    private DateTimeInterface $createdAt;
    private ?DateTimeInterface $updatedAt;

    public function __construct(
        string $id,
        string $title,
        string $slug,
        string $content,
        string $htmlContent,
        PostStatusInterface $status,
        AuthorInterface $author,
        RatingInterface $rating,
        int $commentsCount,
        bool $published,
        TagCollection $collection,
        bool $isLiked,
        DateTimeInterface $createdAt,
        ?DateTimeInterface $updatedAt = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->htmlContent = $htmlContent;
        $this->status = $status;
        $this->author = $author;
        $this->rating = $rating;
        $this->commentsCount = $commentsCount;
        $this->published = $published;
        $this->tags = $collection;
        $this->isLiked = $isLiked;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return htmlspecialchars($this->title);
    }

    /**
     * @param string $title
     * @throws PostException
     */
    public function setTitle(string $title): void
    {
        $length = mb_strlen($title);

        if ($length < self::TITLE_MIN_LENGTH || $length > self::TITLE_MAX_LENGTH) {
            throw new PostException(
                PostException::INVALID_TITLE_LENGTH . self::TITLE_MIN_LENGTH . '-' . self::TITLE_MAX_LENGTH
            );
        }

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return htmlspecialchars($this->content);
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    /**
     * @param string $content
     * @throws PostException
     */
    public function setContent(string $content): void
    {
        $length = mb_strlen($content);

        if ($length < self::CONTENT_MIN_LENGTH || $length > self::CONTENT_MAX_LENGTH) {
            throw new PostException(
                PostException::INVALID_CONTENT_LENGTH . self::CONTENT_MIN_LENGTH . '-' . self::CONTENT_MAX_LENGTH
            );
        }

        $this->content = $content;
    }

    /**
     * @return PostStatusInterface
     */
    public function getStatus(): PostStatusInterface
    {
        return $this->status;
    }

    /**
     * @return AuthorInterface
     */
    public function getAuthor(): AuthorInterface
    {
        return $this->author;
    }

    /**
     * @return RatingInterface
     */
    public function getRating(): RatingInterface
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->commentsCount;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     */
    public function setTags(TagCollection $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return bool
     */
    public function isLiked(): bool
    {
        return $this->isLiked;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'content'          => $this->content,
            'status_id'        => $this->status->getId(),
            'likes'            => $this->rating->getLikes(),
            'dislikes'         => $this->rating->getDislikes(),
            'user_reaction'    => $this->rating->getUserReaction(),
            'comments_count'   => $this->commentsCount,
            'published'        => $this->published,
            'tags'             => $this->tags->toArray(),
            'is_liked'         => $this->isLiked(),
            'author_id'        => $this->author->getId(),
            'author_name'      => $this->author->getName(),
            'author_avatar'    => $this->author->getAvatar(),
            'author_level'     => $this->author->getLevel(),
            'author_status_id' => $this->author->getStatus()->getId(),
            'created_at'       => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'       => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
