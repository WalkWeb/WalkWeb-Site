<?php
use App\Domain\Auth\AuthInterface;
?>
<div class="main_both"></div>
<div class="demon_footer"></div>
</div>
</div>
<div id="footer_box">
    <span id="footer_r"></span>
    <span id="footer_l"></span>
    <div id="footer_m"></div>
</div>
<div id="new_back_to_top">
    <a rel="nofollow" href="#app" title="Наверх"></a>
</div>
</div>
<?php
if ($this->container->exist('user')) {
    /** @var AuthInterface $user */
    $user = $this->container->getUser();

    if ($count = count($user->getNotices())) {

        echo '<div class="up_notice_box">
                <div id="up_open_notice" onclick="openNotice()">
                    <p><span>' . $count . '</span></p>
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

        if ($count > 2) {
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