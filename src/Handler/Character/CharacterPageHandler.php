<?php

declare(strict_types=1);

namespace App\Handler\Character;

use App\Domain\Account\Character\CharacterInterface;
use App\Domain\Account\Character\CharacterRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CharacterPageHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new CharacterRepository($this->container);

        if ($character = $repository->get($request->id)) {
            return $this->render('character/index', ['character' => $character]);
        }

        // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
        return $this->render(
            'errors/custom_404',
            ['error' => 'Персонаж не найден'],
            Response::NOT_FOUND
        );
    }

    /**
     * @param CharacterInterface $character
     * @return string
     */
    public function getInventoryBackground(CharacterInterface $character): string
    {
        switch ($character) {
            case $character->getGenesis()->getId() === 7 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_human_male.jpg';
            case $character->getGenesis()->getId() === 7 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_human_female.jpg';
            case $character->getGenesis()->getId() === 8 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_elf_male.jpg';
            case $character->getGenesis()->getId() === 8 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_elf_female.jpg';
            case $character->getGenesis()->getId() === 9 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_orc_male.jpg';
            case $character->getGenesis()->getId() === 9 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_orc_female.jpg';
            case $character->getGenesis()->getId() === 10 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_dwarf_male.jpg';
            case $character->getGenesis()->getId() === 10 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_dwarf_female.jpg';
            case $character->getGenesis()->getId() === 11 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_angel_male.jpg';
            case $character->getGenesis()->getId() === 11 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_angel_female.jpg';
            case $character->getGenesis()->getId() === 12 && $character->getFloor()->getId() === 1:
                return '/img/inventory/bg_demon_male.jpg';
            case $character->getGenesis()->getId() === 12 && $character->getFloor()->getId() === 2:
                return '/img/inventory/bg_demon_female.jpg';
        }

        return '';
    }
}
