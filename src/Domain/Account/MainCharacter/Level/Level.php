<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter\Level;

use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeException;
use WalkWeb\NW\AppException;

class Level implements LevelInterface
{
    private string $accountId;
    private string $characterId;
    private int $level;
    private int $exp;
    private int $expToLevel;
    private int $expAtLevel;
    private int $statPoints;
    private SendNoticeActionInterface $sendNoticeAction;

    private static array $levelsData = [
        1   => [
            'exp_to_lvl' => 50,
            'exp_total'  => 0,
        ],
        2   => [
            'exp_to_lvl' => 130,
            'exp_total'  => 50,
        ],
        3   => [
            'exp_to_lvl' => 240,
            'exp_total'  => 180,
        ],
        4   => [
            'exp_to_lvl' => 380,
            'exp_total'  => 420,
        ],
        5   => [
            'exp_to_lvl' => 530,
            'exp_total'  => 800,
        ],
        6   => [
            'exp_to_lvl' => 710,
            'exp_total'  => 1330,
        ],
        7   => [
            'exp_to_lvl' => 910,
            'exp_total'  => 2040,
        ],
        8   => [
            'exp_to_lvl' => 1120,
            'exp_total'  => 2950,
        ],
        9   => [
            'exp_to_lvl' => 1350,
            'exp_total'  => 4070,
        ],
        10  => [
            'exp_to_lvl' => 1590,
            'exp_total'  => 5420,
        ],
        11  => [
            'exp_to_lvl' => 1860,
            'exp_total'  => 7010,
        ],
        12  => [
            'exp_to_lvl' => 2130,
            'exp_total'  => 8870,
        ],
        13  => [
            'exp_to_lvl' => 2420,
            'exp_total'  => 11000,
        ],
        14  => [
            'exp_to_lvl' => 2730,
            'exp_total'  => 13420,
        ],
        15  => [
            'exp_to_lvl' => 3040,
            'exp_total'  => 16150,
        ],
        16  => [
            'exp_to_lvl' => 3370,
            'exp_total'  => 19190,
        ],
        17  => [
            'exp_to_lvl' => 3710,
            'exp_total'  => 22560,
        ],
        18  => [
            'exp_to_lvl' => 4070,
            'exp_total'  => 26270,
        ],
        19  => [
            'exp_to_lvl' => 4440,
            'exp_total'  => 30340,
        ],
        20  => [
            'exp_to_lvl' => 4810,
            'exp_total'  => 34780,
        ],
        21  => [
            'exp_to_lvl' => 5200,
            'exp_total'  => 39590,
        ],
        22  => [
            'exp_to_lvl' => 5610,
            'exp_total'  => 44790,
        ],
        23  => [
            'exp_to_lvl' => 6020,
            'exp_total'  => 50400,
        ],
        24  => [
            'exp_to_lvl' => 6440,
            'exp_total'  => 56420,
        ],
        25  => [
            'exp_to_lvl' => 6880,
            'exp_total'  => 62860,
        ],
        26  => [
            'exp_to_lvl' => 7320,
            'exp_total'  => 69740,
        ],
        27  => [
            'exp_to_lvl' => 7780,
            'exp_total'  => 77060,
        ],
        28  => [
            'exp_to_lvl' => 8240,
            'exp_total'  => 84840,
        ],
        29  => [
            'exp_to_lvl' => 8720,
            'exp_total'  => 93080,
        ],
        30  => [
            'exp_to_lvl' => 9200,
            'exp_total'  => 101800,
        ],
        31  => [
            'exp_to_lvl' => 9700,
            'exp_total'  => 111000,
        ],
        32  => [
            'exp_to_lvl' => 10200,
            'exp_total'  => 120700,
        ],
        33  => [
            'exp_to_lvl' => 10700,
            'exp_total'  => 130900,
        ],
        34  => [
            'exp_to_lvl' => 11200,
            'exp_total'  => 141600,
        ],
        35  => [
            'exp_to_lvl' => 11800,
            'exp_total'  => 152800,
        ],
        36  => [
            'exp_to_lvl' => 12300,
            'exp_total'  => 164600,
        ],
        37  => [
            'exp_to_lvl' => 12900,
            'exp_total'  => 176900,
        ],
        38  => [
            'exp_to_lvl' => 13400,
            'exp_total'  => 189800,
        ],
        39  => [
            'exp_to_lvl' => 14000,
            'exp_total'  => 203200,
        ],
        40  => [
            'exp_to_lvl' => 14600,
            'exp_total'  => 217200,
        ],
        41  => [
            'exp_to_lvl' => 15200,
            'exp_total'  => 231800,
        ],
        42  => [
            'exp_to_lvl' => 15800,
            'exp_total'  => 247000,
        ],
        43  => [
            'exp_to_lvl' => 16400,
            'exp_total'  => 262800,
        ],
        44  => [
            'exp_to_lvl' => 17000,
            'exp_total'  => 279200,
        ],
        45  => [
            'exp_to_lvl' => 17600,
            'exp_total'  => 296200,
        ],
        46  => [
            'exp_to_lvl' => 18200,
            'exp_total'  => 313800,
        ],
        47  => [
            'exp_to_lvl' => 18900,
            'exp_total'  => 332000,
        ],
        48  => [
            'exp_to_lvl' => 19500,
            'exp_total'  => 350900,
        ],
        49  => [
            'exp_to_lvl' => 20200,
            'exp_total'  => 370400,
        ],
        50  => [
            'exp_to_lvl' => 20800,
            'exp_total'  => 390600,
        ],
        51  => [
            'exp_to_lvl' => 21500,
            'exp_total'  => 411400,
        ],
        52  => [
            'exp_to_lvl' => 22200,
            'exp_total'  => 432900,
        ],
        53  => [
            'exp_to_lvl' => 22900,
            'exp_total'  => 455100,
        ],
        54  => [
            'exp_to_lvl' => 23600,
            'exp_total'  => 478000,
        ],
        55  => [
            'exp_to_lvl' => 24300,
            'exp_total'  => 501600,
        ],
        56  => [
            'exp_to_lvl' => 25000,
            'exp_total'  => 525900,
        ],
        57  => [
            'exp_to_lvl' => 25700,
            'exp_total'  => 550900,
        ],
        58  => [
            'exp_to_lvl' => 26400,
            'exp_total'  => 576600,
        ],
        59  => [
            'exp_to_lvl' => 27100,
            'exp_total'  => 603000,
        ],
        60  => [
            'exp_to_lvl' => 27900,
            'exp_total'  => 630100,
        ],
        61  => [
            'exp_to_lvl' => 28600,
            'exp_total'  => 658000,
        ],
        62  => [
            'exp_to_lvl' => 29400,
            'exp_total'  => 686600,
        ],
        63  => [
            'exp_to_lvl' => 30100,
            'exp_total'  => 716000,
        ],
        64  => [
            'exp_to_lvl' => 30900,
            'exp_total'  => 746100,
        ],
        65  => [
            'exp_to_lvl' => 31700,
            'exp_total'  => 777000,
        ],
        66  => [
            'exp_to_lvl' => 32500,
            'exp_total'  => 808700,
        ],
        67  => [
            'exp_to_lvl' => 33300,
            'exp_total'  => 841200,
        ],
        68  => [
            'exp_to_lvl' => 34100,
            'exp_total'  => 874500,
        ],
        69  => [
            'exp_to_lvl' => 34900,
            'exp_total'  => 908600,
        ],
        70  => [
            'exp_to_lvl' => 35700,
            'exp_total'  => 943500,
        ],
        71  => [
            'exp_to_lvl' => 36500,
            'exp_total'  => 979200,
        ],
        72  => [
            'exp_to_lvl' => 37300,
            'exp_total'  => 1015700,
        ],
        73  => [
            'exp_to_lvl' => 38100,
            'exp_total'  => 1053000,
        ],
        74  => [
            'exp_to_lvl' => 39000,
            'exp_total'  => 1091100,
        ],
        75  => [
            'exp_to_lvl' => 39800,
            'exp_total'  => 1130100,
        ],
        76  => [
            'exp_to_lvl' => 40700,
            'exp_total'  => 1169900,
        ],
        77  => [
            'exp_to_lvl' => 41500,
            'exp_total'  => 1210600,
        ],
        78  => [
            'exp_to_lvl' => 42400,
            'exp_total'  => 1252100,
        ],
        79  => [
            'exp_to_lvl' => 43300,
            'exp_total'  => 1294500,
        ],
        80  => [
            'exp_to_lvl' => 44200,
            'exp_total'  => 1337800,
        ],
        81  => [
            'exp_to_lvl' => 45000,
            'exp_total'  => 1382000,
        ],
        82  => [
            'exp_to_lvl' => 45900,
            'exp_total'  => 1427000,
        ],
        83  => [
            'exp_to_lvl' => 46800,
            'exp_total'  => 1472900,
        ],
        84  => [
            'exp_to_lvl' => 47700,
            'exp_total'  => 1519700,
        ],
        85  => [
            'exp_to_lvl' => 48700,
            'exp_total'  => 1567400,
        ],
        86  => [
            'exp_to_lvl' => 49600,
            'exp_total'  => 1616100,
        ],
        87  => [
            'exp_to_lvl' => 50500,
            'exp_total'  => 1665700,
        ],
        88  => [
            'exp_to_lvl' => 51400,
            'exp_total'  => 1716200,
        ],
        89  => [
            'exp_to_lvl' => 52400,
            'exp_total'  => 1767600,
        ],
        90  => [
            'exp_to_lvl' => 53300,
            'exp_total'  => 1820000,
        ],
        91  => [
            'exp_to_lvl' => 54300,
            'exp_total'  => 1873300,
        ],
        92  => [
            'exp_to_lvl' => 55200,
            'exp_total'  => 1927600,
        ],
        93  => [
            'exp_to_lvl' => 56200,
            'exp_total'  => 1982800,
        ],
        94  => [
            'exp_to_lvl' => 57200,
            'exp_total'  => 2039000,
        ],
        95  => [
            'exp_to_lvl' => 58100,
            'exp_total'  => 2096200,
        ],
        96  => [
            'exp_to_lvl' => 59100,
            'exp_total'  => 2154300,
        ],
        97  => [
            'exp_to_lvl' => 60100,
            'exp_total'  => 2213400,
        ],
        98  => [
            'exp_to_lvl' => 61100,
            'exp_total'  => 2273500,
        ],
        99  => [
            'exp_to_lvl' => 62100,
            'exp_total'  => 2334600,
        ],
        100 => [
            'exp_to_lvl' => 63100,
            'exp_total'  => 2396700,
        ],
    ];

