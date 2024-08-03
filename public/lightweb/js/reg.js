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
    setAvatars();
    setRegFields();
}

function classChange(value) {
    _class = value;
}

function floorChange(value) {
    _floor = value;
    setAvatars();
    setClasses();
}

function setAvatars() {
    if (_race === '1' && _floor === '1') {
        analystsMaleAvatars();
    }
    if (_race === '2' && _floor === '1') {
        designerMaleAvatars();
    }
    if (_race === '3' && _floor === '1') {
        devopsMaleAvatars();
    }
    if (_race === '4' && _floor === '1') {
        internMaleAvatars();
    }
    if (_race === '5' && _floor === '1') {
        programmerMaleAvatars();
    }
    if (_race === '6' && _floor === '1') {
        managerMaleAvatars();
    }
    if (_race === '1' && _floor === '2') {
        analystsFemaleAvatars();
    }
    if (_race === '2' && _floor === '2') {
        designerFemaleAvatars();
    }
    if (_race === '3' && _floor === '2') {
        devopsFemaleAvatars();
    }
    if (_race === '4' && _floor === '2') {
        internFemaleAvatars();
    }
    if (_race === '5' && _floor === '2') {
        programmerFemaleAvatars();
    }
    if (_race === '6' && _floor === '2') {
        managerFemaleAvatars();
    }
}

function setClasses() {
    if (_race === '1') {
        humansMaleClasses();
        _class = '1';
    }
    if (_race === '2') {
        elfsMaleClasses();
        _class = '2';
    }
    if (_race === '3') {
        orcsMaleClasses();
        _class = '3';
    }
    if (_race === '4') {
        dwarfsMaleClasses();
        _class = '4';
    }
    if (_race === '5') {
        angelsMaleClasses();
        _class = '5';
    }
    if (_race === '6') {
        demonsMaleClasses();
        _class = '6';
    }
}

function analystsMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava1" type="radio" name="avatar_id" value="1" class="r_ava_input" checked>' +
        '<label for="ava1" class="r_l_reg"><img src="/img/avatars/it/analyst/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava13" type="radio" name="avatar_id" value="2" class="r_ava_input">' +
        '<label for="ava13" class="r_l_reg"><img src="/img/avatars/it/analyst/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava25" type="radio" name="avatar_id" value="3" class="r_ava_input">' +
        '<label for="ava25" class="r_l_reg"><img src="/img/avatars/it/analyst/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava37" type="radio" name="avatar_id" value="4" class="r_ava_input">' +
        '<label for="ava37" class="r_l_reg"><img src="/img/avatars/it/analyst/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava49" type="radio" name="avatar_id" value="5" class="r_ava_input">' +
        '<label for="ava49" class="r_l_reg"><img src="/img/avatars/it/analyst/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava61" type="radio" name="avatar_id" value="6" class="r_ava_input">' +
        '<label for="ava61" class="r_l_reg"><img src="/img/avatars/it/analyst/male/06.jpg" alt="" /></label></span>';
}

function analystsFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava2" type="radio" name="avatar_id" value="7" class="r_ava_input" checked>' +
        '<label for="ava2" class="r_l_reg"><img src="/img/avatars/it/analyst/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava14" type="radio" name="avatar_id" value="8" class="r_ava_input">' +
        '<label for="ava14" class="r_l_reg"><img src="/img/avatars/it/analyst/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava26" type="radio" name="avatar_id" value="9" class="r_ava_input">' +
        '<label for="ava26" class="r_l_reg"><img src="/img/avatars/it/analyst/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava38" type="radio" name="avatar_id" value="10" class="r_ava_input">' +
        '<label for="ava38" class="r_l_reg"><img src="/img/avatars/it/analyst/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava50" type="radio" name="avatar_id" value="11" class="r_ava_input">' +
        '<label for="ava50" class="r_l_reg"><img src="/img/avatars/it/analyst/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava62" type="radio" name="avatar_id" value="12" class="r_ava_input">' +
        '<label for="ava62" class="r_l_reg"><img src="/img/avatars/it/analyst/female/06.jpg" alt="" /></label></span>';
}

function designerMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava3" type="radio" name="avatar_id" value="13" class="r_ava_input" checked>' +
        '<label for="ava3" class="r_l_reg"><img src="/img/avatars/it/designer/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava15" type="radio" name="avatar_id" value="14" class="r_ava_input">' +
        '<label for="ava15" class="r_l_reg"><img src="/img/avatars/it/designer/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava27" type="radio" name="avatar_id" value="15" class="r_ava_input">' +
        '<label for="ava27" class="r_l_reg"><img src="/img/avatars/it/designer/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava39" type="radio" name="avatar_id" value="16" class="r_ava_input">' +
        '<label for="ava39" class="r_l_reg"><img src="/img/avatars/it/designer/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava51" type="radio" name="avatar_id" value="17" class="r_ava_input">' +
        '<label for="ava51" class="r_l_reg"><img src="/img/avatars/it/designer/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava63" type="radio" name="avatar_id" value="18" class="r_ava_input">' +
        '<label for="ava63" class="r_l_reg"><img src="/img/avatars/it/designer/male/06.jpg" alt="" /></label></span>';
}

function designerFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava4" type="radio" name="avatar_id" value="19" class="r_ava_input" checked>' +
        '<label for="ava4" class="r_l_reg"><img src="/img/avatars/it/designer/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava16" type="radio" name="avatar_id" value="20" class="r_ava_input">' +
        '<label for="ava16" class="r_l_reg"><img src="/img/avatars/it/designer/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava28" type="radio" name="avatar_id" value="21" class="r_ava_input">' +
        '<label for="ava28" class="r_l_reg"><img src="/img/avatars/it/designer/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava40" type="radio" name="avatar_id" value="22" class="r_ava_input">' +
        '<label for="ava40" class="r_l_reg"><img src="/img/avatars/it/designer/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava52" type="radio" name="avatar_id" value="23" class="r_ava_input">' +
        '<label for="ava52" class="r_l_reg"><img src="/img/avatars/it/designer/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava64" type="radio" name="avatar_id" value="24" class="r_ava_input">' +
        '<label for="ava64" class="r_l_reg"><img src="/img/avatars/it/designer/female/06.jpg" alt="" /></label></span>';
}

function devopsMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava5" type="radio" name="avatar_id" value="25" class="r_ava_input" checked>' +
        '<label for="ava5" class="r_l_reg"><img src="/img/avatars/it/devops/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava17" type="radio" name="avatar_id" value="26" class="r_ava_input">' +
        '<label for="ava17" class="r_l_reg"><img src="/img/avatars/it/devops/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava29" type="radio" name="avatar_id" value="27" class="r_ava_input">' +
        '<label for="ava29" class="r_l_reg"><img src="/img/avatars/it/devops/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava41" type="radio" name="avatar_id" value="28" class="r_ava_input">' +
        '<label for="ava41" class="r_l_reg"><img src="/img/avatars/it/devops/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava53" type="radio" name="avatar_id" value="29" class="r_ava_input">' +
        '<label for="ava53" class="r_l_reg"><img src="/img/avatars/it/devops/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava65" type="radio" name="avatar_id" value="30" class="r_ava_input">' +
        '<label for="ava65" class="r_l_reg"><img src="/img/avatars/it/devops/male/06.jpg" alt="" /></label></span>';
}

function devopsFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava6" type="radio" name="avatar_id" value="31" class="r_ava_input" checked>' +
        '<label for="ava6" class="r_l_reg"><img src="/img/avatars/it/devops/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava18" type="radio" name="avatar_id" value="32" class="r_ava_input">' +
        '<label for="ava18" class="r_l_reg"><img src="/img/avatars/it/devops/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava30" type="radio" name="avatar_id" value="33" class="r_ava_input">' +
        '<label for="ava30" class="r_l_reg"><img src="/img/avatars/it/devops/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava42" type="radio" name="avatar_id" value="34" class="r_ava_input">' +
        '<label for="ava42" class="r_l_reg"><img src="/img/avatars/it/devops/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava54" type="radio" name="avatar_id" value="35" class="r_ava_input">' +
        '<label for="ava54" class="r_l_reg"><img src="/img/avatars/it/devops/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava66" type="radio" name="avatar_id" value="36" class="r_ava_input">' +
        '<label for="ava66" class="r_l_reg"><img src="/img/avatars/it/devops/female/06.jpg" alt="" /></label></span>';
}

function internMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava7" type="radio" name="avatar_id" value="37" class="r_ava_input" checked>' +
        '<label for="ava7" class="r_l_reg"><img src="/img/avatars/it/intern/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava19" type="radio" name="avatar_id" value="38" class="r_ava_input">' +
        '<label for="ava19" class="r_l_reg"><img src="/img/avatars/it/intern/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava31" type="radio" name="avatar_id" value="39" class="r_ava_input">' +
        '<label for="ava31" class="r_l_reg"><img src="/img/avatars/it/intern/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava43" type="radio" name="avatar_id" value="40" class="r_ava_input">' +
        '<label for="ava43" class="r_l_reg"><img src="/img/avatars/it/intern/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava55" type="radio" name="avatar_id" value="41" class="r_ava_input">' +
        '<label for="ava55" class="r_l_reg"><img src="/img/avatars/it/intern/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava67" type="radio" name="avatar_id" value="42" class="r_ava_input">' +
        '<label for="ava67" class="r_l_reg"><img src="/img/avatars/it/intern/male/06.jpg" alt="" /></label></span>';
}

function internFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava8" type="radio" name="avatar_id" value="43" class="r_ava_input" checked>' +
        '<label for="ava8" class="r_l_reg"><img src="/img/avatars/it/intern/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava20" type="radio" name="avatar_id" value="44" class="r_ava_input">' +
        '<label for="ava20" class="r_l_reg"><img src="/img/avatars/it/intern/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava32" type="radio" name="avatar_id" value="45" class="r_ava_input">' +
        '<label for="ava32" class="r_l_reg"><img src="/img/avatars/it/intern/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava44" type="radio" name="avatar_id" value="46" class="r_ava_input">' +
        '<label for="ava44" class="r_l_reg"><img src="/img/avatars/it/intern/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava56" type="radio" name="avatar_id" value="47" class="r_ava_input">' +
        '<label for="ava56" class="r_l_reg"><img src="/img/avatars/it/intern/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava68" type="radio" name="avatar_id" value="48" class="r_ava_input">' +
        '<label for="ava68" class="r_l_reg"><img src="/img/avatars/it/intern/female/06.jpg" alt="" /></label></span>';
}

function programmerMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava9" type="radio" name="avatar_id" value="49" class="r_ava_input" checked>' +
        '<label for="ava9" class="r_l_reg"><img src="/img/avatars/it/programmer/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava21" type="radio" name="avatar_id" value="50" class="r_ava_input">' +
        '<label for="ava21" class="r_l_reg"><img src="/img/avatars/it/programmer/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava33" type="radio" name="avatar_id" value="51" class="r_ava_input">' +
        '<label for="ava33" class="r_l_reg"><img src="/img/avatars/it/programmer/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava45" type="radio" name="avatar_id" value="52" class="r_ava_input">' +
        '<label for="ava45" class="r_l_reg"><img src="/img/avatars/it/programmer/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava57" type="radio" name="avatar_id" value="53" class="r_ava_input">' +
        '<label for="ava57" class="r_l_reg"><img src="/img/avatars/it/programmer/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava69" type="radio" name="avatar_id" value="54" class="r_ava_input">' +
        '<label for="ava69" class="r_l_reg"><img src="/img/avatars/it/programmer/male/06.jpg" alt="" /></label></span>';
}

function programmerFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava10" type="radio" name="avatar_id" value="55" class="r_ava_input" checked>' +
        '<label for="ava10" class="r_l_reg"><img src="/img/avatars/it/programmer/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava22" type="radio" name="avatar_id" value="56" class="r_ava_input">' +
        '<label for="ava22" class="r_l_reg"><img src="/img/avatars/it/programmer/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava34" type="radio" name="avatar_id" value="57" class="r_ava_input">' +
        '<label for="ava34" class="r_l_reg"><img src="/img/avatars/it/programmer/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava46" type="radio" name="avatar_id" value="58" class="r_ava_input">' +
        '<label for="ava46" class="r_l_reg"><img src="/img/avatars/it/programmer/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava58" type="radio" name="avatar_id" value="59" class="r_ava_input">' +
        '<label for="ava58" class="r_l_reg"><img src="/img/avatars/it/programmer/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava70" type="radio" name="avatar_id" value="60" class="r_ava_input">' +
        '<label for="ava70" class="r_l_reg"><img src="/img/avatars/it/programmer/female/06.jpg" alt="" /></label></span>';
}

function managerMaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava11" type="radio" name="avatar_id" value="61" class="r_ava_input" checked>' +
        '<label for="ava11" class="r_l_reg"><img src="/img/avatars/it/manager/male/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava23" type="radio" name="avatar_id" value="62" class="r_ava_input">' +
        '<label for="ava23" class="r_l_reg"><img src="/img/avatars/it/manager/male/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava35" type="radio" name="avatar_id" value="63" class="r_ava_input">' +
        '<label for="ava35" class="r_l_reg"><img src="/img/avatars/it/manager/male/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava47" type="radio" name="avatar_id" value="64" class="r_ava_input">' +
        '<label for="ava47" class="r_l_reg"><img src="/img/avatars/it/manager/male/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava59" type="radio" name="avatar_id" value="65" class="r_ava_input">' +
        '<label for="ava59" class="r_l_reg"><img src="/img/avatars/it/manager/male/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava71" type="radio" name="avatar_id" value="66" class="r_ava_input">' +
        '<label for="ava71" class="r_l_reg"><img src="/img/avatars/it/manager/male/06.jpg" alt="" /></label></span>';
}

function managerFemaleAvatars() {
    document.getElementById('avatars_list').innerHTML =
        '<span><input id="ava12" type="radio" name="avatar_id" value="67" class="r_ava_input" checked>' +
        '<label for="ava12" class="r_l_reg"><img src="/img/avatars/it/manager/female/01.jpg" alt="" /></label></span>' +
        '<span><input id="ava24" type="radio" name="avatar_id" value="68" class="r_ava_input">' +
        '<label for="ava24" class="r_l_reg"><img src="/img/avatars/it/manager/female/02.jpg" alt="" /></label></span>' +
        '<span><input id="ava36" type="radio" name="avatar_id" value="69" class="r_ava_input">' +
        '<label for="ava36" class="r_l_reg"><img src="/img/avatars/it/manager/female/03.jpg" alt="" /></label></span>' +
        '<span><input id="ava48" type="radio" name="avatar_id" value="70" class="r_ava_input">' +
        '<label for="ava48" class="r_l_reg"><img src="/img/avatars/it/manager/female/04.jpg" alt="" /></label></span>' +
        '<span><input id="ava60" type="radio" name="avatar_id" value="71" class="r_ava_input">' +
        '<label for="ava60" class="r_l_reg"><img src="/img/avatars/it/manager/female/05.jpg" alt="" /></label></span>' +
        '<span><input id="ava72" type="radio" name="avatar_id" value="72" class="r_ava_input">' +
        '<label for="ava72" class="r_l_reg"><img src="/img/avatars/it/manager/female/06.jpg" alt="" /></label></span>';
}

function humansMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="1" onclick="classChange(this.value)" checked><span>Default-1</span>' +
        '</label></div>';
}

function elfsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="2" onclick="classChange(this.value)" checked><span>Default-2</span>' +
        '</label></div>';
}

function orcsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="3" onclick="classChange(this.value)" checked><span>Default-3</span>' +
        '</label></div>';
}

function dwarfsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="4" onclick="classChange(this.value)" checked><span>Default-4</span>' +
        '</label></div>';
}

function angelsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="5" onclick="classChange(this.value)" checked><span>Default-5</span>' +
        '</label></div>';
}

function demonsMaleClasses() {
    document.getElementById('class_list').innerHTML =
        '<div class="r_class"><label>' +
        '<input type="radio" name="profession_id" value="6" onclick="classChange(this.value)" checked><span>Default-6</span>' +
        '</label></div>';
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
