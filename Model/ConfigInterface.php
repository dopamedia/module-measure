<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.01.17
 */

namespace Dopamedia\Measure\Model;


interface ConfigInterface
{
    /**
     * @return array[]
     */
    public function getAllFamilies();

    /**
     * @param string $familyCode
     * @return array
     */
    public function getFamily($familyCode);

    /**
     * @return array[]
     */
    public function getAllUnits();

    /**
     * @param string $unitCode
     * @return array
     */
    public function getUnit($unitCode);

    /**
     * @param string $unitCode
     * @return string
     */
    public function getUnitSymbol($unitCode);

    /**
     * @param string $unitCode
     * @return string
     */
    public function getUnitName($unitCode);

    /**
     * @param string $unitCode
     * @return int
     */
    public function getUnitSortOrder($unitCode);

    /**
     * @param string $unitCode
     * @return string
     */
    public function getUnitFamilyCode($unitCode);

    /**
     * @param string $unitCode
     * @return array[]
     */
    public function getUnitConversionStrategies($unitCode);
}