<?php

declare(strict_types=1);

namespace Test\src\Domain\Image;

use App\Domain\Image\ImageException;
use App\Domain\Image\ImageFactory;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Loader\Image as NWImage;

class ImageFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testImageFactoryCreateSuccess(array $data): void
    {
        $image = ImageFactory::create($data);

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
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testImageFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        ImageFactory::create($data);
    }

    /**
     * @dataProvider createNewDataProvider
     * @param NWImage $nwImage
     * @throws AppException
     */
    public function testImageFactoryCreateNew(NWImage $nwImage): void
    {
        $user = $this->createUser();
        $image = ImageFactory::createNew($nwImage, $user);

        self::assertTrue(Uuid::isValid($image->getId()));
        self::assertEquals($user->getId(), $image->getAccountId());
        self::assertEquals($nwImage->getName(), $image->getName());
        self::assertEquals($nwImage->getFilePath(), $image->getFilePath());
        self::assertEquals($nwImage->getSize(), $image->getSize());
        self::assertEquals($nwImage->getWidth(), $image->getWidth());
        self::assertEquals($nwImage->getHeight(), $image->getHeight());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'         => 123,
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f0',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ID,
            ],
            // miss account_id
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => null,
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid uuid
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410xx',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_ACCOUNT_ID,
            ],
            // miss name
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => ['image_name'],
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_NAME,
            ],
            // miss file_path
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_FILE_PATH,
            ],
            // file_path invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => true,
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_FILE_PATH,
            ],
            // miss size
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_SIZE,
            ],
            // size invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 'full',
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_SIZE,
            ],
            // miss width
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_WIDTH,
            ],
            // width invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => null,
                    'height'     => 768,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_WIDTH,
            ],
            // miss height
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_HEIGHT,
            ],
            // height invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => '768',
                    'created_at' => '2020-12-25 21:00:00',
                ],
                ImageException::INVALID_HEIGHT,
            ],
            // miss created_at
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                ],
                ImageException::INVALID_CREATED_AT,
            ],
            // created_at invalid type
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => 123,
                ],
                ImageException::INVALID_CREATED_AT,
            ],
            // created_at invalid date
            [
                [
                    'id'         => '2d55de84-d38c-42c4-a468-5046a34f05d6',
                    'account_id' => 'f54a18eb-d5af-47e1-bf31-e729b8d3f410',
                    'name'       => 'image_name',
                    'file_path'  => 'path/to/image_name.png',
                    'size'       => 23432,
                    'width'      => 1024,
                    'height'     => 768,
                    'created_at' => '2020-12-99 21:00:00',
                ],
                ImageException::INVALID_CREATED_AT,
            ],
        ];
    }

    /**
     * @return array
     */
    public function createNewDataProvider(): array
    {
        return [
            [
                new NWImage(
                    'name-1',
                    'png',
                    10000,
                    300,
                    500,
                    'absolute_file_path',
                    'file_path',
                ),
            ],
        ];
    }
}
