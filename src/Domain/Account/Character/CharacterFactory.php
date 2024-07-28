<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use App\Domain\Account\AccountInterface;
use App\Domain\Account\Character\Avatar\AvatarInterface;
use App\Domain\Account\Character\Genesis\GenesisFactory;
use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Character\Level\Level;
use App\Domain\Account\Character\Level\LevelFactory;
use App\Domain\Account\Character\Profession\ProfessionFactory;
use App\Domain\Account\Character\Profession\ProfessionInterface;
use App\Domain\Account\Character\Season\Season;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\Floor\Floor;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CharacterFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return CharacterInterface
     * @throws AppException
     */
    public static function create(array $data): CharacterInterface
    {
        return new Character(
            self::uuid($data, 'character_id', CharacterException::INVALID_ID),
            self::uuid($data, 'account_id', CharacterException::INVALID_ACCOUNT_ID),
            self::string($data, 'account_name', CharacterException::INVALID_ACCOUNT_NAME),
            self::uuid($data, 'main_character_id', CharacterException::INVALID_MAIN_CHARACTER_ID),
            self::int($data, 'avatar_id', CharacterException::INVALID_AVATAR_ID),
            self::string($data, 'avatar', CharacterException::INVALID_AVATAR),
            new Season(self::int($data, 'season_id', CharacterException::INVALID_SEASON_ID)),
            GenesisFactory::create($data),
            ProfessionFactory::create($data),
            new Floor(self::int($data, 'floor_id', CharacterException::INVALID_FLOOR_ID)),
            LevelFactory::create($data),
        );
    }

    /**
     * @param CreateAccountRequest $request
     * @param AccountInterface $account
     * @param MainCharacterInterface $mainCharacter
     * @param GenesisInterface $genesis
     * @param ProfessionInterface $profession
     * @param AvatarInterface $avatar
     * @return CharacterInterface
     * @throws AppException
     */
    public static function createNew(
        CreateAccountRequest $request,
        AccountInterface $account,
        MainCharacterInterface $mainCharacter,
        GenesisInterface $genesis,
        ProfessionInterface $profession,
        AvatarInterface $avatar
    ): CharacterInterface
    {
        $id = Uuid::uuid4()->toString();

        return new Character(
            $id,
            $account->getId(),
            $account->getName(),
            $mainCharacter->getId(),
            $avatar->getId(),
            $avatar->getOriginUrl(),
            new Season(ACTIVE_SEASON),
            $genesis,
            $profession,
            new Floor($request->getFloor()),
            new Level($account->getId(), $mainCharacter->getId(), $id, 1, 0, 0, 0)
        );
    }
}
