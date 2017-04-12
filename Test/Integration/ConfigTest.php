<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Test\Integration;

use Magento\TestFramework\ObjectManager;
use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfig;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $configType = \Dopamedia\Measure\Model\Config::class;
    private $readerType = \Dopamedia\Measure\Model\Config\Reader\Virtual::class;
    private $schemaLocatorType = \Dopamedia\Measure\Model\Config\SchemaLocator\Virtual::class;
    private $converterType = \Dopamedia\Measure\Model\Config\Converter::class;

    /**
     * @return ObjectManagerConfig
     */
    protected function getDiConfig()
    {
        return ObjectManager::getInstance()->get(ObjectManagerConfig::class);
    }

    /**
     * @param mixed $expected
     * @param string $type
     * @param string $argumentName
     */
    protected function assertDiArgumentSame($expected, $type, $argumentName)
    {
        $arguments = $this->getDiConfig()->getArguments($type);
        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for %s', $argumentName, $type));
        }
        $this->assertSame($expected, $arguments[$argumentName]);
    }

    /**
     * @param string $expectedType
     * @param string $type
     */
    protected function assertVirtualType($expectedType, $type)
    {
        $this->assertSame($expectedType, $this->getDiConfig()->getInstanceType($type));
    }

    /**
     * @param string $expectedType
     * @param string $type
     * @param string $argumentName
     */
    protected function assertDiArgumentInstance($expectedType, $type, $argumentName)
    {
        $arguments = $this->getDiConfig()->getArguments($type);
        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for %s', $argumentName, $type));
        }

        if (!isset($arguments[$argumentName]['instance'])) {
            $this->fail(sprintf('Argument "%s" for %s is not xsi:type="object"', $argumentName, $type));
        }
        $this->assertSame($expectedType, $arguments[$argumentName]['instance']);
    }

    public function testConfigDataDiConfig()
    {
        $this->assertDiArgumentSame('measure_config', $this->configType, 'cacheId');
        $this->assertDiArgumentInstance($this->readerType, $this->configType, 'reader');
    }

    public function testConfigReaderDiConfig()
    {
        $this->assertVirtualType(\Magento\Framework\Config\Reader\Filesystem::class, $this->readerType);
        $this->assertDiArgumentSame('measure.xml', $this->readerType, 'fileName');
        $this->assertDiArgumentInstance($this->schemaLocatorType, $this->readerType, 'schemaLocator');
        $this->assertDiArgumentInstance($this->converterType, $this->readerType, 'converter');
    }

    public function testConfigSchemaLocatorDiConfig()
    {
        $this->assertVirtualType(\Magento\Framework\Config\GenericSchemaLocator::class, $this->schemaLocatorType);
        $this->assertDiArgumentSame('Dopamedia_Measure', $this->schemaLocatorType, 'moduleName');
        $this->assertDiArgumentSame('measure.xsd', $this->schemaLocatorType, 'schema');
    }

    public function testDataCanBeAccessed()
    {
        $config = ObjectManager::getInstance()->create($this->configType);
        $configData = $config->get(null);
        $this->assertInternalType('array', $configData);
        $this->assertNotEmpty($configData);
    }
}