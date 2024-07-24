<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Level;

use App\Domain\Account\MainCharacter\Level\LevelException;
use WalkWeb\NW\AppException;

class Level implements LevelInterface
{
    private string $accountId;
    private string $mainCharacterId;
    private string $characterId;
    private int $level;
    private int $exp;
    private int $expToLevel;
    private int $expAtLevel;
    private int $statPoints;
    private int $skillPoints;

    private static array $levelsData = [
        1   => [
            'exp_to_lvl' => 200,
            'exp_total'  => 0,
        ],
        2   => [
            'exp_to_lvl' => 350,
            'exp_total'  => 200,
        ],
        3   => [
            'exp_to_lvl' => 590,
            'exp_total'  => 550,
        ],
        4   => [
            'exp_to_lvl' => 900,
            'exp_total'  => 1140,
        ],
        5   => [
            'exp_to_lvl' => 1280,
            'exp_total'  => 2040,
        ],
        6   => [
            'exp_to_lvl' => 1720,
            'exp_total'  => 3320,
        ],
        7   => [
            'exp_to_lvl' => 2230,
            'exp_total'  => 5040,
        ],
        8   => [
            'exp_to_lvl' => 2800,
            'exp_total'  => 7270,
        ],
        9   => [
            'exp_to_lvl' => 3430,
            'exp_total'  => 10070,
        ],
        10  => [
            'exp_to_lvl' => 4120,
            'exp_total'  => 13500,
        ],
        11  => [
            'exp_to_lvl' => 4860,
            'exp_total'  => 17620,
        ],
        12  => [
            'exp_to_lvl' => 5660,
            'exp_total'  => 22480,
        ],
        13  => [
            'exp_to_lvl' => 6520,
            'exp_total'  => 28140,
        ],
        14  => [
            'exp_to_lvl' => 7430,
            'exp_total'  => 34660,
        ],
        15  => [
            'exp_to_lvl' => 8390,
            'exp_total'  => 42090,
        ],
        16  => [
            'exp_to_lvl' => 9410,
            'exp_total'  => 50480,
        ],
        17  => [
            'exp_to_lvl' => 10500,
            'exp_total'  => 59890,
        ],
        18  => [
            'exp_to_lvl' => 11600,
            'exp_total'  => 70390,
        ],
        19  => [
            'exp_to_lvl' => 12800,
            'exp_total'  => 81990,
        ],
        20  => [
            'exp_to_lvl' => 14000,
            'exp_total'  => 94790,
        ],
        21  => [
            'exp_to_lvl' => 15300,
            'exp_total'  => 108790,
        ],
        22  => [
            'exp_to_lvl' => 16600,
            'exp_total'  => 124090,
        ],
        23  => [
            'exp_to_lvl' => 18000,
            'exp_total'  => 140690,
        ],
        24  => [
            'exp_to_lvl' => 19400,
            'exp_total'  => 158690,
        ],
        25  => [
            'exp_to_lvl' => 20900,
            'exp_total'  => 178090,
        ],
        26  => [
            'exp_to_lvl' => 22400,
            'exp_total'  => 198990,
        ],
        27  => [
            'exp_to_lvl' => 23900,
            'exp_total'  => 221390,
        ],
        28  => [
            'exp_to_lvl' => 25500,
            'exp_total'  => 245290,
        ],
        29  => [
            'exp_to_lvl' => 27200,
            'exp_total'  => 270790,
        ],
        30  => [
            'exp_to_lvl' => 28900,
            'exp_total'  => 297990,
        ],
        31  => [
            'exp_to_lvl' => 30600,
            'exp_total'  => 326890,
        ],
        32  => [
            'exp_to_lvl' => 32400,
            'exp_total'  => 357490,
        ],
        33  => [
            'exp_to_lvl' => 34300,
            'exp_total'  => 389890,
        ],
        34  => [
            'exp_to_lvl' => 36200,
            'exp_total'  => 424190,
        ],
        35  => [
            'exp_to_lvl' => 38100,
            'exp_total'  => 460390,
        ],
        36  => [
            'exp_to_lvl' => 40100,
            'exp_total'  => 498490,
        ],
        37  => [
            'exp_to_lvl' => 42100,
            'exp_total'  => 538590,
        ],
        38  => [
            'exp_to_lvl' => 44200,
            'exp_total'  => 580690,
        ],
        39  => [
            'exp_to_lvl' => 46300,
            'exp_total'  => 624890,
        ],
        40  => [
            'exp_to_lvl' => 48400,
            'exp_total'  => 671190,
        ],
        41  => [
            'exp_to_lvl' => 50600,
            'exp_total'  => 719590,
        ],
        42  => [
            'exp_to_lvl' => 52800,
            'exp_total'  => 770190,
        ],
        43  => [
            'exp_to_lvl' => 55100,
            'exp_total'  => 822990,
        ],
        44  => [
            'exp_to_lvl' => 57400,
            'exp_total'  => 878090,
        ],
        45  => [
            'exp_to_lvl' => 59800,
            'exp_total'  => 935490,
        ],
        46  => [
            'exp_to_lvl' => 62200,
            'exp_total'  => 995290,
        ],
        47  => [
            'exp_to_lvl' => 64700,
            'exp_total'  => 1057490,
        ],
        48  => [
            'exp_to_lvl' => 67200,
            'exp_total'  => 1122190,
        ],
        49  => [
            'exp_to_lvl' => 69700,
            'exp_total'  => 1189390,
        ],
        50  => [
            'exp_to_lvl' => 72300,
            'exp_total'  => 1259090,
        ],
        51  => [
            'exp_to_lvl' => 74900,
            'exp_total'  => 1331390,
        ],
        52  => [
            'exp_to_lvl' => 77500,
            'exp_total'  => 1406290,
        ],
        53  => [
            'exp_to_lvl' => 80200,
            'exp_total'  => 1483790,
        ],
        54  => [
            'exp_to_lvl' => 83000,
            'exp_total'  => 1563990,
        ],
        55  => [
            'exp_to_lvl' => 85800,
            'exp_total'  => 1646990,
        ],
        56  => [
            'exp_to_lvl' => 88600,
            'exp_total'  => 1732790,
        ],
        57  => [
            'exp_to_lvl' => 91500,
            'exp_total'  => 1821390,
        ],
        58  => [
            'exp_to_lvl' => 94400,
            'exp_total'  => 1912890,
        ],
        59  => [
            'exp_to_lvl' => 97300,
            'exp_total'  => 2007290,
        ],
        60  => [
            'exp_to_lvl' => 100300,
            'exp_total'  => 2104590,
        ],
        61  => [
            'exp_to_lvl' => 103300,
            'exp_total'  => 2204890,
        ],
        62  => [
            'exp_to_lvl' => 106400,
            'exp_total'  => 2308190,
        ],
        63  => [
            'exp_to_lvl' => 109500,
            'exp_total'  => 2414590,
        ],
        64  => [
            'exp_to_lvl' => 112600,
            'exp_total'  => 2524090,
        ],
        65  => [
            'exp_to_lvl' => 115800,
            'exp_total'  => 2636690,
        ],
        66  => [
            'exp_to_lvl' => 119000,
            'exp_total'  => 2752490,
        ],
        67  => [
            'exp_to_lvl' => 122300,
            'exp_total'  => 2871490,
        ],
        68  => [
            'exp_to_lvl' => 125600,
            'exp_total'  => 2993790,
        ],
        69  => [
            'exp_to_lvl' => 128900,
            'exp_total'  => 3119390,
        ],
        70  => [
            'exp_to_lvl' => 132300,
            'exp_total'  => 3248290,
        ],
        71  => [
            'exp_to_lvl' => 135700,
            'exp_total'  => 3380590,
        ],
        72  => [
            'exp_to_lvl' => 139200,
            'exp_total'  => 3516290,
        ],
        73  => [
            'exp_to_lvl' => 142700,
            'exp_total'  => 3655490,
        ],
        74  => [
            'exp_to_lvl' => 146200,
            'exp_total'  => 3798190,
        ],
        75  => [
            'exp_to_lvl' => 149800,
            'exp_total'  => 3944390,
        ],
        76  => [
            'exp_to_lvl' => 153400,
            'exp_total'  => 4094190,
        ],
        77  => [
            'exp_to_lvl' => 157100,
            'exp_total'  => 4247590,
        ],
        78  => [
            'exp_to_lvl' => 160700,
            'exp_total'  => 4404690,
        ],
        79  => [
            'exp_to_lvl' => 164500,
            'exp_total'  => 4565390,
        ],
        80  => [
            'exp_to_lvl' => 168200,
            'exp_total'  => 4729890,
        ],
        81  => [
            'exp_to_lvl' => 172000,
            'exp_total'  => 4898090,
        ],
        82  => [
            'exp_to_lvl' => 175900,
            'exp_total'  => 5070090,
        ],
        83  => [
            'exp_to_lvl' => 179700,
            'exp_total'  => 5245990,
        ],
        84  => [
            'exp_to_lvl' => 183700,
            'exp_total'  => 5425690,
        ],
        85  => [
            'exp_to_lvl' => 187600,
            'exp_total'  => 5609390,
        ],
        86  => [
            'exp_to_lvl' => 191600,
            'exp_total'  => 5796990,
        ],
        87  => [
            'exp_to_lvl' => 195600,
            'exp_total'  => 5988590,
        ],
        88  => [
            'exp_to_lvl' => 199700,
            'exp_total'  => 6184190,
        ],
        89  => [
            'exp_to_lvl' => 203800,
            'exp_total'  => 6383890,
        ],
        90  => [
            'exp_to_lvl' => 207900,
            'exp_total'  => 6587690,
        ],
        91  => [
            'exp_to_lvl' => 212100,
            'exp_total'  => 6795590,
        ],
        92  => [
            'exp_to_lvl' => 216300,
            'exp_total'  => 7007690,
        ],
        93  => [
            'exp_to_lvl' => 220600,
            'exp_total'  => 7223990,
        ],
        94  => [
            'exp_to_lvl' => 224800,
            'exp_total'  => 7444590,
        ],
        95  => [
            'exp_to_lvl' => 229200,
            'exp_total'  => 7669390,
        ],
        96  => [
            'exp_to_lvl' => 233500,
            'exp_total'  => 7898590,
        ],
        97  => [
            'exp_to_lvl' => 237900,
            'exp_total'  => 8132090,
        ],
        98  => [
            'exp_to_lvl' => 242400,
            'exp_total'  => 8369990,
        ],
        99  => [
            'exp_to_lvl' => 246800,
            'exp_total'  => 8612390,
        ],
        100 => [
            'exp_to_lvl' => 251300,
            'exp_total'  => 8859190,
        ],
    ];

