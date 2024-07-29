<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_29_11_10_03_22
{
    /**
     * Названия игровых профессий на английском для мужского и женского пола пишутся одинаково, но в русском языке
     * некоторые различаются. Чтобы реализовать это - названиям которые различаются добавляется префикс, и уже через
     * транслятор будут отображаться корректные названия без префикса, в нужном роде.
     *
     * Профессии, названия которых отличаются для разных полов:
     *
     * Priest: Жрец / Жрица
     * Templar: Хранитель / Хранительница
     * Caster: Заклинатель / Заклинательница
     * Destroyer: Разрушитель / Разрушительница
     * Treasure Hunter: Искатель сокровищ / Искательница сокровищ
     * Hermit: Отшельник / Отшельница
     * Avenger: Мститель / Мстительница
     *
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query("
            INSERT INTO `professions`(`id`, `genesis_id`, `icon`, `name_male`, `name_female`) VALUES
            (7,  7, '', 'Paladin',            'Paladin'),
            (8,  7, '', 'Assassin',           'Assassin'),
            (9,  7, '', 'Ranger',             'Ranger'),
            (10, 7, '', 'Elemental Mage',     'Elemental Mage'),
            (11, 7, '', 'priest_m',           'priest_f'),
                                                                                                     
            (12, 8, '', 'templar_m',          'templar_f'),
            (13, 8, '', 'Day Hunter',         'Day Hunter'),
            (14, 8, '', 'Night Hunter',       'Night Hunter'),
            (15, 8, '', 'caster_m',           'caster_f'),
            (16, 8, '', 'Oracle',             'Oracle'),
                                                                                                     
            (17, 9, '', 'Titan',              'Titan'),
            (18, 9, '', 'destroyer_m',        'destroyer_f'),
            (19, 9, '', 'Berserk',            'Berserk'),
            (20, 9, '', 'Shaman',             'Shaman'),
            (21, 9, '', 'Battle Mage',        'Battle Mage'),
                                                                                                     
            (22, 10, '', 'Guardian',          'Guardian'),
            (23, 10, '', 'treasure_hunter_m', 'treasure_hunter_f'),
            (24, 10, '', 'Crossbowman',       'Crossbowman'),
            (25, 10, '', 'Alchemist',         'Alchemist'),
            (26, 10, '', 'hermit_m',          'hermit_f'),
                                                                                                     
            (27, 11, '', 'Archangel',         'Archangel'),
            (28, 11, '', 'Malachim',          'Malachim'),
            (29, 11, '', 'Phoenix',           'Phoenix'),
            (30, 11, '', 'Seraphim',          'Seraphim'),
            (31, 11, '', 'Arelim',            'Arelim'),
                                                                                                     
            (32, 12, '', 'Hell Knight',       'Hell Knight'),
            (33, 12, '', 'avenger_m',         'avenger_f'),
            (34, 12, '', 'Archont',           'Archont'),
            (35, 12, '', 'Soul Killer',       'Soul Killer'),
            (36, 12, '', 'Succubus',          'Incubus');
        ");

        echo "Added profession variables\n";
    }
}
