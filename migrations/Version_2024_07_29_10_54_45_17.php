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
            INSERT INTO `genesis`(`id`, `theme_id`, `icon`, `icon_small`, `plural`, `single`, `playlable`) VALUES
            (7, 2,  '/icon/races/human.png',  '', 'People',  'Human',  1),
            (8, 2,  '/icon/races/elf.png',    '', 'Elves',   'Elf',    1),
            (9, 2,  '/icon/races/orc.png',    '', 'Orcs',    'Orc',    1),
            (10, 2, '/icon/races/dwarf.png',  '', 'Dwarfs',  'Dwarf',  1),
            (11, 2, '/icon/races/angels.png', '', 'Angels',  'Angel',  1),
            (12, 2, '/icon/races/demons.png', '', 'Demons',  'Demon',  1),
            (13, 2, '',                       '', 'Animals', 'Animal', 0),
            (14, 2, '',                       '', 'Undead',  'Undead', 0),
            (15, 2, '',                       '', 'Golems',  'Golem',  0);
        ");

        echo "Added genesis variables\n";
    }
}
