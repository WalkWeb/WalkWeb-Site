let login = false;
let password = false;
let re_password = false;
let mail = false;
let capcha = false;

const MIN_LOGIN_LENGTH = 5;
const MAX_LOGIN_LENGTH = 15;

const MIN_PASSWORD_LENGTH = 4;
const MAX_PASSWORD_LENGTH = 20;

const MIN_MAIL_LENGTH = 5;
const MAX_MAIL_LENGTH = 25;

function cLogin() {
    let login_length = document.getElementById('login_id').value.length;
    let login_correct = document.getElementById('login_correct');

    if (login_length >= MIN_LOGIN_LENGTH && login_length <= MAX_LOGIN_LENGTH) {
        login_correct.innerHTML = '<span class="green">&#10004; верно</span>';
        login = true;
    } else {
        login_correct.innerHTML = 'от ' + MIN_LOGIN_LENGTH + ' до ' + MAX_LOGIN_LENGTH + ' символов';
        login = false;
    }

    checkRegistration();
}

function cPass() {
    let pass_length = document.getElementById('pass_id').value.length;
    let pass_correct = document.getElementById('pass_correct');

    if (pass_length >= MIN_PASSWORD_LENGTH && pass_length <= MAX_PASSWORD_LENGTH) {
        pass_correct.innerHTML = '<span class="green">&#10004; верно</span>';
        password = true;
    } else {
        pass_correct.innerHTML = 'от ' + MIN_PASSWORD_LENGTH + ' до ' + MAX_PASSWORD_LENGTH + ' символов';
        password = false;
    }

    checkRegistration();
}

function rePass() {
    let pass = document.getElementById('pass_id').value;
    let re_pass = document.getElementById('repass_id').value;
    let repass_correct = document.getElementById('repass_correct');

    if (password && pass === re_pass) {
        repass_correct.innerHTML = '<span class="green">&#10004; пароли совпадают</span>';
        re_password = true;
    } else {
        repass_correct.innerHTML = 'пароли не совпадают';
        re_password = false;
    }

    checkRegistration();
}

function cMail() {
    let mail_form = document.getElementById('email_id');
    let mail_correct = document.getElementById('mail_correct');

    if (isEmail(mail_form.value)) {
        mail_correct.innerHTML = '<span class="green">&#10004; верно</span>';
        mail = true;
    } else {
        mail_correct.innerHTML = 'указана некорректная почта';
        mail = false;
    }

    checkRegistration();
}

function checkRegistration() {
    let button = document.getElementById('submit_id');

    if (login && password && re_password && mail) {
        console.log('Регистрация разрешена');
        button.removeAttribute('disabled');
    } else {
        console.log('Регистрация не разрешена');
        button.setAttribute('disabled', 'disabled');
    }
}

function isEmail(mail) {
    let at = "@";
    let dot = ".";
    let lat = mail.indexOf(at);
    let litem = mail.length;
    if (mail.indexOf(at) === -1) return false; if (mail.indexOf(at) === -1 || mail.indexOf(at) === 0 || mail.indexOf(at) === litem) return false;
    if (mail.indexOf(dot) === -1 || mail.indexOf(dot) === 0 || mail.indexOf(dot) >= litem - 2) return false;
    if (mail.indexOf(at,(lat+1)) !== -1) return false;
    if (mail.substring(lat-1,lat) === dot || mail.substring(lat+1,lat+2) === dot) return false;
    if (mail.indexOf(dot,(lat+2)) === -1) return false;
    return mail.indexOf(" ") === -1;
}


let _race = '1';
let _floor = '1';
let _class = '1';

function raceChange(value) {
    _race = value;
    setClasses();
    setClassBonuses();
    setAvatars();
    setRegFields();
}

function classChange(value) {
    _class = value;
    setClassBonuses();
}

function floorChange(value) {
    _floor = value;
    setAvatars();
    setClasses();
    setClassBonuses();
}