    /**
     * @param string $accountId
     * @param string $characterId
     * @param int $level
     * @param int $exp
     * @param int $statPoints
     * @param SendNoticeActionInterface $sendNoticeAction
     * @throws AppException
     */
    public function __construct(
        string $accountId,
        string $characterId,
        int $level,
        int $exp,
        int $statPoints,
        SendNoticeActionInterface $sendNoticeAction
    )
    {
        $this->accountId = $accountId;
        $this->characterId = $characterId;
        $this->level = $level;
        $this->exp = $exp;
        $this->statPoints = $statPoints;
        $this->sendNoticeAction = $sendNoticeAction;
        $this->setAdditionalParams();
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getCharacterId(): string
    {
        return $this->characterId;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @return int
     */
    public function getExpToLevel(): int
    {
        return $this->expToLevel;
    }

    /**
     * @return int
     */
    public function getExpAtLevel(): int
    {
        return $this->expAtLevel;
    }

    /**
     * @return int
     */
    public function getExpBarWeight(): int
    {
        return (int)round($this->expAtLevel / $this->expToLevel * 100);
    }

    /**
     * @param int $addExp
     * @throws LevelException
     * @throws NoticeException
     * @throws AppException
     */
    public function addExp(int $addExp): void
    {
        if ($addExp < 1) {
            throw new LevelException(LevelException::INVALID_ADD_EXP . ': ' . $addExp);
        }

        $this->exp += $addExp;

        // Проверяем, что опыт не может стать больше чем 100 уровень и 100% сверху (2396700 + 63100 - 1)
        if ($this->exp > 2459799) {
            $this->exp = 2459799;
        }

        $this->setAdditionalParams();
        $this->increaseLevel();
    }

    /**
     * @return int
     */
    public function getStatPoints(): int
    {
        return $this->statPoints;
    }

    /**
     * @throws AppException
     */
    private function setAdditionalParams(): void
    {
        if (!array_key_exists($this->level, self::$levelsData)) {
            throw new AppException(LevelException::INVALID_LEVEL . ': ' . $this->level);
        }

        $this->expToLevel = self::$levelsData[$this->level]['exp_to_lvl'];
        $this->expAtLevel = $this->exp - self::$levelsData[$this->level]['exp_total'];
    }

    /**
     * Рекурсивное увеличение уровня по одному, до тех пор, пока хватает опыта для его повышения
     *
     * @throws LevelException
     * @throws NoticeException
     * @throws AppException
     */
    private function increaseLevel(): void
    {
        if ($this->expAtLevel < $this->expToLevel) {
            return;
        }
        $this->level++;
        $this->statPoints += self::ADD_STAT_POINT;
        $this->setAdditionalParams();
        $this->increaseLevel();
        $this->sendNoticeAction->send($this->accountId, self::NEW_LEVEL_MESSAGE);
    }
}
