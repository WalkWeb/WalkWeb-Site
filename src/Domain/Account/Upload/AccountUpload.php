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
     * @return float
     */
    public function getUploadMb(): float
    {
        return round($this->upload / 1048576, 1);
    }

    /**
     * @return int
     */
    public function getUploadMax(): int
    {
        return $this->uploadMax;
    }

    /**
     * @return float
     */
    public function getUploadMaxMb(): float
    {
        return round($this->uploadMax / 1048576, 1);
    }

    /**
     * @return int
     */
    public function getUploadRemainder(): int
    {
        return $this->uploadMax - $this->upload;
    }

    /**
     * @return int
     */
    public function getUploadBarWeight(): int
    {
        return (int)round($this->upload / $this->uploadMax * 100);
    }
}
