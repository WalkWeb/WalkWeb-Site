<?php

use App\Domain\Account\Character\CharacterInterface;
use WalkWeb\NW\AppException;

$this->title = APP_NAME . ' — Просмотр информации о персонаже';

if (empty($character) || !($character instanceof CharacterInterface)) {
    throw new AppException('character/index view: miss $character');
}

?>

<h1><?= $this->title ?></h1>

<p class="center">
    <img src="<?= $character->getAvatar() ?>" alt="" />
</p>

<p class="center">
    <img src="<?= $character->getGenesis()->getIcon() ?>" alt="" class="vertical_center" /> <?= $character->getGenesis()->getSingle() ?>
</p>

<p>
    ID: <?= $character->getId() ?><br />
    Account ID: <?= $character->getAccountId() ?><br />
    Main Character ID: <?= $character->getMainCharacterId() ?><br />
    Season ID: <?= $character->getSeason()->getId() ?><br />
    Season Name: <?= $character->getSeason()->getName() ?><br />
    Genesis ID: <?= $character->getGenesis()->getId() ?><br />
    Genesis Name: <?= $character->getGenesis()->getSingle() ?><br />
    Profession ID: <?= $character->getProfession()->getId() ?><br />
    Profession ID: <?= $character->getProfession()->getName($character->getFloor()) ?><br />
    Floor: <?= $character->getFloor()->getName() ?><br />
</p>

<p>
    Level: <?= $character->getLevel()->getLevel() ?><br />
    Exp: <?= $character->getLevel()->getExp() ?><br />
    Exp At Level: <?= $character->getLevel()->getExpAtLevel() ?><br />
    Exp To Level: <?= $character->getLevel()->getExpToLevel() ?><br />
</p>
