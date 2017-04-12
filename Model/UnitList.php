<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Model;

use Dopamedia\Measure\Api\UnitListInterface;

class UnitList implements UnitListInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var \Dopamedia\Measure\Api\Data\UnitInterfaceFactory
     */
    private $unitFactory;

    /**
     * @var \Dopamedia\Measure\Api\Data\UnitInterface[]
     */
    private static $unitsBuffer;

    /**
     * @param ConfigInterface $config
     * @param \Dopamedia\Measure\Api\Data\UnitInterfaceFactory $unitFactory
     */
    public function __construct(
        ConfigInterface $config,
        \Dopamedia\Measure\Api\Data\UnitInterfaceFactory $unitFactory
    ) {
        $this->config = $config;
        $this->unitFactory = $unitFactory;
    }

    /**
     * @inheritDoc
     */
    public function getUnits()
    {
        if (empty(self::$unitsBuffer)) {
            foreach ($this->config->getAllUnits() as $unitData) {
                self::$unitsBuffer[] = $this->unitFactory->create(['data' => $unitData]);
            }
        }
        return self::$unitsBuffer;
    }
}