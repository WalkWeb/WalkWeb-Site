<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_29_10_54_45_17
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query("
            INSERT INTO `genesis`(`id`, `theme_id`, `icon`, `plural`, `single`) VALUES
            (7, 2,  '/icon/races/human.png',   'People',  'Human'),
            (8, 2,  '/icon/races/elf.png',     'Elves',   'Elf'),
            (9, 2,  '/icon/races/orc.png',     'Orcs',    'Orc'),
            (10, 2, '/icon/races/dwarf.png',   'Dwarfs',  'Dwarf'),
            (11, 2, '/icon/races/angels.png',  'Angels',  'Angel'),
            (12, 2, '/icon/races/demons.png',  'Demons',  'Demon'),
            (13, 2, '',                        'Animals', 'Animal'),
            (14, 2, '',                        'Undead',  'Undead'),
            (15, 2, '',                        'Golems',  'Golem');
        ");

        echo "Added genesis variables\n";
    }
}