function setClassBonuses()
{
    // Паладин
    if (_class === '1') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 20, 20, 15, 25, 15, 5, 5) + derivedStats(140, 80, 84, 88);

        document.getElementById('ability_list').innerHTML = ability(
            '384', 'Сокрушающий удар — Паладин делает мощный удар, наносящий урон магией жизни',
            '287', 'Удар щитом — Выбрав подходящий момент, паладин делает неожиданный удар щитом, который оглушает противника',
            '253', 'Авангард — Призывая высшие силы на помощь, паладин восстанавливает свое здоровья, и получает бонус ко всем сопротивляемостям'
        );

    }
    // Убийца
    if (_class === '2') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 15, 25, 15, 25, 15, 5, 10) + derivedStats(140, 80, 84, 78, 'Шанс критического удара -20%, сила критического удара +100%');

        document.getElementById('ability_list').innerHTML = ability(
            '438', 'Кровоточащая рана — Наносит глубокий порез противнику, из-за которого он будет постепенно терять здоровье',
            '279', 'Повреждение рук — Выбрав подходящий момент убийца делает удар по рукам противника, снижая наносимый им урон',
            '349', 'Удар в спину — Делает ловкий рывок за спину противника, нанося ему смертельный удар в самое уязвимое место'
        );

    }
    // Рейнджер
    if (_class === '3') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 15, 30, 15, 25, 15, 5, 5) + derivedStats(140, 80, 84, 78);

        document.getElementById('ability_list').innerHTML = ability(
            '191', 'Взрывной выстрел — Выстреливает стрелой с взрывчатым веществом, за счет которого стрела наносит большой урон огнем и поджигает протвника',
            '471', 'Хорошая позиция — Рейнджер занимает наилучшую позицию на поле боя, увеличивая свою меткость и защиту',
            '242', 'Град стрел — Делает несколько очень быстрых выстрелов один за одним'
        );

    }
    // Маг Стихий
    if (_class === '4') {
        document.getElementById('stats_list').innerHTML =
            baseStats(15, 25, 15, 20, 20, 25, 5, 5) + derivedStats(120, 110, 74, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '233', 'Огненный шар — Концентрирует стихию огня, превращая её в огненный шар, и обрушивает его на противника',
            '232', 'Воспламенение — Поджигает противника, который начинает получать большой урон огнем в течении небольшого количества времени',
            '276', 'Метеоритный дождь — Обрушивает на противников метеоритный дождь, который повреждает одну или несколько целей до трех раз'
        );

    }
    // Жрец
    if (_class === '5') {
        document.getElementById('stats_list').innerHTML =
            baseStats(15, 20, 15, 20, 20, 25, 10, 5) + derivedStats(108, 121, 74, 78, 'Здоровье -10%, Мана +10%');

        document.getElementById('ability_list').innerHTML = ability(
            '277', 'Осколки льда — Создает и обрушивает на противника ледяные осколки',
            '497', 'Поддержка воды — Накладывает баф, постепенно восстанавливающий здоровье',
            '452', 'Исцеление — Восстанавливает значительное количество здоровья самому раненому члену отряда'
        );

    }
    // Хранитель
    if (_class === '6') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 15, 25, 10, 25, 15, 10, 5) + derivedStats(140, 80, 84, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '335', 'Разящий клинок — Делает разящий удар клинком с увеличенным шансом и силой критического удара',
            '128', 'Круговая оборона — Хранитель занимает оборонительную боевую позицию, увеличивая на некоторое время шанс блока атак и заклинаний',
            '409', 'Крепость — Хранитель накладывает на себя баф, увеличивая сопротивления и немного максимальные сопротивления на несколько ходов'
        );

    }
    // Дневной охотник
    if (_class === '7') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 15, 30, 10, 20, 15, 10, 10) + derivedStats(120, 80, 74, 78, 'Защита +20%, Магическая защита -20%');

        document.getElementById('ability_list').innerHTML = ability(
            '182', 'Отравляющее жало — Охотники знают, какими травами нужно обработать лезвие оружия, чтобы оно не только нанесло урон, но и отравляло противника',
            '442', 'Поддержка ветра — Призывая силы ветра себе на помощь, охотник увеличивает скорость своих движений, получая бонус к скорости атаки и защите',
            '482', 'Пронзающий удар — Сконцентрировав всю свою ярость охотник делает сокрушительный удар, который наносит огромный урон и имеет увеличенный шанс нанести критический удар'
        );

    }
    // Ночной охотник
    if (_class === '8') {
        document.getElementById('stats_list').innerHTML =
            baseStats(15, 15, 40, 10, 15, 20, 10, 5) + derivedStats(100, 95, 64, 73, 'Защита -20%, Магическая защита +20%');

        document.getElementById('ability_list').innerHTML = ability(
            '190', 'Ледяной выстрел — Превращает стрелу в ледяной осколок, которая нанесет большой урон магией воды',
            '199', 'Паучья сеть — Призывает из тьмы паутину, которая обволакивает и сковывает противника, снижая его скорость атаки, скорость создания заклинаний и меткость',
            '360', 'Залп тьмы — Наносит сокрушительный урон магией тьмы. 25% от нанесенного урона воруется в здоровье'
        );

    }
    // Заклинатель
    if (_class === '9') {
        document.getElementById('stats_list').innerHTML =
            baseStats(10, 25, 25, 15, 15, 25, 10, 5) + derivedStats(100, 110, 64, 78);

        document.getElementById('ability_list').innerHTML = ability(
            '503', 'Шаровая молния — Создает шаровую молнию, которая наносит урон магией воздуха',
            '500', 'Единство с ветром — Незначительно увеличивает скорость атаки, создания заклинаний, меткость и защиту на несколько ходов',
            '214', 'Вихрь — Создает мощный вихрь, который проходит по врагам, нанося урон магией воздуха два раза подряд'
        );

    }
    // Оракул
    if (_class === '10') {
        document.getElementById('stats_list').innerHTML =
            baseStats(10, 20, 25, 15, 15, 25, 15, 5) + derivedStats(100, 110, 64, 73);

        document.getElementById('ability_list').innerHTML = ability(
            '230', 'Осколки льда — Концентрирует влагу из воздуха, которая сразу замерзает, и обрушивается на противника ледяными осколками, нанося урон водой',
            '196', 'Восстановление — Накладывает баф на себя или союзника, постепенно восстанавливающий здоровье',
            '392', 'Решимость — Накладывает комплексное усиление на себя или союзника, увеличивает урон, меткость и защиту'
        );

    }
    // Разрушитель
    if (_class === '11') {
        document.getElementById('stats_list').innerHTML =
            baseStats(45, 10, 15, 10, 30, 10, 5, 5) + derivedStats(160, 65, 94, 98);

        document.getElementById('ability_list').innerHTML = ability(
            '523', 'Гнев — Вложив весь свой гнев, разрушитель делает сильный удар, наносящий увеличенный урон',
            '248', 'Грязный прием — Разрушитель делает удар по кистям противника, уменьшая скорость атаки и защиту цели на несколько ходов',
            '219', 'Боевой клич — Когда бой затянулся разрушитель использует боевой клич, увеличивающий свой и без того не малый урон на несколько ходов'
        );

    }
    // Титан
    if (_class === '12') {
        document.getElementById('stats_list').innerHTML =
            baseStats(30, 10, 15, 10, 40, 15, 5, 5) + derivedStats(240, 40, 114, 83, 'Здоровье +20%, Мана -50%');

        document.getElementById('ability_list').innerHTML = ability(
            '017', 'Удар ногой — Наносит удар ногой, который сбивает врага с ног. Сбитый с ног противник теряет возможность совершать действия',
            '204', 'Регенерация — Раскрывает резервные силы, которые постепенно восстанавливают небольшое количество здоровья',
            '266', 'Защитная стойка — Титан принимает защитную стойку, увеличивая свои сопротивления'
        );

    }
    // Берсерк
    if (_class === '13') {
        document.getElementById('stats_list').innerHTML =
            baseStats(35, 10, 20, 10, 30, 15, 5, 5) + derivedStats(160, 80, 94, 88, 'Регенерация здоровья +2%, Регенерация маны -2%, Расход выносливости -1');

        document.getElementById('ability_list').innerHTML = ability(
            '249', 'Град ударов — Делает три быстрых удара подряд',
            '235', 'Боевой транс — Берсерк входит в боевой транс, в котором его обычная и магическая защита вырастает',
            '148', 'Ликантропия — Чем дольше продолжается бой, тем сильнее берсерк превращается в разъяренного зверя, в форме которого его скорость атаки значительно вырастает'
        );

    }
    // Шаман
    if (_class === '14') {
        document.getElementById('stats_list').innerHTML =
            baseStats(30, 20, 10, 15, 25, 20, 5, 5) + derivedStats(140, 95, 84, 93);

        document.getElementById('ability_list').innerHTML = ability(
            '379', 'Ядовитый дух — Призывает ядовитый дух, который обрушивается на противника, нанося ему урон магией смерти',
            '324', 'Укрепляющее снадобье — Накладывает постепенное восстановление здоровья на самого раненого члена отряда',
            '406', 'Боевое лечение — Мощное лечение самого раненого члена отряда'
        );

    }
    // Боевой маг
    if (_class === '15') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 20, 10, 15, 25, 25, 5, 5) + derivedStats(140, 110, 84, 88);

        document.getElementById('ability_list').innerHTML = ability(
            '525', 'Сгусток плазмы — Создает раскаленный шар плазмы, и метает его во врага',
            '432', 'Лечение — Лечит самого раненого члена отряда',
            '036', 'Метеорит — Обрушивает на врага большой метеорит, который наносит урон огнем'
        );

    }
    // Страж
    if (_class === '16') {
        document.getElementById('stats_list').innerHTML =
            baseStats(30, 10, 15, 10, 40, 10, 5, 10) + derivedStats(200, 65, 114, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '121', 'Разрубание — Делает мощный удар по противнику, нанося урон стихией земли',
            '299', 'Запасное зелье — Выпивает запасное зелье, постепено восстановливающее здоровье',
            '271', 'Каменная кожа — Увеличивает свои сопротивления, а также максимальные сопротивления к стихиям огня, воды, воздуха и земли'
        );

    }
    // Искатель сокровищ
    if (_class === '17') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 10, 25, 10, 25, 10, 5, 20) + derivedStats(126, 59, 84, 78, 'Здоровье -10%, Мана -10%, Количество находимого золота +50%');

        document.getElementById('ability_list').innerHTML = ability(
            '341', 'Парный удар — Делает два удара подряд',
            '459', 'Отравленный клинок — Делает удар отравленным клинком, из-за которого цель еще несколько ходов будет получать урон водой',
            '336', 'Зачарованный клинок — Наносит мощный удар стихией земли с увеличенным шансом и силой критического удара'
        );

    }
    // Арбалетчик
    if (_class === '18') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 15, 15, 15, 30, 15, 5, 10) + derivedStats(160, 80, 94, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '006', 'Бронебойный болт — Выстрел таким болтом не только наносит увеличенный урон, но и пробивает щиты – его невозможно заблокировать',
            '070', 'Опыт ветерана — Поняв повадки противника арбалетчик начинает наносить более меткие и смертоносные выстрелы',
            '100', 'Взрывчатка — Арбалетчики бросает в противника самодельную взрывчатку, наносящую большой урон огнем'
        );

    }
    // Алхимик
    if (_class === '19') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 20, 10, 15, 30, 20, 5, 10) + derivedStats(160, 238, 94, 83, 'Мана +100%, Регенерация маны -3%');

        document.getElementById('ability_list').innerHTML = ability(
            '390', 'Взрывчатое зелье — Бросает в противника зелье со взрывчатым раствором',
            '234', 'Зелье оздоровления — Алхимик дает самому раненому союзнику зелье оздоравления, которое будет постепенно восстанавливать его здоровье и ману',
            '085', 'Зелье мощи — Усиливает одного из союзников зельем мощи, которое увеличивает его урон и сопротивления'
        );

    }
    // Отшельник
    if (_class === '20') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 20, 10, 15, 25, 20, 5, 15) + derivedStats(140, 95, 84, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '071', 'Каменный град — Отшельник создает мощное заклинание, которое наносит по противнику большой урон',
            '355', 'Боевая поддержка — Лечащее заклинание, которое восстанавливает здоровье наиболее раненому члены команды',
            '073', 'Ярость предков — В трудном бою отшельник призывает силу предков на помощь, и на врагов обрушивается мощное заклинание, наносящее подряд три удара'
        );

    }
    // Архангел
    if (_class === '21') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 20, 15, 10, 30, 15, 10, 5) + derivedStats(160, 56, 94, 88, 'Блок +12%, Магический блок +12%, Мана -30%, Регенерация маны -1%');

        document.getElementById('ability_list').innerHTML = ability(
            '051', 'Карающий свет — Архангел призывает высшие силы, которые обрушивают на врага карающий свет, наносящий урон магией жизни',
            '052', 'Неприступность — На несколько ходов Архангел получает бонус к обычному и магическому блоку',
            '526', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая свой урон и сопротивления'
        );

    }
    // Малахим
    if (_class === '22') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 20, 15, 10, 30, 20, 10, 5) + derivedStats(160, 95, 94, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '388', 'Небесное правосудие — Малахим призывает небесное правосудие, которое наносит по врагам до трех ударов магией жизни',
            '403', 'Переполняющая энергия — Баф на увеличение скорости атаки, шанса и силы критического урона',
            '526', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая свой урон и скорость атаки'
        );

    }
    // Феникс
    if (_class === '23') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 20, 15, 10, 30, 20, 10, 5) + derivedStats(160, 95, 94, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '419', 'Дыхание феникса — Урон огнем',
            '416', 'Дух феникса — Непобедимый дух феникса излечивает часть полученных повреждений',
            '526', 'Изначальная форма — Пробуждает в себе силы древней крови, восстанавливая здоровья, увеличивая урон и меткость'
        );

    }
    // Серафим
    if (_class === '24') {
        document.getElementById('stats_list').innerHTML =
            baseStats(15, 35, 10, 15, 15, 20, 15, 5) + derivedStats(80, 95, 64, 93, 'Здоровье -20%, Регенерация маны +1%, Ментальный барьер +30%');

        document.getElementById('ability_list').innerHTML = ability(
            '075', 'Молния — Призывая на помощь небеса, во врагов серафима ударяет молния',
            '053', 'Резервные силы — Лечащее заклинание, которое восстанавливает здоровье наиболее раненому члены команды',
            '526', 'Изначальная форма — Пробуждает в себе силы древней крови, восстанавливая здоровья, увеличивая урон и скорость атаки'
        );

    }
    // Арелим
    if (_class === '25') {
        document.getElementById('stats_list').innerHTML =
            baseStats(15, 25, 10, 15, 20, 25, 15, 5) + derivedStats(120, 110, 74, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '282', 'Чистая энергия — Арелим концентрирует заряд чистой энергии, которая наносит противнику урон магией жизни',
            '402', 'Ясность ума — Накладывает баф на увеличение меткости и защиты, а также немного увеличивающий скорость атаки и создания заклинаний',
            '526', 'Изначальная форма — Пробуждает в себе силы древней крови, восстанавливая здоровья, увеличивая защиту, магическую защиту и сопротивления'
        );

    }
    // Рыцарь ада
    if (_class === '26') {
        document.getElementById('stats_list').innerHTML =
            baseStats(30, 20, 20, 15, 20, 15, 5, 5) + derivedStats(120, 80, 74, 93, 'Общий множитель наносимого урона: -20%, Общий множитель получаемого урона: -20%');

        document.getElementById('ability_list').innerHTML = ability(
            '083', 'Агония — Накладывает на противника сильное проклятье, которое будет наносить урона огнем в течении нескольких ходов',
            '458', 'Вытягивание сил — Демон кусает противника, восстанавливая свое здоровье в размере 50% от нанесенного урона',
            '527', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая урон и сопротивления'
        );

    }
    // Мститель
    if (_class === '27') {
        document.getElementById('stats_list').innerHTML =
            baseStats(35, 20, 15, 15, 25, 10, 5, 5) + derivedStats(140, 65, 84, 98);

        document.getElementById('ability_list').innerHTML = ability(
            '430', 'Пентаграмма — Создает пентаграмму под ногами противника, которая наносит урон магией тьмы',
            '226', 'Сила тьмы — Мститель черпает силу из тьмы, увеличивая меткость и скорость атаки',
            '527', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая урон, скорость атаки, обычную и магическую меткость'
        );

    }
    // Архонт
    if (_class === '28') {
        document.getElementById('stats_list').innerHTML =
            baseStats(25, 20, 15, 15, 25, 20, 5, 5) + derivedStats(112, 76, 84, 88, 'Здоровье -20%, Мана -20%, Вампиризм 10%');

        document.getElementById('ability_list').innerHTML = ability(
            '220', 'Вытягивание сил — Архонт использует особое заклинание, которое восстанавливает 50% здоровья от нанесенного урона',
            '346', 'Вампиризм — На несколько ходов увеличивает свои способности воровства здоровья',
            '527', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая урон и скорость создания заклинаний'
        );

    }
    // Душегуб
    if (_class === '29') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 30, 15, 20, 15, 20, 5, 5) + derivedStats(100, 95, 64, 93);

        document.getElementById('ability_list').innerHTML = ability(
            '310', 'Прикосновение пустоты — Наносит урон магией смерти',
            '304', 'Расщепление — Накладывает вредоносное проклятье, которое расщепляет плоть цели. Постепенный урон',
            '527', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая урон и сопротивления'
        );

    }
    // Инкуб/Суккуб
    if (_class === '30') {
        document.getElementById('stats_list').innerHTML =
            baseStats(20, 20, 15, 15, 20, 15, 15, 10) + derivedStats(120, 80, 74, 83);

        document.getElementById('ability_list').innerHTML = ability(
            '160', 'Нечестный обмен — Отнимает у врага небольшое количество здоровья, восстанавливая свое здоровье на туже величину',
            '110', 'Проклятие — Проклинает противника, снижая его скорость атаки, скорость создания заклинаний, защиту и магическую защиту',
            '527', 'Изначальная форма — Пробуждает в себе силы древней крови, увеличивая урон и скорость создания заклинаний'
        );

    }
}

