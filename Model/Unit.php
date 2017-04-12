<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.01.17
 */

namespace Dopamedia\Measure\Model;

use Dopamedia\Measure\Api\Data\UnitInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class Unit extends AbstractSimpleObject implements UnitInterface
{
    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->_get(self::CODE);
    }

    /**
     * @inheritdoc
     */
    public function getSymbol()
    {
        return $this->_get(self::SYMBOL);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder()
    {
        return $this->_get(self::SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function getFamilyCode()
    {
        return $this->_get(self::FAMILY_CODE);
    }

    /**
     * @inheritdoc
     */
    public function getConversionStrategies()
    {
        return $this->_get(self::CONVERSION_STRATEGIES);
    }
}