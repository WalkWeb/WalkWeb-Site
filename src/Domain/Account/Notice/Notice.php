<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use DateTimeInterface;

class Notice implements NoticeInterface
{
    private static array $map = [
        self::TYPE_INFO     => 'Info',
        self::TYPE_WARNING  => 'Warning',
        self::TYPE_SUCCESS  => 'Success',
    ];

    private string $id;
    private int $typeId;
    private string $type;
    private string $accountId;
    private string $message;
    private bool $view;
    private DateTimeInterface $createdAt;

    /**
     * @param string $id
     * @param int $typeId
     * @param string $accountId
     * @param string $message
     * @param bool $view
     * @param DateTimeInterface $createdAt
     * @throws NoticeException
     */
    public function __construct(
        string $id,
        int $typeId,
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
        $this->setType($typeId);
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
    public function getTypeId(): int
    {
        return $this->typeId;
    }

    /**
     * @return string
     */
    public function getType(): string
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
     * @param int $id
     * @throws NoticeException
     */
    private function setType(int $id): void
    {
        if (!array_key_exists($id, self::$map)) {
            throw new NoticeException(NoticeException::UNKNOWN_TYPE . ': ' . $id);
        }

        $this->typeId = $id;
        $this->type = self::$map[$id];
    }
}