function setAvatars() {
    if (_race === '1' && _floor === '1') {
        humanMaleAvatars();
    }
    if (_race === '2' && _floor === '1') {
        elfMaleAvatars();
    }
    if (_race === '3' && _floor === '1') {
        orcMaleAvatars();
    }
    if (_race === '4' && _floor === '1') {
        dwarfMaleAvatars();
    }
    if (_race === '5' && _floor === '1') {
        angelMaleAvatars();
    }
    if (_race === '6' && _floor === '1') {
        demonMaleAvatars();
    }
    if (_race === '1' && _floor === '2') {
        humanFemaleAvatars();
    }
    if (_race === '2' && _floor === '2') {
        elfFemaleAvatars();
    }
    if (_race === '3' && _floor === '2') {
        orcFemaleAvatars();
    }
    if (_race === '4' && _floor === '2') {
        dwarfFemaleAvatars();
    }
    if (_race === '5' && _floor === '2') {
        angelFemaleAvatars();
    }
    if (_race === '6' && _floor === '2') {
        demonFemaleAvatars();
    }
}

function setClasses() {
    if (_race === '1' && _floor === '1') {
        humansMaleClasses();
        _class = '1';
    }
    if (_race === '2' && _floor === '1') {
        elfsMaleClasses();
        _class = '6';
    }
    if (_race === '3' && _floor === '1') {
        orcsMaleClasses();
        _class = '11';
    }
    if (_race === '4' && _floor === '1') {
        dwarfsMaleClasses();
        _class = '16';
    }
    if (_race === '5' && _floor === '1') {
        angelsMaleClasses();
        _class = '21';
    }
    if (_race === '6' && _floor === '1') {
        demonsMaleClasses();
        _class = '26';
    }
    if (_race === '1' && _floor === '2') {
        humansFemaleClasses();
        _class = '1';
    }
    if (_race === '2' && _floor === '2') {
        elfsFemaleClasses();
        _class = '6';
    }
    if (_race === '3' && _floor === '2') {
        orcsFemaleClasses();
        _class = '11';
    }
    if (_race === '4' && _floor === '2') {
        dwarfsFemaleClasses();
        _class = '16';
    }
    if (_race === '5' && _floor === '2') {
        angelsFemaleClasses();
        _class = '21';
    }
    if (_race === '6' && _floor === '2') {
        demonsFemaleClasses();
        _class = '26';
    }
}

function humanMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava1" type="radio" name="avatar_id" value="1" class="r_ava_input" checked><label for="ava1" class="r_l_reg"><img src="/img/avatars/game/humans/human001.jpg" alt="" /></label></span><span><input id="ava13" type="radio" name="avatar_id" value="13" class="r_ava_input"><label for="ava13" class="r_l_reg"><img src="/img/avatars/game/humans/human003.jpg" alt="" /></label></span><span><input id="ava25" type="radio" name="avatar_id" value="25" class="r_ava_input"><label for="ava25" class="r_l_reg"><img src="/img/avatars/game/humans/human005.jpg" alt="" /></label></span><span><input id="ava37" type="radio" name="avatar_id" value="37" class="r_ava_input"><label for="ava37" class="r_l_reg"><img src="/img/avatars/game/humans/human007.jpg" alt="" /></label></span><span><input id="ava49" type="radio" name="avatar_id" value="49" class="r_ava_input"><label for="ava49" class="r_l_reg"><img src="/img/avatars/game/humans/human009.jpg" alt="" /></label></span><span><input id="ava61" type="radio" name="avatar_id" value="61" class="r_ava_input"><label for="ava61" class="r_l_reg"><img src="/img/avatars/game/humans/human011.jpg" alt="" /></label></span><span><input id="ava73" type="radio" name="avatar_id" value="73" class="r_ava_input"><label for="ava73" class="r_l_reg"><img src="/img/avatars/game/humans/human013.jpg" alt="" /></label></span><span><input id="ava85" type="radio" name="avatar_id" value="85" class="r_ava_input"><label for="ava85" class="r_l_reg"><img src="/img/avatars/game/humans/human015.jpg" alt="" /></label></span><span><input id="ava97" type="radio" name="avatar_id" value="97" class="r_ava_input"><label for="ava97" class="r_l_reg"><img src="/img/avatars/game/humans/human017.jpg" alt="" /></label></span><span><input id="ava109" type="radio" name="avatar_id" value="109" class="r_ava_input"><label for="ava109" class="r_l_reg"><img src="/img/avatars/game/humans/human019.jpg" alt="" /></label></span><span><input id="ava121" type="radio" name="avatar_id" value="121" class="r_ava_input"><label for="ava121" class="r_l_reg"><img src="/img/avatars/game/humans/human021.jpg" alt="" /></label></span><span><input id="ava133" type="radio" name="avatar_id" value="133" class="r_ava_input"><label for="ava133" class="r_l_reg"><img src="/img/avatars/game/humans/human023.jpg" alt="" /></label></span>';
}

function humanFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava2" type="radio" name="avatar_id" value="2" class="r_ava_input" checked><label for="ava2" class="r_l_reg"><img src="/img/avatars/game/humans/human002.jpg" alt="" /></label></span><span><input id="ava14" type="radio" name="avatar_id" value="14" class="r_ava_input"><label for="ava14" class="r_l_reg"><img src="/img/avatars/game/humans/human004.jpg" alt="" /></label></span><span><input id="ava26" type="radio" name="avatar_id" value="26" class="r_ava_input"><label for="ava26" class="r_l_reg"><img src="/img/avatars/game/humans/human006.jpg" alt="" /></label></span><span><input id="ava38" type="radio" name="avatar_id" value="38" class="r_ava_input"><label for="ava38" class="r_l_reg"><img src="/img/avatars/game/humans/human008.jpg" alt="" /></label></span><span><input id="ava50" type="radio" name="avatar_id" value="50" class="r_ava_input"><label for="ava50" class="r_l_reg"><img src="/img/avatars/game/humans/human010.jpg" alt="" /></label></span><span><input id="ava62" type="radio" name="avatar_id" value="62" class="r_ava_input"><label for="ava62" class="r_l_reg"><img src="/img/avatars/game/humans/human012.jpg" alt="" /></label></span><span><input id="ava74" type="radio" name="avatar_id" value="74" class="r_ava_input"><label for="ava74" class="r_l_reg"><img src="/img/avatars/game/humans/human014.jpg" alt="" /></label></span><span><input id="ava86" type="radio" name="avatar_id" value="86" class="r_ava_input"><label for="ava86" class="r_l_reg"><img src="/img/avatars/game/humans/human016.jpg" alt="" /></label></span><span><input id="ava98" type="radio" name="avatar_id" value="98" class="r_ava_input"><label for="ava98" class="r_l_reg"><img src="/img/avatars/game/humans/human018.jpg" alt="" /></label></span><span><input id="ava110" type="radio" name="avatar_id" value="110" class="r_ava_input"><label for="ava110" class="r_l_reg"><img src="/img/avatars/game/humans/human020.jpg" alt="" /></label></span><span><input id="ava122" type="radio" name="avatar_id" value="122" class="r_ava_input"><label for="ava122" class="r_l_reg"><img src="/img/avatars/game/humans/human022.jpg" alt="" /></label></span><span><input id="ava134" type="radio" name="avatar_id" value="134" class="r_ava_input"><label for="ava134" class="r_l_reg"><img src="/img/avatars/game/humans/human024.jpg" alt="" /></label></span>';
}

function elfMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava3" type="radio" name="avatar_id" value="3" class="r_ava_input" checked><label for="ava3" class="r_l_reg"><img src="/img/avatars/game/elfs/elf001.jpg" alt="" /></label></span><span><input id="ava15" type="radio" name="avatar_id" value="15" class="r_ava_input"><label for="ava15" class="r_l_reg"><img src="/img/avatars/game/elfs/elf003.jpg" alt="" /></label></span><span><input id="ava27" type="radio" name="avatar_id" value="27" class="r_ava_input"><label for="ava27" class="r_l_reg"><img src="/img/avatars/game/elfs/elf005.jpg" alt="" /></label></span><span><input id="ava39" type="radio" name="avatar_id" value="39" class="r_ava_input"><label for="ava39" class="r_l_reg"><img src="/img/avatars/game/elfs/elf007.jpg" alt="" /></label></span><span><input id="ava51" type="radio" name="avatar_id" value="51" class="r_ava_input"><label for="ava51" class="r_l_reg"><img src="/img/avatars/game/elfs/elf009.jpg" alt="" /></label></span><span><input id="ava63" type="radio" name="avatar_id" value="63" class="r_ava_input"><label for="ava63" class="r_l_reg"><img src="/img/avatars/game/elfs/elf011.jpg" alt="" /></label></span><span><input id="ava75" type="radio" name="avatar_id" value="75" class="r_ava_input"><label for="ava75" class="r_l_reg"><img src="/img/avatars/game/elfs/elf013.jpg" alt="" /></label></span><span><input id="ava87" type="radio" name="avatar_id" value="87" class="r_ava_input"><label for="ava87" class="r_l_reg"><img src="/img/avatars/game/elfs/elf015.jpg" alt="" /></label></span><span><input id="ava99" type="radio" name="avatar_id" value="99" class="r_ava_input"><label for="ava99" class="r_l_reg"><img src="/img/avatars/game/elfs/elf017.jpg" alt="" /></label></span><span><input id="ava111" type="radio" name="avatar_id" value="111" class="r_ava_input"><label for="ava111" class="r_l_reg"><img src="/img/avatars/game/elfs/elf019.jpg" alt="" /></label></span><span><input id="ava123" type="radio" name="avatar_id" value="123" class="r_ava_input"><label for="ava123" class="r_l_reg"><img src="/img/avatars/game/elfs/elf021.jpg" alt="" /></label></span><span><input id="ava135" type="radio" name="avatar_id" value="135" class="r_ava_input"><label for="ava135" class="r_l_reg"><img src="/img/avatars/game/elfs/elf023.jpg" alt="" /></label></span>';
}

function elfFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava4" type="radio" name="avatar_id" value="4" class="r_ava_input" checked><label for="ava4" class="r_l_reg"><img src="/img/avatars/game/elfs/elf002.jpg" alt="" /></label></span><span><input id="ava16" type="radio" name="avatar_id" value="16" class="r_ava_input"><label for="ava16" class="r_l_reg"><img src="/img/avatars/game/elfs/elf004.jpg" alt="" /></label></span><span><input id="ava28" type="radio" name="avatar_id" value="28" class="r_ava_input"><label for="ava28" class="r_l_reg"><img src="/img/avatars/game/elfs/elf006.jpg" alt="" /></label></span><span><input id="ava40" type="radio" name="avatar_id" value="40" class="r_ava_input"><label for="ava40" class="r_l_reg"><img src="/img/avatars/game/elfs/elf008.jpg" alt="" /></label></span><span><input id="ava52" type="radio" name="avatar_id" value="52" class="r_ava_input"><label for="ava52" class="r_l_reg"><img src="/img/avatars/game/elfs/elf010.jpg" alt="" /></label></span><span><input id="ava64" type="radio" name="avatar_id" value="64" class="r_ava_input"><label for="ava64" class="r_l_reg"><img src="/img/avatars/game/elfs/elf012.jpg" alt="" /></label></span><span><input id="ava76" type="radio" name="avatar_id" value="76" class="r_ava_input"><label for="ava76" class="r_l_reg"><img src="/img/avatars/game/elfs/elf014.jpg" alt="" /></label></span><span><input id="ava88" type="radio" name="avatar_id" value="88" class="r_ava_input"><label for="ava88" class="r_l_reg"><img src="/img/avatars/game/elfs/elf016.jpg" alt="" /></label></span><span><input id="ava100" type="radio" name="avatar_id" value="100" class="r_ava_input"><label for="ava100" class="r_l_reg"><img src="/img/avatars/game/elfs/elf018.jpg" alt="" /></label></span><span><input id="ava112" type="radio" name="avatar_id" value="112" class="r_ava_input"><label for="ava112" class="r_l_reg"><img src="/img/avatars/game/elfs/elf020.jpg" alt="" /></label></span><span><input id="ava124" type="radio" name="avatar_id" value="124" class="r_ava_input"><label for="ava124" class="r_l_reg"><img src="/img/avatars/game/elfs/elf022.jpg" alt="" /></label></span><span><input id="ava136" type="radio" name="avatar_id" value="136" class="r_ava_input"><label for="ava136" class="r_l_reg"><img src="/img/avatars/game/elfs/elf024.jpg" alt="" /></label></span>';
}

function orcMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava5" type="radio" name="avatar_id" value="5" class="r_ava_input" checked><label for="ava5" class="r_l_reg"><img src="/img/avatars/game/orcs/orc001.jpg" alt="" /></label></span><span><input id="ava17" type="radio" name="avatar_id" value="17" class="r_ava_input"><label for="ava17" class="r_l_reg"><img src="/img/avatars/game/orcs/orc003.jpg" alt="" /></label></span><span><input id="ava29" type="radio" name="avatar_id" value="29" class="r_ava_input"><label for="ava29" class="r_l_reg"><img src="/img/avatars/game/orcs/orc005.jpg" alt="" /></label></span><span><input id="ava41" type="radio" name="avatar_id" value="41" class="r_ava_input"><label for="ava41" class="r_l_reg"><img src="/img/avatars/game/orcs/orc007.jpg" alt="" /></label></span><span><input id="ava53" type="radio" name="avatar_id" value="53" class="r_ava_input"><label for="ava53" class="r_l_reg"><img src="/img/avatars/game/orcs/orc009.jpg" alt="" /></label></span><span><input id="ava65" type="radio" name="avatar_id" value="65" class="r_ava_input"><label for="ava65" class="r_l_reg"><img src="/img/avatars/game/orcs/orc011.jpg" alt="" /></label></span><span><input id="ava77" type="radio" name="avatar_id" value="77" class="r_ava_input"><label for="ava77" class="r_l_reg"><img src="/img/avatars/game/orcs/orc013.jpg" alt="" /></label></span><span><input id="ava89" type="radio" name="avatar_id" value="89" class="r_ava_input"><label for="ava89" class="r_l_reg"><img src="/img/avatars/game/orcs/orc015.jpg" alt="" /></label></span><span><input id="ava101" type="radio" name="avatar_id" value="101" class="r_ava_input"><label for="ava101" class="r_l_reg"><img src="/img/avatars/game/orcs/orc017.jpg" alt="" /></label></span><span><input id="ava113" type="radio" name="avatar_id" value="113" class="r_ava_input"><label for="ava113" class="r_l_reg"><img src="/img/avatars/game/orcs/orc019.jpg" alt="" /></label></span><span><input id="ava125" type="radio" name="avatar_id" value="125" class="r_ava_input"><label for="ava125" class="r_l_reg"><img src="/img/avatars/game/orcs/orc021.jpg" alt="" /></label></span><span><input id="ava137" type="radio" name="avatar_id" value="137" class="r_ava_input"><label for="ava137" class="r_l_reg"><img src="/img/avatars/game/orcs/orc023.jpg" alt="" /></label></span>';
}

function orcFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava6" type="radio" name="avatar_id" value="6" class="r_ava_input" checked><label for="ava6" class="r_l_reg"><img src="/img/avatars/game/orcs/orc002.jpg" alt="" /></label></span><span><input id="ava18" type="radio" name="avatar_id" value="18" class="r_ava_input"><label for="ava18" class="r_l_reg"><img src="/img/avatars/game/orcs/orc004.jpg" alt="" /></label></span><span><input id="ava30" type="radio" name="avatar_id" value="30" class="r_ava_input"><label for="ava30" class="r_l_reg"><img src="/img/avatars/game/orcs/orc006.jpg" alt="" /></label></span><span><input id="ava42" type="radio" name="avatar_id" value="42" class="r_ava_input"><label for="ava42" class="r_l_reg"><img src="/img/avatars/game/orcs/orc008.jpg" alt="" /></label></span><span><input id="ava54" type="radio" name="avatar_id" value="54" class="r_ava_input"><label for="ava54" class="r_l_reg"><img src="/img/avatars/game/orcs/orc010.jpg" alt="" /></label></span><span><input id="ava66" type="radio" name="avatar_id" value="66" class="r_ava_input"><label for="ava66" class="r_l_reg"><img src="/img/avatars/game/orcs/orc012.jpg" alt="" /></label></span><span><input id="ava78" type="radio" name="avatar_id" value="78" class="r_ava_input"><label for="ava78" class="r_l_reg"><img src="/img/avatars/game/orcs/orc014.jpg" alt="" /></label></span><span><input id="ava90" type="radio" name="avatar_id" value="90" class="r_ava_input"><label for="ava90" class="r_l_reg"><img src="/img/avatars/game/orcs/orc016.jpg" alt="" /></label></span><span><input id="ava102" type="radio" name="avatar_id" value="102" class="r_ava_input"><label for="ava102" class="r_l_reg"><img src="/img/avatars/game/orcs/orc018.jpg" alt="" /></label></span><span><input id="ava114" type="radio" name="avatar_id" value="114" class="r_ava_input"><label for="ava114" class="r_l_reg"><img src="/img/avatars/game/orcs/orc020.jpg" alt="" /></label></span><span><input id="ava126" type="radio" name="avatar_id" value="126" class="r_ava_input"><label for="ava126" class="r_l_reg"><img src="/img/avatars/game/orcs/orc022.jpg" alt="" /></label></span><span><input id="ava138" type="radio" name="avatar_id" value="138" class="r_ava_input"><label for="ava138" class="r_l_reg"><img src="/img/avatars/game/orcs/orc024.jpg" alt="" /></label></span>';
}

function dwarfMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava7" type="radio" name="avatar_id" value="7" class="r_ava_input" checked><label for="ava7" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf001.jpg" alt="" /></label></span><span><input id="ava19" type="radio" name="avatar_id" value="19" class="r_ava_input"><label for="ava19" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf003.jpg" alt="" /></label></span><span><input id="ava31" type="radio" name="avatar_id" value="31" class="r_ava_input"><label for="ava31" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf005.jpg" alt="" /></label></span><span><input id="ava43" type="radio" name="avatar_id" value="43" class="r_ava_input"><label for="ava43" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf007.jpg" alt="" /></label></span><span><input id="ava55" type="radio" name="avatar_id" value="55" class="r_ava_input"><label for="ava55" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf009.jpg" alt="" /></label></span><span><input id="ava67" type="radio" name="avatar_id" value="67" class="r_ava_input"><label for="ava67" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf011.jpg" alt="" /></label></span><span><input id="ava79" type="radio" name="avatar_id" value="79" class="r_ava_input"><label for="ava79" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf013.jpg" alt="" /></label></span><span><input id="ava91" type="radio" name="avatar_id" value="91" class="r_ava_input"><label for="ava91" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf015.jpg" alt="" /></label></span><span><input id="ava103" type="radio" name="avatar_id" value="103" class="r_ava_input"><label for="ava103" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf017.jpg" alt="" /></label></span><span><input id="ava115" type="radio" name="avatar_id" value="115" class="r_ava_input"><label for="ava115" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf019.jpg" alt="" /></label></span><span><input id="ava127" type="radio" name="avatar_id" value="127" class="r_ava_input"><label for="ava127" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf021.jpg" alt="" /></label></span><span><input id="ava139" type="radio" name="avatar_id" value="139" class="r_ava_input"><label for="ava139" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf023.jpg" alt="" /></label></span>';
}

function dwarfFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava8" type="radio" name="avatar_id" value="8" class="r_ava_input" checked><label for="ava8" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf002.jpg" alt="" /></label></span><span><input id="ava20" type="radio" name="avatar_id" value="20" class="r_ava_input"><label for="ava20" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf004.jpg" alt="" /></label></span><span><input id="ava32" type="radio" name="avatar_id" value="32" class="r_ava_input"><label for="ava32" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf006.jpg" alt="" /></label></span><span><input id="ava44" type="radio" name="avatar_id" value="44" class="r_ava_input"><label for="ava44" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf008.jpg" alt="" /></label></span><span><input id="ava56" type="radio" name="avatar_id" value="56" class="r_ava_input"><label for="ava56" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf010.jpg" alt="" /></label></span><span><input id="ava68" type="radio" name="avatar_id" value="68" class="r_ava_input"><label for="ava68" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf012.jpg" alt="" /></label></span><span><input id="ava80" type="radio" name="avatar_id" value="80" class="r_ava_input"><label for="ava80" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf014.jpg" alt="" /></label></span><span><input id="ava92" type="radio" name="avatar_id" value="92" class="r_ava_input"><label for="ava92" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf016.jpg" alt="" /></label></span><span><input id="ava104" type="radio" name="avatar_id" value="104" class="r_ava_input"><label for="ava104" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf018.jpg" alt="" /></label></span><span><input id="ava116" type="radio" name="avatar_id" value="116" class="r_ava_input"><label for="ava116" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf020.jpg" alt="" /></label></span><span><input id="ava128" type="radio" name="avatar_id" value="128" class="r_ava_input"><label for="ava128" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf022.jpg" alt="" /></label></span><span><input id="ava140" type="radio" name="avatar_id" value="140" class="r_ava_input"><label for="ava140" class="r_l_reg"><img src="/img/avatars/game/dwarfs/dwarf024.jpg" alt="" /></label></span>';
}

function angelMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava9" type="radio" name="avatar_id" value="9" class="r_ava_input" checked><label for="ava9" class="r_l_reg"><img src="/img/avatars/game/angels/angel001.jpg" alt="" /></label></span><span><input id="ava21" type="radio" name="avatar_id" value="21" class="r_ava_input"><label for="ava21" class="r_l_reg"><img src="/img/avatars/game/angels/angel003.jpg" alt="" /></label></span><span><input id="ava33" type="radio" name="avatar_id" value="33" class="r_ava_input"><label for="ava33" class="r_l_reg"><img src="/img/avatars/game/angels/angel005.jpg" alt="" /></label></span><span><input id="ava45" type="radio" name="avatar_id" value="45" class="r_ava_input"><label for="ava45" class="r_l_reg"><img src="/img/avatars/game/angels/angel007.jpg" alt="" /></label></span><span><input id="ava57" type="radio" name="avatar_id" value="57" class="r_ava_input"><label for="ava57" class="r_l_reg"><img src="/img/avatars/game/angels/angel009.jpg" alt="" /></label></span><span><input id="ava69" type="radio" name="avatar_id" value="69" class="r_ava_input"><label for="ava69" class="r_l_reg"><img src="/img/avatars/game/angels/angel011.jpg" alt="" /></label></span><span><input id="ava81" type="radio" name="avatar_id" value="81" class="r_ava_input"><label for="ava81" class="r_l_reg"><img src="/img/avatars/game/angels/angel013.jpg" alt="" /></label></span><span><input id="ava93" type="radio" name="avatar_id" value="93" class="r_ava_input"><label for="ava93" class="r_l_reg"><img src="/img/avatars/game/angels/angel015.jpg" alt="" /></label></span><span><input id="ava105" type="radio" name="avatar_id" value="105" class="r_ava_input"><label for="ava105" class="r_l_reg"><img src="/img/avatars/game/angels/angel017.jpg" alt="" /></label></span><span><input id="ava117" type="radio" name="avatar_id" value="117" class="r_ava_input"><label for="ava117" class="r_l_reg"><img src="/img/avatars/game/angels/angel019.jpg" alt="" /></label></span><span><input id="ava129" type="radio" name="avatar_id" value="129" class="r_ava_input"><label for="ava129" class="r_l_reg"><img src="/img/avatars/game/angels/angel021.jpg" alt="" /></label></span><span><input id="ava141" type="radio" name="avatar_id" value="141" class="r_ava_input"><label for="ava141" class="r_l_reg"><img src="/img/avatars/game/angels/angel023.jpg" alt="" /></label></span>';
}

function angelFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava10" type="radio" name="avatar_id" value="10" class="r_ava_input" checked><label for="ava10" class="r_l_reg"><img src="/img/avatars/game/angels/angel002.jpg" alt="" /></label></span><span><input id="ava22" type="radio" name="avatar_id" value="22" class="r_ava_input"><label for="ava22" class="r_l_reg"><img src="/img/avatars/game/angels/angel004.jpg" alt="" /></label></span><span><input id="ava34" type="radio" name="avatar_id" value="34" class="r_ava_input"><label for="ava34" class="r_l_reg"><img src="/img/avatars/game/angels/angel006.jpg" alt="" /></label></span><span><input id="ava46" type="radio" name="avatar_id" value="46" class="r_ava_input"><label for="ava46" class="r_l_reg"><img src="/img/avatars/game/angels/angel008.jpg" alt="" /></label></span><span><input id="ava58" type="radio" name="avatar_id" value="58" class="r_ava_input"><label for="ava58" class="r_l_reg"><img src="/img/avatars/game/angels/angel010.jpg" alt="" /></label></span><span><input id="ava70" type="radio" name="avatar_id" value="70" class="r_ava_input"><label for="ava70" class="r_l_reg"><img src="/img/avatars/game/angels/angel012.jpg" alt="" /></label></span><span><input id="ava82" type="radio" name="avatar_id" value="82" class="r_ava_input"><label for="ava82" class="r_l_reg"><img src="/img/avatars/game/angels/angel014.jpg" alt="" /></label></span><span><input id="ava94" type="radio" name="avatar_id" value="94" class="r_ava_input"><label for="ava94" class="r_l_reg"><img src="/img/avatars/game/angels/angel016.jpg" alt="" /></label></span><span><input id="ava106" type="radio" name="avatar_id" value="106" class="r_ava_input"><label for="ava106" class="r_l_reg"><img src="/img/avatars/game/angels/angel018.jpg" alt="" /></label></span><span><input id="ava118" type="radio" name="avatar_id" value="118" class="r_ava_input"><label for="ava118" class="r_l_reg"><img src="/img/avatars/game/angels/angel020.jpg" alt="" /></label></span><span><input id="ava130" type="radio" name="avatar_id" value="130" class="r_ava_input"><label for="ava130" class="r_l_reg"><img src="/img/avatars/game/angels/angel022.jpg" alt="" /></label></span><span><input id="ava142" type="radio" name="avatar_id" value="142" class="r_ava_input"><label for="ava142" class="r_l_reg"><img src="/img/avatars/game/angels/angel024.jpg" alt="" /></label></span>';
}

function demonMaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava11" type="radio" name="avatar_id" value="11" class="r_ava_input" checked><label for="ava11" class="r_l_reg"><img src="/img/avatars/game/demons/demon001.jpg" alt="" /></label></span><span><input id="ava23" type="radio" name="avatar_id" value="23" class="r_ava_input"><label for="ava23" class="r_l_reg"><img src="/img/avatars/game/demons/demon003.jpg" alt="" /></label></span><span><input id="ava35" type="radio" name="avatar_id" value="35" class="r_ava_input"><label for="ava35" class="r_l_reg"><img src="/img/avatars/game/demons/demon005.jpg" alt="" /></label></span><span><input id="ava47" type="radio" name="avatar_id" value="47" class="r_ava_input"><label for="ava47" class="r_l_reg"><img src="/img/avatars/game/demons/demon007.jpg" alt="" /></label></span><span><input id="ava59" type="radio" name="avatar_id" value="59" class="r_ava_input"><label for="ava59" class="r_l_reg"><img src="/img/avatars/game/demons/demon009.jpg" alt="" /></label></span><span><input id="ava71" type="radio" name="avatar_id" value="71" class="r_ava_input"><label for="ava71" class="r_l_reg"><img src="/img/avatars/game/demons/demon011.jpg" alt="" /></label></span><span><input id="ava83" type="radio" name="avatar_id" value="83" class="r_ava_input"><label for="ava83" class="r_l_reg"><img src="/img/avatars/game/demons/demon013.jpg" alt="" /></label></span><span><input id="ava95" type="radio" name="avatar_id" value="95" class="r_ava_input"><label for="ava95" class="r_l_reg"><img src="/img/avatars/game/demons/demon015.jpg" alt="" /></label></span><span><input id="ava107" type="radio" name="avatar_id" value="107" class="r_ava_input"><label for="ava107" class="r_l_reg"><img src="/img/avatars/game/demons/demon017.jpg" alt="" /></label></span><span><input id="ava119" type="radio" name="avatar_id" value="119" class="r_ava_input"><label for="ava119" class="r_l_reg"><img src="/img/avatars/game/demons/demon019.jpg" alt="" /></label></span><span><input id="ava131" type="radio" name="avatar_id" value="131" class="r_ava_input"><label for="ava131" class="r_l_reg"><img src="/img/avatars/game/demons/demon021.jpg" alt="" /></label></span><span><input id="ava143" type="radio" name="avatar_id" value="143" class="r_ava_input"><label for="ava143" class="r_l_reg"><img src="/img/avatars/game/demons/demon023.jpg" alt="" /></label></span>';
}

function demonFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML = '<span><input id="ava12" type="radio" name="avatar_id" value="12" class="r_ava_input" checked><label for="ava12" class="r_l_reg"><img src="/img/avatars/game/demons/demon002.jpg" alt="" /></label></span><span><input id="ava24" type="radio" name="avatar_id" value="24" class="r_ava_input"><label for="ava24" class="r_l_reg"><img src="/img/avatars/game/demons/demon004.jpg" alt="" /></label></span><span><input id="ava36" type="radio" name="avatar_id" value="36" class="r_ava_input"><label for="ava36" class="r_l_reg"><img src="/img/avatars/game/demons/demon006.jpg" alt="" /></label></span><span><input id="ava48" type="radio" name="avatar_id" value="48" class="r_ava_input"><label for="ava48" class="r_l_reg"><img src="/img/avatars/game/demons/demon008.jpg" alt="" /></label></span><span><input id="ava60" type="radio" name="avatar_id" value="60" class="r_ava_input"><label for="ava60" class="r_l_reg"><img src="/img/avatars/game/demons/demon010.jpg" alt="" /></label></span><span><input id="ava72" type="radio" name="avatar_id" value="72" class="r_ava_input"><label for="ava72" class="r_l_reg"><img src="/img/avatars/game/demons/demon012.jpg" alt="" /></label></span><span><input id="ava84" type="radio" name="avatar_id" value="84" class="r_ava_input"><label for="ava84" class="r_l_reg"><img src="/img/avatars/game/demons/demon014.jpg" alt="" /></label></span><span><input id="ava96" type="radio" name="avatar_id" value="96" class="r_ava_input"><label for="ava96" class="r_l_reg"><img src="/img/avatars/game/demons/demon016.jpg" alt="" /></label></span><span><input id="ava108" type="radio" name="avatar_id" value="108" class="r_ava_input"><label for="ava108" class="r_l_reg"><img src="/img/avatars/game/demons/demon018.jpg" alt="" /></label></span><span><input id="ava120" type="radio" name="avatar_id" value="120" class="r_ava_input"><label for="ava120" class="r_l_reg"><img src="/img/avatars/game/demons/demon020.jpg" alt="" /></label></span><span><input id="ava132" type="radio" name="avatar_id" value="132" class="r_ava_input"><label for="ava132" class="r_l_reg"><img src="/img/avatars/game/demons/demon022.jpg" alt="" /></label></span><span><input id="ava144" type="radio" name="avatar_id" value="144" class="r_ava_input"><label for="ava144" class="r_l_reg"><img src="/img/avatars/game/demons/demon024.jpg" alt="" /></label></span>';
}

function humansMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="1" onclick="classChange(this.value)" checked><span>Паладин</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="2" onclick="classChange(this.value)"><span>Убийца</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="3" onclick="classChange(this.value)"><span>Рейнджер</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="4" onclick="classChange(this.value)"><span>Маг Стихий</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="5" onclick="classChange(this.value)"><span>Жрец</span>' +
        '</label></div>';
}

function elfsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="6" onclick="classChange(this.value)" checked><span>Хранитель</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="7" onclick="classChange(this.value)"><span>Дневной охотник</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="8" onclick="classChange(this.value)"><span>Ночной охотник</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="9" onclick="classChange(this.value)"><span>Заклинатель</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="10" onclick="classChange(this.value)"><span>Оракул</span>' +
        '</label></div>';
}

function orcsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="11" onclick="classChange(this.value)" checked><span>Разрушитель</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="12" onclick="classChange(this.value)"><span>Титан</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="13" onclick="classChange(this.value)"><span>Берсерк</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="14" onclick="classChange(this.value)"><span>Шаман</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="15" onclick="classChange(this.value)"><span>Боевой маг</span>' +
        '</label></div>';
}

function dwarfsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="16" onclick="classChange(this.value)" checked><span>Страж</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="17" onclick="classChange(this.value)"><span>Искатель сокровищ</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="18" onclick="classChange(this.value)"><span>Арбалетчик</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="19" onclick="classChange(this.value)"><span>Алхимик</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="20" onclick="classChange(this.value)"><span>Отшельник</span>' +
        '</label></div>';
}

function angelsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="21" onclick="classChange(this.value)" checked><span>Архангел</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="22" onclick="classChange(this.value)"><span>Малахим</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="23" onclick="classChange(this.value)"><span>Феникс</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="24" onclick="classChange(this.value)"><span>Серафим</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="25" onclick="classChange(this.value)"><span>Арелим</span>' +
        '</label></div>';
}

function demonsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="26" onclick="classChange(this.value)" checked><span>Рыцарь ада</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="27" onclick="classChange(this.value)"><span>Мститель</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="28" onclick="classChange(this.value)"><span>Архонт</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="29" onclick="classChange(this.value)"><span>Душегуб</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="30" onclick="classChange(this.value)"><span>Инкуб</span>' +
        '</label></div>';
}

function humansFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="1" onclick="classChange(this.value)" checked><span>Паладин</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="2" onclick="classChange(this.value)"><span>Убийца</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="3" onclick="classChange(this.value)"><span>Рейнджер</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="4" onclick="classChange(this.value)"><span>Маг Стихий</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="5" onclick="classChange(this.value)"><span>Жрица</span>' +
        '</label></div>';
}

function elfsFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="6" onclick="classChange(this.value)" checked><span>Хранительница</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="7" onclick="classChange(this.value)"><span>Дневной охотник</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="8" onclick="classChange(this.value)"><span>Ночной охотник</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="9" onclick="classChange(this.value)"><span>Заклинательница</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="10" onclick="classChange(this.value)"><span>Оракул</span>' +
        '</label></div>';
}

function orcsFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="11" onclick="classChange(this.value)" checked><span>Разрушительница</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="12" onclick="classChange(this.value)"><span>Титан</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="13" onclick="classChange(this.value)"><span>Берсерк</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="14" onclick="classChange(this.value)"><span>Шаман</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="15" onclick="classChange(this.value)"><span>Боевой маг</span>' +
        '</label></div>';
}

function dwarfsFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="16" onclick="classChange(this.value)" checked><span>Страж</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="17" onclick="classChange(this.value)"><span>Искательница сокровищ</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="18" onclick="classChange(this.value)"><span>Арбалетчица</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="19" onclick="classChange(this.value)"><span>Алхимик</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="20" onclick="classChange(this.value)"><span>Отшельница</span>' +
        '</label></div>';
}

function angelsFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="21" onclick="classChange(this.value)" checked><span>Архангел</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="22" onclick="classChange(this.value)"><span>Малахим</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="23" onclick="classChange(this.value)"><span>Феникс</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="24" onclick="classChange(this.value)"><span>Серафим</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="25" onclick="classChange(this.value)"><span>Арелим</span>' +
        '</label></div>';
}

function demonsFemaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="26" onclick="classChange(this.value)" checked><span>Рыцарь ада</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="27" onclick="classChange(this.value)"><span>Мстительница</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="28" onclick="classChange(this.value)"><span>Архонт</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="29" onclick="classChange(this.value)"><span>Душегуб</span>' +
        '</label></div>' +
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="30" onclick="classChange(this.value)"><span>Суккуб</span>' +
        '</label></div>';
}

function baseStats(str = 0, int = 0, dex = 0, will = 0, end = 0, perc = 0, char = 0, luck = 0) {
    let string =
        '<div class="r_n_c_l">' +
        '<p>Сила: ' + str + '</p>' +
        '<p>Интеллект: ' + int + '</p>' +
        '<p>Ловкость: ' + dex + '</p>' +
        '<p>Воля: ' + will + '</p>' +
        '<p>Телосложение: ' + end + '</p>' +
        '<p>Восприятие: ' + perc + '</p>' +
        '<p>Харизма: ' + char + '</p>' +
        '<p>Удача: ' + luck + '</p>';

    return string + '</div>';
}

function derivedStats(life = 0, mana = 0, stam = 0, horror = 0, special = '') {
    return '<div class="r_n_c_r">' +
        '<p>Здоровье: ' + life + '</p>' +
        '<p>Мана: ' + mana + '</p>' +
        '<p>Выносливость: ' + stam + '</p>' +
        '<p>Предел<span class="r_s_hidden">_</span>ужаса: ' + horror + '</p>' +
        '<p><br />' + special + '</p>' +
        '</div>'
}

function ability(icon1, desc1, icon2, desc2, icon3, decs3) {
    return '<table><tr>' +
        '<td><img src="/icon/ability/' + icon1 + '.png" align="left" alt="" /></td>' +
        '<td><p>' + desc1 + '</p></td>' +
        '</tr><tr>' +
        '<td><img src="/icon/ability/' + icon2 + '.png" align="left" alt="" /></td>' +
        '<td><p>' + desc2 + '</p></td>' +
        '</tr><tr>' +
        '<td><img src="/icon/ability/' + icon3 + '.png" align="left" alt="" /></td>' +
        '<td><p>' + decs3 + '</p></td>' +
        '</tr></table>';
}

function setRegFields() {
    let fields = document.getElementById('reg_fields');
    let newField = document.createElement('input');
    newField.setAttribute('type', 'hidden');
    newField.setAttribute('name', 'user_agent');
    newField.setAttribute('value', navigator.userAgent);
    fields.appendChild(newField);
}

function reg_send(form) {
    document.getElementById('submit_id').style.display = 'none';
    document.getElementById('reg_img_loading').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function () {
    raceChange('1');
});
