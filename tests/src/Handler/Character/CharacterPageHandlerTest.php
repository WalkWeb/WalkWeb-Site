<?php

declare(strict_types=1);

namespace Test\src\Handler\Character;

use App\Domain\Account\Character\CharacterFactory;
use App\Domain\Account\Character\CharacterInterface;
use App\Handler\Character\CharacterPageHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CharacterPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCharacterPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c01']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Analyst/', $response->getBody());
        self::assertMatchesRegularExpression('/Default/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCharacterPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c33']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Персонаж не найден/', $response->getBody());
    }

    /**
     * @dataProvider backgroundDataProvider
     * @param CharacterInterface $character
     * @param string $backgroundImage
     * @throws AppException
     */
    public function testCharacterPageHandlerGetInventoryBackground(CharacterInterface $character, string $backgroundImage): void
    {
        $handler = new CharacterPageHandler(self::getContainer());
        self::assertEquals($backgroundImage, $handler->getInventoryBackground($character));
    }

    /**
     * @return CharacterInterface[]
     * @throws AppException
     */
    public function backgroundDataProvider(): array
    {
        return [
            [
                CharacterFactory::create([
                    'genesis_id'             => 7,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_human_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 7,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_human_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 8,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_elf_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 8,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_elf_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 9,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_orc_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 9,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_orc_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 10,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_dwarf_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 10,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_dwarf_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 11,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_angel_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 11,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_angel_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 12,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_demon_male.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 12,
                    'floor_id'               => 2,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '/img/inventory/bg_demon_female.jpg',
            ],
            [
                CharacterFactory::create([
                    'genesis_id'             => 1,
                    'floor_id'               => 1,
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar_id'              => 23,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ]),
                '',
            ],
        ];
    }
}
