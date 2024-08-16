<?php

declare(strict_types=1);

namespace App\Domain\Comment\DTO;

class CreateCommentRequest
{
    private string $postSlug;
    private string $message;

    public function __construct(string $postSlug, string $message)
    {
        $this->postSlug = $postSlug;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getPostSlug(): string
    {
        return $this->postSlug;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