    /**
     * @param string $accountId
     * @param string $mainCharacterId
     * @param string $characterId
     * @param int $level
     * @param int $exp
     * @param int $statPoints
     * @param int $skillPoints
     * @throws AppException
     */
    public function __construct(
        string $accountId,
        string $mainCharacterId,
        string $characterId,
        int $level,
        int $exp,
        int $statPoints,
        int $skillPoints
    )
    {
        $this->accountId = $accountId;
        $this->mainCharacterId = $mainCharacterId;
        $this->characterId = $characterId;
        $this->level = $level;
        $this->exp = $exp;
        $this->statPoints = $statPoints;
        $this->skillPoints = $skillPoints;
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
    public function getMainCharacterId(): string
    {
        return $this->mainCharacterId;
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
    public function getStatPoints(): int
    {
        return $this->statPoints;
    }

    /**
     * @return int
     */
    public function getSkillPoints(): int
    {
        return $this->skillPoints;
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
     * @throws AppException
     */
    public function addExp(int $addExp): void
    {
        if ($addExp < 1) {
            throw new AppException(LevelException::INVALID_ADD_EXP . ': ' . $addExp);
        }

        $this->exp += $addExp;

        // Проверяем, что опыт не может стать больше чем 100 уровень и 100% сверху (8859190 + 251300 - 1)
        if ($this->exp > 9110489) {
            $this->exp = 9110489;
        }

        $this->setAdditionalParams();
        $this->increaseLevel();
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
     * @throws AppException
     */
    private function increaseLevel(): void
    {
        if ($this->expAtLevel < $this->expToLevel) {
            return;
        }
        $this->level++;
        $this->statPoints += self::ADD_STAT_POINT;
        $this->skillPoints += self::ADD_SKILL_POINT;
        $this->setAdditionalParams();
        $this->increaseLevel();
    }
}
