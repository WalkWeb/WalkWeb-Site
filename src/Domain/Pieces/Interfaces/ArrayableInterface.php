<?php

namespace App\Domain\Pieces\Interfaces;

interface ArrayableInterface
{
    /**
     * Представляет объект в виде массива
     *
     * @return array
     */
    public function toArray(): array;
}
