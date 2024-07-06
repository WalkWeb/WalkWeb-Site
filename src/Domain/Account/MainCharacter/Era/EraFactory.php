<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter\Era;

use WalkWeb\NW\AppException;

/**
 * Эпохи меняются редко (например, раз-два в год) по этому, чтобы не делать каждый раз запрос на данные по эпохе, данные
 * по ним дублируются в коде
 *
 * Эта фабрика сделана как пример, вы можете подменить её на нужную вам (с нужными данными) в контейнере
 *
 * @package Portal\Account\Character\Era
 */
class EraFactory
{
    private static array $data = [
        1 => [
            'name'   => 'Alpha',
            'actual' => true,
        ],
        2 => [
            'name'   => 'Beta',
            'actual' => false,
        ],
    ];

    /**
     * Создает объект эпохи по id
     *
     * @param int $id
     * @return EraInterface
     * @throws AppException
     */
    public static function create(int $id): EraInterface
    {
        if (!array_key_exists($id, self::$data)) {
            throw new AppException(EraException::UNKNOWN_ERA . ": $id");
        }

        return new Era($id, self::$data[$id]['name'], self::$data[$id]['actual']);
    }
}
