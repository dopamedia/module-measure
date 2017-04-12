<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 05.01.17
 */

namespace Dopamedia\Measure\Model;

use Dopamedia\Measure\Api\Data\UnitInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Builder implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var UnitFactory
     */
    protected $unitFactory;

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
     * @param string $unitCode
     * @return UnitInterface
     * @throws LocalizedException
     */
    public function getUnit($unitCode)
    {
        $unitConfig = $this->config->getUnit($unitCode);
        if (empty($unitConfig)) {
            throw new LocalizedException(
                new Phrase('The unit "%1" could not be found', [$unitCode])
            );
        }
        return $this->unitFactory->create(['data' => $unitConfig]);
    }
}