<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\Tag\TagException;
use App\Domain\Post\Tag\TagFactory;
use App\Domain\Post\Tag\TagInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class TagFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание тега на основе массива параметров
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testTagFactoryCreateSuccess(array $data): void
    {
        $tag = TagFactory::create($data);

        self::assertEquals($data['id'], $tag->getId());
        self::assertEquals($data['name'], $tag->getName());
        self::assertEquals($data['slug'], $tag->getSlug());
        self::assertEquals($data['icon'], $tag->getIcon());
        self::assertEquals($data['preview_post_id'] ?? '', $tag->getPreviewPostId());
        self::assertEquals((bool)$data['approved'], $tag->isApproved());

        self::assertEquals(
            [
                'id'              => $data['id'],
                'name'            => $data['name'],
                'slug'            => $data['slug'],
                'icon'            => $data['icon'],
                'preview_post_id' => $data['preview_post_id'],
                'approved'        => $data['approved'],
            ],
            $tag->toArray()
        );
    }

    /**
     * Тест на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testTagFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        TagFactory::create($data);
    }

    /**
     * @dataProvider newDataProvider
     * @param string $tagName
     * @throws Exception
     */
    public function testTagFactoryCreateNewSuccess(string $tagName): void
    {
        $tag = TagFactory::createNew($tagName);

        self::assertTrue(Uuid::isValid($tag->getId()));
        self::assertEquals(mb_strtolower($tagName), $tag->getName());
        self::assertTrue(mb_strlen($tag->getSlug()) > 5);
        self::assertEquals('', $tag->getIcon());
        self::assertEquals('', $tag->getPreviewPostId());
        self::assertFalse($tag->isApproved());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
            ],
            [
                [
                    'id'              => '3bf4f5b2-d79c-45c6-b6c3-7f8dee8bf8a5',
                    'name'            => 'статьи',
                    'slug'            => 'stati',
                    'icon'            => 'icon-2.png',
                    'preview_post_id' => null,
                    'approved'        => 0,
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function failDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'              => 10,
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_ID,
            ],
            // miss name
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => [],
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_NAME,
            ],
            // name over min length
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => self::generateString(TagInterface::NAME_MIN_LENGTH - 1),
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_NAME_LENGTH . TagInterface::NAME_MIN_LENGTH . '-' . TagInterface::NAME_MAX_LENGTH,
            ],
            // name over max length
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => self::generateString(TagInterface::NAME_MAX_LENGTH + 1),
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_NAME_LENGTH . TagInterface::NAME_MIN_LENGTH . '-' . TagInterface::NAME_MAX_LENGTH,
            ],
            // miss slug
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_SLUG,
            ],
            // slug invalid type
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 10.20,
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_SLUG,
            ],
            // slug over min length
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => self::generateString(TagInterface::SLUG_MIN_LENGTH - 1),
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_SLUG_LENGTH . TagInterface::SLUG_MIN_LENGTH . '-' . TagInterface::SLUG_MAX_LENGTH,
            ],
            // slug over max length
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => self::generateString(TagInterface::SLUG_MAX_LENGTH + 1),
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_SLUG_LENGTH . TagInterface::SLUG_MIN_LENGTH . '-' . TagInterface::SLUG_MAX_LENGTH,
            ],
            // miss icon
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_ICON,
            ],
            // icon invalid type
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => null,
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_ICON,
            ],
            // icon over max length
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => self::generateString(TagInterface::ICON_MAX_LENGTH + 1),
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => 1,
                ],
                TagException::INVALID_ICON_LENGTH . TagInterface::ICON_MIN_LENGTH . '-' . TagInterface::ICON_MAX_LENGTH,
            ],
            // miss preview_post_id
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'approved'        => 1,
                ],
                TagException::INVALID_PREVIEW_POST_ID,
            ],
            // preview_post_id invalid type
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => 13,
                    'approved'        => 1,
                ],
                TagException::INVALID_PREVIEW_POST_ID,
            ],
            // miss approved
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                ],
                TagException::INVALID_APPROVED,
            ],
            // approved invalid type
            [
                [
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                    'approved'        => true,
                ],
                TagException::INVALID_APPROVED,
            ],
        ];
    }

    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                'Программирование',
            ],
            [
                'Programming',
            ],
            [
                'IT',
            ],
            [
                'AI',
            ],
        ];
    }
}
