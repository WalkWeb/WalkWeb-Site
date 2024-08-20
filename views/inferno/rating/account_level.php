<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Самые высокоуровневые аккаунты';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.rating.account_level: miss accounts');
}

?>

<p class="text" align="center">
    Уровень аккаунта |
    <a href="#" title="" class="osnova">Карма</a> |
    <a href="#" title="" class="osnova">Расы</a>
</p>

<!-- TODO Доработать визуально: добавить иконки топ-3, добавить им цветовое выделение имен + размер, добавить другие стили для имен аккаунтов (не простые ссылки) и прочие мелочи -->

<div class="content_box">
    <div class="content_box_c">
        <table class="d_table">
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
                    Самые высокоуровневые аккаунты
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
                        <tr class="header">
                            <td><p><span class="tna">№</span></p></td>
                            <td><p><span class="tna">Аватар</span></p></td>
                            <td><p><span class="tna">Уровень</span></p></td>
                            <td><p><span class="tna">Опыт</span></p></td>
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
                        <?php

                        if (count($accounts) > 0) {
                            $i = 1;
                            foreach ($accounts as $account) {
                                echo '
                                <tr class="tbc2">
                                    <td><p><span class="text_dam">' . $i . '</span></p></td>
                                    <td class="table_td_ava" style="background-image: url(' . $account->getAvatar() . ');"><a href="/u/' . $account->getName() . '" class="full"></a></td>
                                    <td><p><span class="text_dam">' . $account->getLevel() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $account->getExp() . '</span></p></td>
                                    <td><p><a href="/u/'. $account->getName() . '" title="" class="acc_info_name ">' . $account->getName() . '</a></p></td>
                                    <td><p><span class="text_dam">#</span></p></td>
                                    <td><p><span class="text_dam">#</span></p></td>
                                    <td><p><span class="' . $account->getCarmaColoClass() . '">' . $account->getCarmaSign() . $account->getCarma() . '</span></p></td>
                                </tr>
                            ';
                                $i++;
                            }
                        } else {
                            echo '<tr class="tbc2"><td colspan="8">Нет аккаунтов</td></tr>';
                        }

                        ?>
                        <tr class="header">
                            <td><p><span class="tna">№</span></p></td>
                            <td><p><span class="tna">Аватар</span></p></td>
                            <td><p><span class="tna">Уровень</span></p></td>
                            <td><p><span class="tna">Опыт</span></p></td>
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
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
        </table>
    </div>
</div>
