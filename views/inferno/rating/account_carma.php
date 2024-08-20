<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Пользователи с наибольшей кармой';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.rating.account_level: miss accounts');
}

?>

<p class="text center">
    <a href="/top/account/level" title="" class="osnova">Уровень</a> |
    Карма |
    <a href="#" title="" class="osnova">Сообщества</a> |
    <a href="#" title="" class="osnova">Игры</a> |
    <a href="#" title="" class="osnova">Расы</a>
</p>

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
                    Пользователи с наибольшей кармой
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
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Опыт</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
                        <?php

                        if (count($accounts) > 0) {
                            $i = 1;
                            foreach ($accounts as $account) {

                                switch ($i) {
                                    case 1:
                                        $td = '<td class="table_td_rating"><div class="first_icon"><div class="first_sign"></div></div></td>';
                                        break;
                                    case 2:
                                        $td = '<td class="table_td_rating"><div class="second_icon"><div class="second_sign"></div></div></td>';
                                        break;
                                    case 3:
                                        $td = '<td class="table_td_rating"><div class="third_icon"><div class="third_sign"></div></div></td>';
                                        break;
                                    case 4:
                                        $td = '<td class="table_td_rating"><div class="fourth_icon"><div class="fourth_sign"></div></div></td>';
                                        break;
                                    case 5:
                                        $td = '<td class="table_td_rating"><div class="fifth_icon"><div class="fifth_sign"></div></div></td>';
                                        break;
                                    default:
                                        $td = '<td><p><span class="blue very_big">' . $i . '</span></p></td>';
                                }

                                echo '
                                <tr class="tbc2">
                                    ' . $td . '
                                    <td class="table_td_ava" style="background-image: url(' . $account->getAvatar() . ');"><a href="/u/' . $account->getName() . '" class="full"></a></td>
                                    <td><p><span class="rich_yellow very_big">' . $account->getLevel() . '</span></p></td>
                                    <td><p><a href="/u/'. $account->getName() . '" title="" class="rating_name">' . $account->getName() . '</a></p></td>
                                    <td><p>' . $account->getExp() . '</p></td>
                                    <td><p>' . $account->getPostCount() . '</p></td>
                                    <td><p>' . $account->getCommentCount() . '</p></td>
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
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Опыт</span></p></td>
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
