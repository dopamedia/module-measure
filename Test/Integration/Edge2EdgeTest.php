<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Test\Integration;

use Magento\TestFramework\ObjectManager;

class Edge2EdgeTest extends \PHPUnit_Framework_TestCase
{
    private $configType = \Dopamedia\Measure\Model\Config::class;

    public function testCanAccessFamilyConfig()
    {
        $objectManager = ObjectManager::getInstance();
        /** @var \Magento\Framework\Config\Data $config */
        $config = $objectManager->create($this->configType);
        $this->assertSame('LENGTH', $config->get('families/LENGTH/code'));
        $this->assertSame('Length', $config->get('families/LENGTH/name'));
        $this->assertSame('METRE', $config->get('families/LENGTH/standard'));
        $this->assertSame('VOLUME', $config->get('families/VOLUME/code'));
    }

    public function testCanAccessUnitConfig()
    {
        $objectManager = ObjectManager::getInstance();
        /** @var \Magento\Framework\Config\Data $config */
        $config = $objectManager->create($this->configType);
        $this->assertSame('DECIMETRE', $config->get('units/DECIMETRE/code'));
        $this->assertSame('dm', $config->get('units/DECIMETRE/symbol'));
        $this->assertSame('Decimetre', $config->get('units/DECIMETRE/name'));
        $this->assertSame('30', $config->get('units/DECIMETRE/sort_order'));
    }

    public function testCanAccessConversionStrategies()
    {
        $objectManager = ObjectManager::getInstance();
        /** @var \Magento\Framework\Config\Data $config */
        $config = $objectManager->create($this->configType);
        $this->assertSame(
            ['mul' => 10.0],
            $config->get('units/DEKAMETRE/conversion_strategies')
        );
    }

    public function testMultipleFilesCouldBeMerged()
    {
        $mockFileResolver = $this->getMock(\Magento\Framework\Config\FileResolverInterface::class);
        $mockFileResolver->method('get')->willReturn([
            'measure_1.xml' => <<<XML
<config>
    <family code="LENGTH" name="Length" standard="METRE" sortOrder="20">
        <unit code="MILLIMETRE" symbol="mm" name="Millimetre" sortOrder="10">
            <convert>
                <strategy name="mul" value="0.01"/>
            </convert>
        </unit>
    </family>
</config>
XML
            ,'measure_2.xml' => <<<XML
<config>
    <family code="LENGTH" sortOrder="20">
        <unit code="MILLIMETRE">
            <convert>
                <strategy name="mul" value="0.01"/>
            </convert>
        </unit>
    </family>
</config>
XML
        ]);

        $objectManager = ObjectManager::getInstance();
        $reader = $objectManager->create(
            \Dopamedia\Measure\Model\Config\Reader\Virtual::class,
            ['fileResolver' => $mockFileResolver]
        );
        $mockCache = $this->getMock(\Magento\Framework\Config\CacheInterface::class);
        $mockCache->method('load')->willReturn(false);

        /** @var \Magento\Framework\Config\Data $config */
        $config = $objectManager->create($this->configType, ['reader' => $reader, 'cache' => $mockCache]);

        $expected = [
            'code' => 'LENGTH',
            'name' => 'Length',
            'standard' => 'METRE'
        ];

        $this->assertSame($expected, $config->get('families/LENGTH'));
    }
}