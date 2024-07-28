
<link rel="stylesheet" type="text/css" href="/inferno/styles/registration.css?ver=1.0">

<div class="r_races_cont">
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="1" class="dnone" onclick="raceChange(this.value)" checked>
            <span id="ava-1" class="r_race_icon r_humans"></span> <span>Люди</span>
        </label>
    </div>
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="2" class="dnone" onclick="raceChange(this.value)">
            <span id="ava-2" class="r_race_icon r_elves"></span> <span>Эльфы</span>
        </label>
    </div>
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="3" class="dnone" onclick="raceChange(this.value)">
            <span id="ava-3" class="r_race_icon r_orcs"></span> <span>Орки</span>
        </label>
    </div>
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="4" class="dnone" onclick="raceChange(this.value)">
            <span id="ava-4" class="r_race_icon r_dwarfs"></span> <span>Гномы</span>
        </label>
    </div>
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="5" class="dnone" onclick="raceChange(this.value)">
            <span id="ava-5" class="r_race_icon r_angels"></span> <span>Ангелы</span>
        </label>
    </div>
    <div class="rcbox r_16" align="center">
        <label>
            <input type="radio" name="genesis_id" value="6" class="dnone" onclick="raceChange(this.value)">
            <span id="ava-6" class="r_race_icon r_demons"></span> <span>Демоны</span>
        </label>
    </div>
</div>

<div class="r_n_c_c">
    <div class="r_n_l" id="class_list">
        <div class="r_class">
            <label>
                <input type="radio" name="profession_id" value="1" onclick="classChange(this.value)" checked>
                <span>Паладин</span>
            </label>
        </div>
        <div class="r_class">
            <label>
                <input type="radio" name="profession_id" value="2" onclick="classChange(this.value)">
                <span>Убийца</span>
            </label>
        </div>
        <div class="r_class">
            <label>
                <input type="radio" name="profession_id" value="3" onclick="classChange(this.value)">
                <span>Рейнджер</span>
            </label>
        </div>
        <div class="r_class">
            <label>
                <input type="radio" name="profession_id" value="4" onclick="classChange(this.value)">
                <span>Маг Стихий</span>
            </label>
        </div>
        <div class="r_class">
            <label>
                <input type="radio" name="profession_id" value="5" onclick="classChange(this.value)">
                <span>Жрец</span>
            </label>
        </div>
    </div>
    <div class="r_n_r" id="ability_list"></div>
    <div class="r_n_c" id="stats_list"></div>
</div>

<?= '<div class="r_ava_c" id="avatars_list"></div><div style="width: 100%; clear: both;"></div>' ?>

<script src="/inferno/js/reg.js?v=1.0"></script>
