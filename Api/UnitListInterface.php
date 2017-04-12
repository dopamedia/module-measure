<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Api;

use Dopamedia\Measure\Api\Data\UnitInterface;

interface UnitListInterface
{
    /**
     * @return UnitInterface[]
     */
    public function getUnits();
}