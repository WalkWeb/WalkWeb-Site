<?php

use App\Domain\Rating\DTO\Genesis\GenesisRatingCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Рейтинг профессий';

if (empty($top) || !($top instanceof GenesisRatingCollection)) {
    throw new AppException('view.rating.genesis: miss $top');
}

?>

<p class="text center">
    <a href="/top/account/level" title="" class="osnova">Уровень</a> |
    <a href="/top/account/carma" title="" class="osnova">Карма</a> |
    Профессии |
    <a href="#" title="" class="osnova">Сообщества</a> |
    <a href="#" title="" class="osnova">Языки программирования</a>
</p>

<table>
    <tr>
        <th>№</th>
        <th>Профессия</th>
        <th>Участников</th>
        <th>Постов</th>
        <th>Комментариев</th>
        <th>Карма</th>
    </tr>
    <?php
    $i = 1;
    foreach ($top as $genesis) {
        echo '<tr>
                    <td>' . $i . '</td>
                    <td>' . $genesis->getName() . '</td>
                    <td>' . $genesis->getMemberCount() . '</td>
                    <td>' . $genesis->getPostCount() . '</td>
                    <td>' . $genesis->getCommentCount() . '</td>
                    <td><span class="' . $genesis->getCarmaColoClass() . '">' . $genesis->getCarmaSign() . $genesis->getCarmaCount() . '</span></td>
                 </tr>';
        $i++;
    }
    ?>
</table>
