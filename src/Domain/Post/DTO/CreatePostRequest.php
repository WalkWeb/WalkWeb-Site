<?php

declare(strict_types=1);

namespace App\Domain\Post\DTO;

use App\Domain\Auth\AuthInterface;

class CreatePostRequest
{
    private string $title;
    private string $content;
    private array $tags;
    private AuthInterface $author;

    public function __construct(string $title, string $content, array $tags, AuthInterface $author)
    {
        $this->title = $title;
        $this->content = $content;
        $this->tags = $tags;
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return AuthInterface
     */
    public function getAuthor(): AuthInterface
    {
        return $this->author;
    }
}
