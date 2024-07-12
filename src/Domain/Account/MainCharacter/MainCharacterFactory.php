<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter;

use App\Domain\Account\MainCharacter\Era\EraFactory;
use App\Domain\Account\MainCharacter\Level\MainLevel;
use App\Domain\Account\MainCharacter\Level\LevelFactory;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class MainCharacterFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return MainCharacterInterface
     * @throws AppException
     */
    public static function create(array $data, SendNoticeActionInterface $sendNoticeAction): MainCharacterInterface
    {
        return new MainCharacter(
            self::uuid($data, 'character_id', MainCharacterException::INVALID_ID),
            self::uuid($data, 'account_id', MainCharacterException::INVALID_ACCOUNT_ID),
            EraFactory::create(self::int($data, 'era_id', MainCharacterException::INVALID_ERA_ID)),
            LevelFactory::create($data, $sendNoticeAction),
            self::int($data, 'energy_bonus', MainCharacterException::INVALID_ENERGY_BONUS),
            self::int($data, 'upload_bonus', MainCharacterException::INVALID_UPLOAD_BONUS)
        );
    }

    /**
     * @param string $accountId
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return MainCharacterInterface
     * @throws AppException
     */
    public static function createNew(string $accountId, SendNoticeActionInterface $sendNoticeAction): MainCharacterInterface
    {
        $id = Uuid::uuid4()->toString();

        if (!Uuid::isValid($accountId)) {
            throw new AppException(MainCharacterException::INVALID_ACCOUNT_ID_VALUE);
        }

        return new MainCharacter(
            $id,
            $accountId,
            EraFactory::create(ACTIVE_ERA),
            new MainLevel($accountId, $id, 1, 0, 0, $sendNoticeAction),
            0,
            0
        );
    }
}
