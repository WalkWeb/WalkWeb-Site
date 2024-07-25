<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\CharacterException;
use App\Domain\Account\Character\CharacterFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CharacterFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AccountException
     * @throws AppException
     */
    public function testCharacterFactoryCreateSuccess(array $data): void
    {
        $character = CharacterFactory::create($data);

        // character
        self::assertEquals($data['character_id'], $character->getId());
        self::assertEquals($data['account_id'], $character->getAccountId());
        self::assertEquals($data['account_name'], $character->getAccountName());
        self::assertEquals($data['main_character_id'], $character->getMainCharacterId());
        self::assertEquals($data['avatar'], $character->getAvatar());
        self::assertEquals($data['season_id'], $character->getSeason()->getId());
        self::assertEquals($data['floor_id'], $character->getFloor()->getId());

        // genesis
        self::assertEquals($data['genesis_id'], $character->getGenesis()->getId());
        self::assertEquals($data['theme_id'], $character->getGenesis()->getTheme()->getId());
        self::assertEquals($data['genesis_icon'], $character->getGenesis()->getIcon());
        self::assertEquals($data['genesis_plural'], $character->getGenesis()->getPlural());
        self::assertEquals($data['genesis_single'], $character->getGenesis()->getSingle());

        // profession
        self::assertEquals($data['profession_id'], $character->getProfession()->getId());
        self::assertEquals($data['profession_icon'], $character->getProfession()->getIcon());
        self::assertEquals($data['profession_name_male'], $character->getProfession()->getNameMale());
        self::assertEquals($data['profession_name_female'], $character->getProfession()->getNameFemale());

        // level
        self::assertEquals($data['account_id'], $character->getLevel()->getAccountId());
        self::assertEquals($data['main_character_id'], $character->getLevel()->getMainCharacterId());
        self::assertEquals($data['character_id'], $character->getLevel()->getCharacterId());
        self::assertEquals($data['character_level'], $character->getLevel()->getLevel());
        self::assertEquals($data['character_exp'], $character->getLevel()->getExp());
        self::assertEquals($data['character_stat_points'], $character->getLevel()->getStatPoints());
        self::assertEquals($data['character_skill_points'], $character->getLevel()->getSkillPoints());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AccountException
     */
    public function testCharacterFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CharacterFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    // character
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    // genesis
                    'genesis_id'             => 4,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    // profession
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    // level
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
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
            // miss character_id
            [
                [
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ID,
            ],
            // character_id invalid type
            [
                [
                    'character_id'           => 123,
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ID,
            ],
            // character_id invalid uuid
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958xxx',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ID,
            ],

            // miss account_id
            [
                [
                    'account_name'           => 'AccountName',
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid type
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => 10.5,
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid uuid
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492xxx',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_ACCOUNT_ID,
            ],

            // miss main_character_id
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_MAIN_CHARACTER_ID,
            ],
            // main_character_id invalid type
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => null,
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_MAIN_CHARACTER_ID,
            ],
            // main_character_id invalid uuid
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2xxx',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_MAIN_CHARACTER_ID,
            ],
            // miss avatar
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_AVATAR,
            ],
            // avatar invalid type
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'avatar'                 => true,
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_AVATAR,
            ],
            // miss season_id
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'avatar'                 => 'avatar-1.png',
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_SEASON_ID,
            ],
            // season_id invalid type
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 'First',
                    'floor_id'               => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_SEASON_ID,
            ],
            // miss floor_id
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 'AccountName',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2123',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => null,
                    'genesis_id'             => 4,
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
                ],
                CharacterException::INVALID_FLOOR_ID,
            ],
            // miss account_name
            [
                [
                    // character
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    // genesis
                    'genesis_id'             => 4,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    // profession
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    // level
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ],
                CharacterException::INVALID_ACCOUNT_NAME,
            ],
            // account_name invalid type
            [
                [
                    // character
                    'character_id'           => '47e7495d-36d6-457d-8a1a-94575b958a18',
                    'account_id'             => '2441c114-af0c-48cb-978c-83f394492585',
                    'account_name'           => 123,
                    'main_character_id'      => '7fb2de36-e91b-4a24-ace8-ffbe235d2303',
                    'avatar'                 => 'avatar-1.png',
                    'season_id'              => 1,
                    'floor_id'               => 1,
                    // genesis
                    'genesis_id'             => 4,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'genesis-4.png',
                    'genesis_plural'         => 'Trainees',
                    'genesis_single'         => 'Intern',
                    // profession
                    'profession_id'          => 4,
                    'profession_icon'        => 'profession-4.png',
                    'profession_name_male'   => 'profession_male',
                    'profession_name_female' => 'profession_female',
                    // level
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 2,
                ],
                CharacterException::INVALID_ACCOUNT_NAME,
            ],
        ];
    }
}
