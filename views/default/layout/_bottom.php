<?php

use App\Domain\Auth\AuthInterface;

?>
<div class="right_box">
    <div class="right_content">
        <div class="right_content_head">
            Персона
        </div>
        <?php
        if ($this->container->exist('user')) {
            /** @var AuthInterface $user */
            $user = $this->container->get('user');
            echo '<div class="right_content_ava" style="background-image: url(' . $user->getAvatar() . ');">
                      <a href="/profile" class="full"></a>
                      </div>
                      <div class="exp_background"></div>
                      <div class="exp_fill" id="auth_exp_width" style="width: ' . $user->getLevel()->getExpBarWeight() . '%"></div>
                      <div class="exp_text">
                      <p><abbr title="Ваш опыт"><span id="auth_exp_at_lvl">' . $user->getLevel()->getExpAtLevel() . '</span>/<span id="auth_exp_to_lvl">' . $user->getLevel()->getExpToLevel() . '</span></abbr></p>
                      </div>
                      <div class="right_content_body">
                      <p><span class="lvl">' . $user->getLevel()->getLevel() . '</span> <a href="/u/' . $user->getName() . '" class="profile_link" title="Профиль">' . $user->getName() . '</a></p>
                      </div>
                      <div class="energy_background"></div>
                      <div class="energy_fill" id="energy_bar_div" style="width: ' . $user->getEnergy()->getEnergyWeight() . '%;"></div>
                      <div class="energy_text">
                      <p><abbr title="Ваша энергия"><span id="energy">' . $user->getEnergy()->getEnergy() . '</span>/<span id="energy_max">' . $user->getEnergy()->getMaxEnergy() . '</span></abbr></p>
                      </div>
                      <div class="residue_background"></div>
                      <div class="residue_fill" id="second_bar_div" style="width: ' . $user->getEnergy()->getRestoreWeight() . '%;"></div>';

        } else {
            echo '<div class="right_content_body">
                        <p>
                            <a href="/login" title="">Вход</a> / <a href="/registration/main" title="">Регистрация</a>
                        </p>
                      </div>';
        }
        ?>
    </div>
</div>
</div>
<div class="box line"></div>
<div class="box footer_image"></div>
<div class="footer_box">
    <div class="box footer_content">
        <p>
            <a href="#">О проекте WalkWeb</a><br />
            <a href="/users/1">Пользователи</a><br />
            <a href="/functionality">Функционал</a><br />
            <a href="#">Правила</a>
        </p>
    </div>
</div>
<?php
if ($this->container->exist('user')) {
    /** @var AuthInterface $user */
    $user = $this->container->getUser();

    if ($user->getNotices()->getTotal() > 0) {

        echo '<div class="up_notice_box">
                <div id="up_open_notice" onclick="openNotice()">
                    <p><span>' . $user->getNotices()->getTotal() . '</span></p>
                </div>
                <div id="up_notice_content">';

        foreach ($user->getNotices() as $notice) {
            echo    '<div class="up_notice_row" id="notice_' . $notice->getId() . '">
                        <div class="up_notice_row_l">
                            <p>' . $notice->getMessage() . '</p>
                        </div>
                        <div class="up_notice_row_r">
                            <span onclick="closeNotice(\'' . $notice->getId() . '\')">×</span>
                        </div>
                    </div>';
        }

        if ($user->getNotices()->getTotal() > 2) {
            echo '<div class="up_notice_car">
                    <p><span onclick="closeAllNotice()">закрыть все</span></p>
                  </div>';
        }

        echo '</div></div>';
    }

    echo
        '<script>
            let interval = 1000;
            let expected = Date.now() + interval;
            let energy = ' . $user->getEnergy()->getEnergy() . ';
            let energy_max = ' . $user->getEnergy()->getMaxEnergy() . ';
            let second = ' . $user->getEnergy()->getResidue() . ';
            let second_max = ' . ENERGY_RESTORE . ';
            let energy_bar;
            let second_bar;
        </script>
        <script src="/js/energy.js?v=1.0"></script>';
}
?>
</body>
</html>