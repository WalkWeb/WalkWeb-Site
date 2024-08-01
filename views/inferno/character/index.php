<?php

use App\Domain\Account\Character\CharacterInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Просмотр информации о персонаже';

if (empty($character) || !($character instanceof CharacterInterface)) {
    throw new AppException('character/index view: miss $character');
}

$owner = false;
$active_char = false;

?>

<div class="cr_parent">
    » <a href="/u/<?= $character->getAccountName() ?>" title="" class="osnova"><?= $character->getAccountName() ?></a>
</div>

<div align="center">
    <div class="pbg01">
        <div class="profile_life1">
            <div class="profile_life2">
                <div class="character_text">
                    <div class="profile_life3">
                        <div class="profile_life4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cr_left_box">
    <p>
        <a href="#" title="">Дерево способностей</a> (<?= $character->getLevel()->getSkillPoints() ?>)
    </p>
</div>

<div class="cr_right_box">
    <p>
        Создан: #<br />
        Сезон: <?= $character->getSeason()->getName() ?><br />
        Побед в PvP: #
</div>

<div class="cr_ava_box">
    <div class="profile_char_container">
        <div class="profile_char_ava_cont">
            <div class="ch_char_ava" style="background-image: url(<?= $character->getAvatar() ?>);"></div>
        </div>
    </div>
    <div class="profile_char_ava_border"></div>
</div>
<p class="ch_char_info">
    <span class="cr_lvl"><?= $character->getLevel()->getLevel() ?></span>
    <span class="cr_class"><?= $character->getProfession()->getName($character->getFloor()) ?></span><br />
    <span class="cr_race"><?= $character->getGenesis()->getSingle() ?></span>
</p>

<div class="ch_exp_cont">
    <div class="ch_exp_box">
        <div class="ch_exp" style="width: <?= $character->getLevel()->getExpBarWeight() ?>%;"></div>
    </div>
    <div class="ch_exp_text_box">
        <?= $character->getLevel()->getExpAtLevel() ?> / <?= $character->getLevel()->getExpToLevel() ?>
    </div>
</div>

<div class="cr_base_stats_box">
    <div class="cr_base_stats_box_l">
        <div class="cr_base_hp"></div>
        <div class="cr_base_hp_text">
            <abbr class="cr_base_stats_abbr" title="Здоровье"><span id="char_hp">#</span></abbr>
        </div>
        <div class="cr_base_mana"></div>
        <div class="cr_base_mana_text">
            <abbr class="cr_base_stats_abbr" title="Мана"><span id="char_mp">#</span></abbr>
        </div>
        <div class="cr_base_stam"></div>
        <div class="cr_base_stam_text">
            <abbr class="cr_base_stats_abbr" title="Выносливость"><span id="char_stam">#</span></abbr>
        </div>
        <div class="cr_base_horror"></div>
        <div class="cr_base_horror_text">
            <abbr class="cr_base_stats_abbr" title="Предел ужаса"><span id="char_horror">#</span></abbr>
        </div>
    </div>
    <div class="cr_base_stats_box_r">
        <span class="cr_avg_def">
            <span id="char_ave_def"> #</span>% <span id="char_ave_set">#</span>
        </span>
        <br />Средние сопротивления
    </div>
    <div class="cr_base_stats_box_c">
        <span class="cr_dps" id="char_dps">#</span>
        <br />Средний урон за ход
    </div>
</div>

<div id="character_stats">

    <div class="ch_p_row">
        <div class="ch_left">
            <table class="cr_table_char_box">
                <tr>
                    <td class="tb01"></td>
                    <td class="tb02"></td>
                    <td class="tb03"></td>
                    <td class="tb04"></td>
                    <td class="tb05"></td>
                </tr>
                <tr>
                    <td class="tb06"></td>
                    <td colspan="3" rowspan="3" class="cr_table_char">
                        <div class="ch_container">
                            <div class="ch_character">
                                <div class="ch_equip" style="background-image: url(<?= $this->getInventoryBackground($character) ?>);">
                                    <div class="ch_black">
                                        <div class="ch_black_left">
                                            <div class="ch_section ch_amulet">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_main_hand">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_gloves">
                                                <!-- TODO -->
                                            </div>
                                        </div>
                                        <div class="ch_black_right">
                                            <div class="ch_section ch_shoulders">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_off_hand">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_ring1">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_ring2">
                                                <!-- TODO -->
                                            </div>
                                        </div>
                                        <div class="ch_black_center">
                                            <div class="ch_section ch_helmet">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_armor">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_legs">
                                                <!-- TODO -->
                                            </div>
                                            <div class="ch_section ch_boots">
                                                <!-- TODO -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
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
            </table>
        </div>
        <div class="ch_right">
            <table class="cr_table_stats_box">
                <tr>
                    <td class="tb01"></td>
                    <td class="tb02"></td>
                    <td class="tb03"></td>
                    <td class="tb04"></td>
                    <td class="tb05"></td>
                </tr>
                <tr>
                    <td class="tb06"></td>
                    <td colspan="3" rowspan="3" class="cr_table_stats">
                        <div class="ch_inv_container">
                            <div class="ch_inv">
                                <div class="ch_inventory base_scroll_v">
                                    <table class="w_100">
                                        <tr>
                                            <td colspan="4" class="ch_td_p ch_td_h">
                                                Базовые характеристики
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сила:</td>
                                            <td class="ch_td_v">#</td>
                                            <td class="ch_td_p">Интеллект:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Ловкость:</td>
                                            <td class="ch_td_v">#</td>
                                            <td class="ch_td_p">Воля:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Телосложение:</td>
                                            <td class="ch_td_v">#</td>
                                            <td class="ch_td_p">Восприятие:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Харизма:</td>
                                            <td class="ch_td_v">#</td>
                                            <td class="ch_td_p">Удача:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="ch_td_p ch_td_h">
                                                Атакующие характеристики
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="ch_td_p">
                                                <span class="ch_damage">Урон:</span>
                                                <span class="ch_damage_value">#</span>
                                            </td>
                                            <td class="ch_td_p">Тип урона:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Меткость:</td>
                                            <td class="ch_td_v">#</td>
                                            <td class="ch_td_p">Магическая меткость:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Шанс критического удара:</td>
                                            <td class="ch_td_v">#%</td>
                                            <td class="ch_td_p">Множитель критического удара:</td>
                                            <td class="ch_td_v">#%</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="ch_td_p ch_td_h">
                                                Защитные характеристики
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление физическому урону:</td>
                                            <td class="ch_td_v">#%</td>
                                            <td class="ch_td_p">Защита:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону огнем:</td>
                                            <td class="ch_td_v">#%</td>
                                            <td class="ch_td_p">Магическая защита:</td>
                                            <td class="ch_td_v">#</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону водой:</td>
                                            <td class="ch_td_v">#%</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону воздухом:</td>
                                            <td class="ch_td_v">#%</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону землей:</td>
                                            <td class="ch_td_v">#%</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону жизни:</td>
                                            <td class="ch_td_v">#%</td>
                                        </tr>
                                        <tr>
                                            <td class="ch_td_p">Сопротивление урону смерти:</td>
                                            <td class="ch_td_v">#%</td>
                                            <td class="ch_td_p"></td>
                                            <td class="ch_td_v"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="ch_td_p ch_td_h">
                                                Дополнительные параметры
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
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
            </table>
        </div>
    </div>

    <div class="both"></div>

</div>
