<?php

use App\Domain\Rating\DTO\Genesis\GenesisRatingCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Рейтинг рас';

if (empty($top) || !($top instanceof GenesisRatingCollection)) {
    throw new AppException('view.rating.genesis: miss $top');
}

?>

<p class="text center">
    <a href="/top/account/level" title="" class="osnova">Уровень</a> |
    <a href="/top/account/carma" title="" class="osnova">Карма</a> |
    <a href="#" title="" class="osnova">Сообщества</a> |
    <a href="#" title="" class="osnova">Игры</a> |
    Расы
</p>

<div class="content_box">
    <div class="content_box_c">

        <table class="d_table">
            <tbody>
            <tr>
                <td class="btll"></td>
                <td class="btl"></td>
                <td class="btc"></td>
                <td class="btr"></td>
                <td class="btrr"></td>
            </tr>
            <tr>
                <td class="blt"></td>
                <td class="tl"></td>
                <td class="tc" rowspan="2">
                    Рейтинг рас
                </td>
                <td class="tr"></td>
                <td class="brt"></td>
            </tr>
            <tr>
                <td class="emptyborder"></td>
                <td class="tlborder">&nbsp;</td>
                <td class="trborder">&nbsp;</td>
                <td class="emptyborder"></td>
            </tr>
            <tr>
                <td class="emptyborder"></td>
                <td colspan="3">
                    <table class="table_content">
                        <tbody>
                        <tr class="header">
                            <td><p><span class="tna">№</span></p></td>
                            <td><p><span class="tna">Раса</span></p></td>
                            <td><p><span class="tna">Численность</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
                        <?php
                        $i = 1;
                        foreach ($top as $genesis) {
                            echo '
                                <tr class="tbc2">
                                    <td><p><span class="text_dam">' . $i . '</span></p></td>
                                    <td><p><span class="text_dam"><img src="' . $genesis->getIcon() . '" alt="" width="80" /><br />' . $genesis->getName() . '</span></p></td>
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
                            <td><p><span class="tna">Раса</span></p></td>
                            <td><p><span class="tna">Численность</span></p></td>
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
