<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Model;

class Config extends \Magento\Framework\Config\Data implements ConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getAllFamilies()
    {
        return $this->get('families');
    }

    /**
     * @inheritdoc
     */
    public function getFamily($familyCode)
    {
        return $this->get('families/' . $familyCode, []);
    }

    /**
     * @inheritdoc
     */
    public function getAllUnits()
    {
        return $this->get('units');
    }

    /**
     * @inheritdoc
     */
    public function getUnit($unitCode)
    {
        return $this->get('units/' . $unitCode, []);
    }

    /**
     * @inheritdoc
     */
    public function getUnitSymbol($unitCode)
    {
        return $this->get('units/' . $unitCode . '/symbol', '');
    }

    /**
     * @inheritdoc
     */
    public function getUnitName($unitCode)
    {
        return $this->get('units/' . $unitCode . '/name', '');
    }

    /**
     * @inheritdoc
     */
    public function getUnitSortOrder($unitCode)
    {
        return (int)$this->get('units/' . $unitCode . '/sort_order', 0);
    }

    /**
     * @inheritdoc
     */
    public function getUnitFamilyCode($unitCode)
    {
        return $this->get('units/' . $unitCode . '/family', '');
    }

    /**
     * @inheritdoc
     */
    public function getUnitConversionStrategies($unitCode)
    {
        return $this->get('units/' . $unitCode . '/conversion_strategies', []);
    }
}