<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_22_16_25_83
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `avatars` (
                `id`         MEDIUMINT UNSIGNED PRIMARY KEY,
                `genesis_id` TINYINT UNSIGNED NOT NULL,
                `floor_id`   TINYINT UNSIGNED NOT NULL,
                `origin_rul` VARCHAR(90) NOT NULL,
                `small_rul`  VARCHAR(90) NOT NULL,
                FOREIGN KEY (`genesis_id`) REFERENCES `genesis`(`id`),
                FOREIGN KEY (`floor_id`) REFERENCES `floors`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `avatars`(`id`, `genesis_id`, `floor_id`, `origin_rul`, `small_rul`) VALUES
            (1, 1, 1, '/img/avatars/it/analyst/male/01.jpg', '/img/avatars/it/analyst/male/01s.jpg'),
            (2, 1, 1, '/img/avatars/it/analyst/male/02.jpg', '/img/avatars/it/analyst/male/02s.jpg'),
            (3, 1, 1, '/img/avatars/it/analyst/male/03.jpg', '/img/avatars/it/analyst/male/03s.jpg'),
            (4, 1, 1, '/img/avatars/it/analyst/male/04.jpg', '/img/avatars/it/analyst/male/04s.jpg'),
            (5, 1, 1, '/img/avatars/it/analyst/male/05.jpg', '/img/avatars/it/analyst/male/05s.jpg'),
            (6, 1, 1, '/img/avatars/it/analyst/male/06.jpg', '/img/avatars/it/analyst/male/06s.jpg'),
                                                                                                    
            (7, 1, 2, '/img/avatars/it/analyst/female/01.jpg', '/img/avatars/it/analyst/female/01s.jpg'),
            (8, 1, 2, '/img/avatars/it/analyst/female/02.jpg', '/img/avatars/it/analyst/female/02s.jpg'),
            (9, 1, 2, '/img/avatars/it/analyst/female/03.jpg', '/img/avatars/it/analyst/female/03s.jpg'),
            (10, 1, 2, '/img/avatars/it/analyst/female/04.jpg', '/img/avatars/it/analyst/female/04s.jpg'),
            (11, 1, 2, '/img/avatars/it/analyst/female/05.jpg', '/img/avatars/it/analyst/female/05s.jpg'),
            (12, 1, 2, '/img/avatars/it/analyst/female/06.jpg', '/img/avatars/it/analyst/female/06s.jpg'),

            (13, 2, 1, '/img/avatars/it/designer/male/01.jpg', '/img/avatars/it/designer/male/01s.jpg'),
            (14, 2, 1, '/img/avatars/it/designer/male/02.jpg', '/img/avatars/it/designer/male/02s.jpg'),
            (15, 2, 1, '/img/avatars/it/designer/male/03.jpg', '/img/avatars/it/designer/male/03s.jpg'),
            (16, 2, 1, '/img/avatars/it/designer/male/04.jpg', '/img/avatars/it/designer/male/04s.jpg'),
            (17, 2, 1, '/img/avatars/it/designer/male/05.jpg', '/img/avatars/it/designer/male/05s.jpg'),
            (18, 2, 1, '/img/avatars/it/designer/male/06.jpg', '/img/avatars/it/designer/male/06s.jpg'),
                                                                                                    
            (19, 2, 2, '/img/avatars/it/designer/female/01.jpg', '/img/avatars/it/designer/female/01s.jpg'),
            (20, 2, 2, '/img/avatars/it/designer/female/02.jpg', '/img/avatars/it/designer/female/02s.jpg'),
            (21, 2, 2, '/img/avatars/it/designer/female/03.jpg', '/img/avatars/it/designer/female/03s.jpg'),
            (22, 2, 2, '/img/avatars/it/designer/female/04.jpg', '/img/avatars/it/designer/female/04s.jpg'),
            (23, 2, 2, '/img/avatars/it/designer/female/05.jpg', '/img/avatars/it/designer/female/05s.jpg'),
            (24, 2, 2, '/img/avatars/it/designer/female/06.jpg', '/img/avatars/it/designer/female/06s.jpg'),
                                                                                                    
            (25, 3, 1, '/img/avatars/it/devops/male/01.jpg', '/img/avatars/it/devops/male/01s.jpg'),
            (26, 3, 1, '/img/avatars/it/devops/male/02.jpg', '/img/avatars/it/devops/male/02s.jpg'),
            (27, 3, 1, '/img/avatars/it/devops/male/03.jpg', '/img/avatars/it/devops/male/03s.jpg'),
            (28, 3, 1, '/img/avatars/it/devops/male/04.jpg', '/img/avatars/it/devops/male/04s.jpg'),
            (29, 3, 1, '/img/avatars/it/devops/male/05.jpg', '/img/avatars/it/devops/male/05s.jpg'),
            (30, 3, 1, '/img/avatars/it/devops/male/06.jpg', '/img/avatars/it/devops/male/06s.jpg'),
                                                                                                    
            (31, 3, 2, '/img/avatars/it/devops/female/01.jpg', '/img/avatars/it/devops/female/01s.jpg'),
            (32, 3, 2, '/img/avatars/it/devops/female/02.jpg', '/img/avatars/it/devops/female/02s.jpg'),
            (33, 3, 2, '/img/avatars/it/devops/female/03.jpg', '/img/avatars/it/devops/female/03s.jpg'),
            (34, 3, 2, '/img/avatars/it/devops/female/04.jpg', '/img/avatars/it/devops/female/04s.jpg'),
            (35, 3, 2, '/img/avatars/it/devops/female/05.jpg', '/img/avatars/it/devops/female/05s.jpg'),
            (36, 3, 2, '/img/avatars/it/devops/female/06.jpg', '/img/avatars/it/devops/female/06s.jpg'),
                                                                                                    
            (37, 4, 1, '/img/avatars/it/intern/male/01.jpg', '/img/avatars/it/intern/male/01s.jpg'),
            (38, 4, 1, '/img/avatars/it/intern/male/02.jpg', '/img/avatars/it/intern/male/02s.jpg'),
            (39, 4, 1, '/img/avatars/it/intern/male/03.jpg', '/img/avatars/it/intern/male/03s.jpg'),
            (40, 4, 1, '/img/avatars/it/intern/male/04.jpg', '/img/avatars/it/intern/male/04s.jpg'),
            (41, 4, 1, '/img/avatars/it/intern/male/05.jpg', '/img/avatars/it/intern/male/05s.jpg'),
            (42, 4, 1, '/img/avatars/it/intern/male/06.jpg', '/img/avatars/it/intern/male/06s.jpg'),
                                                                                                    
            (43, 4, 2, '/img/avatars/it/intern/female/01.jpg', '/img/avatars/it/intern/female/01s.jpg'),
            (44, 4, 2, '/img/avatars/it/intern/female/02.jpg', '/img/avatars/it/intern/female/02s.jpg'),
            (45, 4, 2, '/img/avatars/it/intern/female/03.jpg', '/img/avatars/it/intern/female/03s.jpg'),
            (46, 4, 2, '/img/avatars/it/intern/female/04.jpg', '/img/avatars/it/intern/female/04s.jpg'),
            (47, 4, 2, '/img/avatars/it/intern/female/05.jpg', '/img/avatars/it/intern/female/05s.jpg'),
            (48, 4, 2, '/img/avatars/it/intern/female/06.jpg', '/img/avatars/it/intern/female/06s.jpg'),

            (49, 5, 1, '/img/avatars/it/programmer/male/01.jpg', '/img/avatars/it/programmer/male/01s.jpg'),
            (50, 5, 1, '/img/avatars/it/programmer/male/02.jpg', '/img/avatars/it/programmer/male/02s.jpg'),
            (51, 5, 1, '/img/avatars/it/programmer/male/03.jpg', '/img/avatars/it/programmer/male/03s.jpg'),
            (52, 5, 1, '/img/avatars/it/programmer/male/04.jpg', '/img/avatars/it/programmer/male/04s.jpg'),
            (53, 5, 1, '/img/avatars/it/programmer/male/05.jpg', '/img/avatars/it/programmer/male/05s.jpg'),
            (54, 5, 1, '/img/avatars/it/programmer/male/06.jpg', '/img/avatars/it/programmer/male/06s.jpg'),
                                                                                                    
            (55, 5, 2, '/img/avatars/it/programmer/female/01.jpg', '/img/avatars/it/programmer/female/01s.jpg'),
            (56, 5, 2, '/img/avatars/it/programmer/female/02.jpg', '/img/avatars/it/programmer/female/02s.jpg'),
            (57, 5, 2, '/img/avatars/it/programmer/female/03.jpg', '/img/avatars/it/programmer/female/03s.jpg'),
            (58, 5, 2, '/img/avatars/it/programmer/female/04.jpg', '/img/avatars/it/programmer/female/04s.jpg'),
            (59, 5, 2, '/img/avatars/it/programmer/female/05.jpg', '/img/avatars/it/programmer/female/05s.jpg'),
            (60, 5, 2, '/img/avatars/it/programmer/female/06.jpg', '/img/avatars/it/programmer/female/06s.jpg'),
                                                                                                    
            (61, 6, 1, '/img/avatars/it/manager/male/01.jpg', '/img/avatars/it/manager/male/01s.jpg'),
            (62, 6, 1, '/img/avatars/it/manager/male/02.jpg', '/img/avatars/it/manager/male/02s.jpg'),
            (63, 6, 1, '/img/avatars/it/manager/male/03.jpg', '/img/avatars/it/manager/male/03s.jpg'),
            (64, 6, 1, '/img/avatars/it/manager/male/04.jpg', '/img/avatars/it/manager/male/04s.jpg'),
            (65, 6, 1, '/img/avatars/it/manager/male/05.jpg', '/img/avatars/it/manager/male/05s.jpg'),
            (66, 6, 1, '/img/avatars/it/manager/male/06.jpg', '/img/avatars/it/manager/male/06s.jpg'),
                                                                                                    
            (67, 6, 2, '/img/avatars/it/manager/female/01.jpg', '/img/avatars/it/manager/female/01s.jpg'),
            (68, 6, 2, '/img/avatars/it/manager/female/02.jpg', '/img/avatars/it/manager/female/02s.jpg'),
            (69, 6, 2, '/img/avatars/it/manager/female/03.jpg', '/img/avatars/it/manager/female/03s.jpg'),
            (70, 6, 2, '/img/avatars/it/manager/female/04.jpg', '/img/avatars/it/manager/female/04s.jpg'),
            (71, 6, 2, '/img/avatars/it/manager/female/05.jpg', '/img/avatars/it/manager/female/05s.jpg'),
            (72, 6, 2, '/img/avatars/it/manager/female/06.jpg', '/img/avatars/it/manager/female/06s.jpg');                                                                                
        ");

        echo "Added avatars\n";
    }
}
