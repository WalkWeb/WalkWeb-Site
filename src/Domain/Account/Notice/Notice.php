<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use DateTimeInterface;

class Notice implements NoticeInterface
{
    private string $id;
    private int $type;
    private string $accountId;
    private string $message;
    private bool $view;
    private DateTimeInterface $createdAt;

    /**
     * @param string $id
     * @param int $type
     * @param string $accountId
     * @param string $message
     * @param bool $view
     * @param DateTimeInterface $createdAt
     * @throws NoticeException
     */
    public function __construct(
        string $id,
        int $type,
        string $accountId,
        string $message,
        bool $view,
        DateTimeInterface $createdAt
    )
    {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->message = $message;
        $this->view = $view;
        $this->createdAt = $createdAt;
        $this->setType($type);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isView(): bool
    {
        return $this->view;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param int $type
     * @throws NoticeException
     */
    private function setType(int $type): void
    {
        if (!in_array($type, [self::TYPE_INFO, self::TYPE_WARNING, self::TYPE_SUCCESS], true)) {
            throw new NoticeException(NoticeException::UNKNOWN_TYPE . ': ' . $type);
        }

        $this->type = $type;
    }
}
