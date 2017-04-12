<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 05.01.17
 */

namespace Dopamedia\Measure\Model;

use Dopamedia\Measure\Api\Data\UnitInterface;
use Magento\Framework\Exception\LocalizedException;

interface BuilderInterface
{
    /**
     * @param string $unitCode
     * @return UnitInterface
     * @throws LocalizedException
     */
    public function getUnit($unitCode);
}