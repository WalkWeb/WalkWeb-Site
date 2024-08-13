<?php

declare(strict_types=1);

namespace Test\src\Domain\Image;

use App\Domain\Image\ImageFactory;
use App\Domain\Image\ImageRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class ImageRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getSuccessDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testImageRepositoryGetSuccess(array $data): void
    {
        $image = $this->getRepository()->get($data['id']);

        self::assertEquals($data['id'], $image->getId());
        self::assertEquals($data['account_id'], $image->getAccountId());
        self::assertEquals($data['name'], $image->getName());
        self::assertEquals($data['file_path'], $image->getFilePath());
        self::assertEquals($data['size'], $image->getSize());
        self::assertEquals($data['width'], $image->getWidth());
        self::assertEquals($data['height'], $image->getHeight());
        self::assertEquals($data['created_at'], $image->getCreatedAt()->format(self::DATE_FORMAT));
    }

    /**
     * @throws AppException
     */
    public function testImageRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get('1b55cb4c-f16d-4060-b332-ef68ae550169'));
    }

    /**
     * @dataProvider addDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testImageRepositoryAdd(array $data): void
    {
        self::assertCount(1, $this->getAllImagesData());

        $image = ImageFactory::create($data);

        $this->getRepository()->add($image);

        $image = $this->getRepository()->get($data['id']);

        self::assertEquals($data['id'], $image->getId());
        self::assertEquals($data['account_id'], $image->getAccountId());
        self::assertEquals($data['name'], $image->getName());
        self::assertEquals($data['file_path'], $image->getFilePath());
        self::assertEquals($data['size'], $image->getSize());
        self::assertEquals($data['width'], $image->getWidth());
        self::assertEquals($data['height'], $image->getHeight());
        self::assertEquals($data['created_at'], $image->getCreatedAt()->format(self::DATE_FORMAT));

        self::assertCount(2, $this->getAllImagesData());
    }

    /**
     * @return array
     */
    public function getSuccessDataProvider(): array
    {
        return [
            [
                [
                    'id'         => '11874a06-f9d6-4d98-ac0c-14f7c4678801',
                    'account_id' => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'name'       => 'image_example',
                    'file_path'  => 'file_path/image_example.png',
                    'size'       => 12345,
                    'width'      => 1000,
                    'height'     => 800,
                    'created_at' => '2024-06-15 16:30:00',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function addDataProvider(): array
    {
        return [
            [
                [
                    'id'         => '2220b957-d446-4519-8bcf-4c3190c8bb2d',
                    'account_id' => '1e3a3b27-12da-4c73-a3a7-b83092705b02',
                    'name'       => 'image_example-2',
                    'file_path'  => 'file_path/image_example-2.png',
                    'size'       => 33333,
                    'width'      => 1400,
                    'height'     => 700,
                    'created_at' => '2024-06-17 17:00:00',
                ],
            ],
        ];
    }

    /**
     * @return ImageRepository
     * @throws AppException
     */
    private function getRepository(): ImageRepository
    {
        return new ImageRepository(self::getContainer());
    }

    /**
     * @return array
     * @throws AppException
     */
    private function getAllImagesData(): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query('SELECT * FROM `images`');
    }
}
