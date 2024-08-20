<?php

declare(strict_types=1);

namespace App\Domain\Account\Traits;

trait CarmaTrait
{
    /**
     * @return string
     */
    public function getCarmaSign(): string
    {
        if ($this->carma > 0) {
            return '+';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCarmaColoClass(): string
    {
        if ($this->carma > 0) {
            return 'positiveRatingColor';
        }

        if ($this->carma < 0) {
            return 'negativeRatingColor';
        }

        return 'defaultRatingColor';
    }
}
