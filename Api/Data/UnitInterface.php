<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.01.17
 */

namespace Dopamedia\Measure\Api\Data;

interface UnitInterface
{
    /**#@+*/
    const CODE = 'code';
    const SYMBOL = 'symbol';
    const NAME = 'name';
    const SORT_ORDER = 'sort_order';
    const FAMILY_CODE = 'family_code';
    const CONVERSION_STRATEGIES = 'conversion_strategies';
    /**#@+*/

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getSymbol();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @return string
     */
    public function getFamilyCode();

    /**
     * @return array
     */
    public function getConversionStrategies();
}