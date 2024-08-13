<?php

declare(strict_types=1);

namespace App\Domain\Image;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class ImageRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return ImageInterface|null
     * @throws AppException
     */
    public function get(string $id): ?ImageInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id`, `account_id`, `name`, `file_path`, `size`, `width`, `height`, `created_at` FROM `images` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            return null;
        }

        return ImageFactory::create($data);
    }

    /**
     * @param ImageInterface $image
     * @throws AppException
     */
    public function add(ImageInterface $image): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `images` (`id`, `account_id`, `name`, `file_path`, `size`, `width`, `height`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $image->getId()],
                ['type' => 's', 'value' => $image->getAccountId()],
                ['type' => 's', 'value' => $image->getName()],
                ['type' => 's', 'value' => $image->getFilePath()],
                ['type' => 'i', 'value' => $image->getSize()],
                ['type' => 'i', 'value' => $image->getWidth()],
                ['type' => 'i', 'value' => $image->getHeight()],
                ['type' => 's', 'value' => $image->getCreatedAt()->format('Y-m-d H:i:s')],
            ]
        );
    }
}
