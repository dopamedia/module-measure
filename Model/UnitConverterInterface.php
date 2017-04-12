<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Model;

interface UnitConverterInterface
{
    /**
     * @param string $baseUnitCode
     * @param string $referenceUnitCode
     * @param float $value
     * @return float
     */
    public function convert($baseUnitCode, $referenceUnitCode, $value);
}