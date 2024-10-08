<?php

use App\Domain\Account\AccountInterface;
use App\Domain\Account\Character\Collection\CharacterCollection;
use WalkWeb\NW\AppException;

if (empty($account) || !($account instanceof AccountInterface)) {
    throw new AppException('view.account.profile: miss or invalid account');
}

if (empty($characters) || !($characters instanceof CharacterCollection)) {
    throw new AppException('view.account.profile: miss or invalid characters');
}

if (empty($maxCharacters) || !is_int($maxCharacters)) {
    throw new AppException('view.account.profile: miss or invalid maxCharacters');
}

$this->title = APP_NAME . ' — Ваш профиль';

$charactersCount = count($characters);
$rise = $account->getMainCharacter()->getLevel()->getStatPoints() > 0;

?>

<div class="row_mc1">
    <div class="content_profile_box">

        <h1 class="pr_title"><?= $account->getName() ?></h1>
        <p class="pr_level">
            <?= $account->getMainCharacter()->getLevel()->getLevel() ?> <span class="pr_level_t">уровень</span>
        </p>

        <?php
        if ($account->getMainCharacter()->getLevel()->getLevel() < 2) {
            echo '
            <div class="select_text_box">
                <div class="select_text_row">
            
                    <p class="center"><span class="select_text_header">Повышение уровня аккаунта</span></p>
            
                    <p>
                        За написание постов и комментариев ваш аккаунт будет получать опыт, а при получении нового уровня 
                        вы сможете повысить его характеристики. Характеристики аккаунта прибавляются к существующим характеристикам
                        ваших персонажей, тем самым усиливая их всех
                    </p>
                    
                    <p>
                        Попробуйте написать первый интересный для других пользователей пост, это как раз повысит уровень
                        аккаунта до 2-го. Когда другие пользователи будут лайкать ваш пост - вы сможете получить 
                        дополнительный опыт. На определенном уровне посты без рейтинга практически не будут приближать
                        вас к новому уровню
                    </p>
                </div>
            </div>';
        }
        ?>

        <div align="center">
            <div class="pbg05">
                <div class="profile_life1">
                    <div class="profile_life2">
                        <div class="profile_life_info_text">
                            <div class="profile_life3">
                                <div class="profile_life4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="pr_info_box" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td class="pr_ava_box">
                    <table class="pr_ava_table" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="tb01"></td>
                            <td class="tb02"></td>
                            <td class="tb03"></td>
                            <td class="tb04"></td>
                            <td class="tb05"></td>
                        </tr>
                        <tr>
                            <td class="tb06"></td>
                            <td colspan="3" rowspan="3" class="pr_ava" style="background-image: url(<?= $account->getAvatar() ?>)"></td>
                            <td class="tb07"></td>
                        </tr>
                        <tr>
                            <td class="tb08"></td>
                            <td class="tb09"></td>
                        </tr>
                        <tr>
                            <td class="tb10"></td>
                            <td class="tb11"></td>
                        </tr>
                        <tr>
                            <td class="tb12"></td>
                            <td class="tb13"></td>
                            <td class="tb14"></td>
                            <td class="tb15"></td>
                            <td class="tb16"></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="pr_info">
                    <div class="pr_info_base">
                        <dl>
                            <dt>Пол:</dt>
                            <dd><?= $account->getFloor()->getName() ?></dd>
                        </dl>
                        <dl>
                            <dt>Карма:</dt>
                            <dd>
                                <span class="<?= $account->getCarma()->getCarmaColoClass() ?>">
                                    <?= $account->getCarma()->getCarmaSign() . $account->getCarma()->getCarma() ?>
                                </span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Количество постов:</dt>
                            <dd><?= $account->getPostCount() ?></dd>
                        </dl>
                        <dl>
                            <dt>Количество комментариев:</dt>
                            <dd><?= $account->getCommentCount() ?></dd>
                        </dl>
                        <dl>
                            <dt>Группа:</dt>
                            <dd><?= $account->getGroup()->getName() ?></dd>
                        </dl>
                        <dl>
                            <dt>Статус:</dt>
                            <dd><?= $account->getStatus()->getName() ?></dd>
                        </dl>
                        <dl>
                            <dt>Статус в чате:</dt>
                            <dd>#</dd>
                        </dl>
                        <dl>
                            <dt>Зарегистрирован:</dt>
                            <dd><?= $account->getCreatedAt()->format('Y-m-d H:i:s') ?></dd>
                        </dl>
                    </div>

                    <p>
                        Доступно места на диске:
                    </p>

                    <div class="pr_upload_box">
                        <div class="pr_upload" style="width: <?= $account->getUpload()->getUploadBarWeight() ?>%;"></div>
                        <div class="pr_upload_text"><?= $account->getUpload()->getUploadMb() ?> / <?= $account->getUpload()->getUploadMaxMb() ?> Мбайт</div>
                    </div>

                    <p><a href="/logout" class="input_submit small">Выход</a></p>

                </td>
                <td class="pr_stats">
                    <table class="pr_stats_table">
                        <tr>
                            <td><img src="/icon/str.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_str">Сила:</td>
                            <td class="pr_stats_td_r pr_stat_str"><span id="acc_str">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_str()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                            <td><img src="/icon/int.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_int">Интеллект:</td>
                            <td class="pr_stats_td_r pr_stat_int"><span id="acc_int">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_int()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                        </tr>
                        <tr>
                            <td><img src="/icon/dex.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_dex">Ловкость:</td>
                            <td class="pr_stats_td_r pr_stat_dex"><span id="acc_dex">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_dex()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                            <td><img src="/icon/will.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_will">Воля:</td>
                            <td class="pr_stats_td_r pr_stat_will"><span id="acc_will">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_will()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                        </tr>
                        <tr>
                            <td><img src="/icon/end.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_end">Телосложение:</td>
                            <td class="pr_stats_td_r pr_stat_end"><span id="acc_end">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_end()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                            <td><img src="/icon/perc.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_perc">Восприятие:</td>
                            <td class="pr_stats_td_r pr_stat_perc"><span id="acc_perc">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_perc()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                        </tr>
                        <tr>
                            <td><img src="/icon/char.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_char">Харизма:</td>
                            <td class="pr_stats_td_r pr_stat_char"><span id="acc_char">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_char()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                            <td><img src="/icon/luck.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_luck">Удача:</td>
                            <td class="pr_stats_td_r pr_stat_luck"><span id="acc_luck">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_luck()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                        </tr>
                        <tr>
                            <td><img src="/icon/energy.png" class="pr_stats_i" alt="" /></td>
                            <td class="pr_stats_td_l pr_stat_energy">Энергия:</td>
                            <td class="pr_stats_td_r pr_stat_energy"><span id="acc_energy">#</span></td>
                            <td class="pr_stats_td_i"><?= $rise ? '<span onclick="add_energy()"><img src="/icon/stat_up.png" alt="" class="add_stat_img" /></span>' : '' ?></td>
                            <?php
                            if ($rise) {
                                echo '<td class="pr_stats_td_l" colspan="3"><span id="acc_stats_desc">Свободных очков:</span></td>
                              <td class="pr_stats_td_s"><span id="acc_stats_point">' . $account->getMainCharacter()->getLevel()->getStatPoints() . '</span></td>';
                            }
                            ?>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>


        <div align="center">
            <div class="pbg07">
                <div class="profile_life1">
                    <div class="profile_life2">
                        <div class="profile_life_characters">
                            <div class="profile_life3">
                                <div class="profile_life4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="pr_char_sum">
            Персонажей: <?= $charactersCount ?> / <?= $maxCharacters ?><br />
            <a href="/character/new" title="" class="osnova">Создать нового персонажа</a>
        </p>

        <div class="profile_chars_container">
            <?php
            foreach ($characters as $character) {
                echo '
                    <a href="/h/' . $character->getId() . '" title="" class="pr_char_link">
                    <div class="pr_char_box">
                        <div class="profile_char_container">
                            <div class="profile_char_ava_cont">
                                <div class="pr_char_ava" style="background-image: url(' . $character->getAvatar() . ');"></div>
                            </div>
                        </div>
                        <div class="profile_char_ava_border"></div>
                        <div class="profile_char_desc">
                            <p><span class="pr_char_lvl">' . $character->getLevel() . '</span> ' . $character->getProfession() . '<br />
                            <span class="pr_char_race">' . $character->getGenesis() . '</span></p>
                        </div>
                    </div>
                    </a>';
            }
            ?>
        </div>

        <br /><br />
    </div>
</div>
<div class="row_mc3"></div>

<h2 class="pr_h2">Материалы, созданные <?= $account->getName() ?></h2>

<?php

echo '<p class="text center"><b>' . $account->getName() . '</b> пока ничего не ' . ($account->getFloor()->getId() === 1 ? 'написал' : 'написала') . '</p>';

?>

<div class="profile_both"></div>

<div class="demon_footer"></div>
