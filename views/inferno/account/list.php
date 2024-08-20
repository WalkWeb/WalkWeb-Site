<?php

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Пользователи портала';

if (empty($accounts) || !($accounts instanceof AccountCollection)) {
    throw new AppException('view.account.list: miss accounts');
}

if (empty($total) || !is_int($total)) {
    throw new AppException('view.account.list: miss total');
}

$pagination = $pagination ?? '';

?>

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
                    Пользователи
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
                            <td><p><span class="tna">Аватар</span></p></td>
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Уровень</span></p></td>
                            <td><p><span class="tna">Группа</span></p></td>
                            <td><p><span class="tna">Статус</span></p></td>
                            <td><p><span class="tna">Постов</span></p></td>
                            <td><p><span class="tna">Комментариев</span></p></td>
                            <td><p><span class="tna">Карма</span></p></td>
                        </tr>
                        <?php
                        if ($total > 0) {
                            foreach ($accounts as $account) {
                                echo '
                                <tr class="tbc2">
                                    <td class="table_td_ava" style="background-image: url(' . $account->getAvatar() . ');"><a href="/u/' . $account->getName() . '" class="full"></a></td>
                                    <td><p><a href="/u/' . $account->getName() . '" title="" class="acc_info_name ">' . $account->getName() . '</a></p></td>
                                    <td><p><span class="text_dam">' . $account->getLevel() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $account->getGroup()->getName() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $account->getStatus()->getName() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $account->getPostCount() . '</span></p></td>
                                    <td><p><span class="text_dam">' . $account->getCommentCount() . '</span></p></td>
                                    <td><p><span class="' . $account->getCarmaColoClass() . '">' . $account->getCarmaSign() . $account->getCarma() . '</span></p></td>
                                </tr>
                            ';
                            }
                        } else {
                            echo '<tr class="tbc2"><td colspan="7">Пользователей нет.</td></tr>';
                        }
                        ?>
                        <tr class="header">
                            <td><p><span class="tna">Аватар</span></p></td>
                            <td><p><span class="tna">Имя</span></p></td>
                            <td><p><span class="tna">Уровень</span></p></td>
                            <td><p><span class="tna">Группа</span></p></td>
                            <td><p><span class="tna">Статус</span></p></td>
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

<?= "<div class='pagination'>$pagination</div>" ?>

