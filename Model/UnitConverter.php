<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class UnitConverter implements UnitConverterInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function convert($baseUnitCode, $referenceUnitCode, $value)
    {
        foreach ([$baseUnitCode, $referenceUnitCode] as $unitCode) {
            if (!$this->checkIfUnitExists($unitCode)) {
                throw new LocalizedException(
                    new Phrase('The unit "%1" could not be found', [$unitCode])
                );
            }
        }

        if (!$this->compareUnitFamilies($baseUnitCode, $referenceUnitCode)) {
            throw new LocalizedException(
                new Phrase('The units "%1" and "%2" are not in the same family', [$baseUnitCode, $referenceUnitCode])
            );
        }

        $standardValue = $this->convertBaseToStandard($baseUnitCode, $value);
        return $this->convertStandardToResult($referenceUnitCode, $standardValue);
    }

    /**
     * @param string $unitCode
     * @return bool
     */
    public function checkIfUnitExists($unitCode)
    {
        return !empty($this->config->getUnit($unitCode));
    }

    /**
     * @param string $leftUnitCode
     * @param string $rightUnitCode
     * @return bool
     */
    public function compareUnitFamilies($leftUnitCode, $rightUnitCode)
    {
        return $this->config->getUnitFamilyCode($leftUnitCode)
            === $this->config->getUnitFamilyCode($rightUnitCode);
    }

    /**
     * @param string $baseUnitCode
     * @param float $value
     * @return float
     */
    public function convertBaseToStandard($baseUnitCode, $value)
    {
        $conversionStrategies = $this->config->getUnitConversionStrategies($baseUnitCode);
        $convertedValue = $value;
        foreach ($conversionStrategies as $operator => $operand) {
            $convertedValue = $this->applyOperation($convertedValue, $operator, $operand);
        }
        return $convertedValue;
    }

    /**
     * @param float $value
     * @param string $operator
     * @param float $operand
     * @return float
     * @throws \Exception
     */
    protected function applyOperation($value, $operator, $operand)
    {
        $processedValue = $value;
        switch ($operator) {
            case 'div':
                if ($operand !== 0) {
                    $processedValue = $processedValue / $operand;
                }
                break;
            case 'mul':
                $processedValue = $processedValue * $operand;
                break;
            case 'add':
                $processedValue = $processedValue + $operand;
                break;
            case "sub":
                $processedValue = $processedValue - $operand;
                break;
            default:
                throw new LocalizedException(
                    new Phrase('Operator "%1" is not supported', [$operator])
                );
        }
        return $processedValue;
    }

    /**
     * @param string $finalUnitCode
     * @param float $value
     * @return float
     */
    public function convertStandardToResult($finalUnitCode, $value)
    {
        $conversionStrategies = $this->config->getUnitConversionStrategies($finalUnitCode);
        $convertedValue = $value;
        foreach (array_reverse($conversionStrategies) as $operator => $operand) {
            $convertedValue = $this->applyReversedOperation($convertedValue, $operator, $operand);
        }
        return $convertedValue;
    }

    /**
     * @param float $value
     * @param string $operator
     * @param float $operand
     * @return float
     * @throws \Exception
     */
    protected function applyReversedOperation($value, $operator, $operand)
    {
        $processedValue = $value;
        switch ($operator) {
            case "div":
                $processedValue = $processedValue * $operand;
                break;
            case "mul":
                if ($operand !== 0) {
                    $processedValue = $processedValue / $operand;
                }
                break;
            case "add":
                $processedValue = $processedValue - $operand;
                break;
            case "sub":
                $processedValue = $processedValue + $operand;
                break;
            default:
                throw new LocalizedException(
                    new Phrase('Operator "%1" is not supported', [$operator])
                );
        }
        return $processedValue;
    }
}