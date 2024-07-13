<?php

declare(strict_types=1);

namespace App\Domain\Account\Upload;

class AccountUpload implements UploadInterface
{
    private int $upload;
    private int $uploadMax;

    public function __construct(int $upload, int $uploadMax)
    {
        $this->upload = $upload;
        $this->uploadMax = $uploadMax;
    }

    /**
     * @return int
     */
    public function getUpload(): int
    {
        return $this->upload;
    }

    /**
     * @return int
     */
    public function getUploadMax(): int
    {
        return $this->uploadMax;
    }

    /**
     * @return int
     */
    public function getUploadRemainder(): int
    {
        return $this->uploadMax - $this->upload;
    }
}
