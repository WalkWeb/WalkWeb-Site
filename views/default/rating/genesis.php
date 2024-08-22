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


<?php

echo '<table>
          <tr><th>№</th><th></th><th>Профессия</th><th>Участников</th><th>Постов</th><th>Комментариев</th><th>Карма</th></tr>';
$i = 1;
foreach ($top as $genesis) {
    echo '<tr>
                <td>' . $i . '</td>
                <td><img src="' . $genesis->getIcon() . '" alt="" width="80" /></td>
                <td>' . $genesis->getName() . '</td>
                <td>' . $genesis->getMemberCount() . '</td>
                <td>' . $genesis->getPostCount() . '</td>
                <td>' . $genesis->getCommentCount() . '</td>
                <td><span class="' . $genesis->getCarmaColoClass() . '">' . $genesis->getCarmaSign() . $genesis->getCarmaCount() . '</span></td>
             </tr>';
    $i++;
}

echo '</table>';

?>

<div class="content_box">
    <div class="content_box_c">
        <table class="d_table">
            <tr>
                <td class="emptyborder"></td>
                <td colspan="3">
                    <table class="table_content">
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($top as $genesis) {
                            echo '
                                <tr class="tbc2">
                                    <td><p><span class="text_dam">' . $i . '</span></p></td>
                                    <td width="80"><img src="' . $genesis->getIcon() . '" alt="" width="80" /></td>
                                    <td><p><span class="text_dam">' . $genesis->getName() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $genesis->getMemberCount() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $genesis->getPostCount() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $genesis->getCommentCount() . '</span></p></td>
                                    <td><p><span class="' . $genesis->getCarmaColoClass() . '">' . $genesis->getCarmaSign() . $genesis->getCarmaCount() . '</span></p></td>
                                </tr>
                            ';
                            $i++;
                        }
                        ?>
                        <tr class="header">
                            <td><p><span class="tna">№</span></p></td>
                            <td><p><span class="tna"></span></p></td>
                            <td><p><span class="tna">Профессия</span></p></td>
                            <td><p><span class="tna">Участников</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="emptyborder"></td>
            </tr>
            <tr>
                <td class="bll"></td>
                <td class="bl"></td>
                <td class="bc"></td>
                <td class="br"></td>
                <td class="brr"></td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
