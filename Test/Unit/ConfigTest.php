<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Test\Unit;

use Dopamedia\Measure\Model\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Config\ReaderInterface
     */
    protected $readerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Config\CacheInterface
     */
    protected $cacheMock;

    protected function setUp()
    {
        $this->readerMock = $this->getMock(
            '\Magento\Framework\Config\ReaderInterface',
            [],
            [],
            '',
            false
        );
        $this->cacheMock = $this->getMock('Magento\Framework\Config\CacheInterface');
    }

    public function testGetFamilies()
    {
        $value = [
            'families' => [
                'LENGTH' => [
                    'code' => 'LENGTH',
                    'name' => 'Length',
                    'standard' => 'METRE'
                ],
                'VOLUME' => [
                    'code' => 'VOLUME',
                    'name' => 'Volume',
                    'standard' => 'CUBIC_METRE'
                ]
            ]
        ];

        $expected = [
            'LENGTH' => [
                'code' => 'LENGTH',
                'name' => 'Length',
                'standard' => 'METRE'
            ],
            'VOLUME' => [
                'code' => 'VOLUME',
                'name' => 'Volume',
                'standard' => 'CUBIC_METRE'
            ]
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $config->getAllFamilies());
    }

    public function testGetFamily()
    {
        $value = [
            'families' => [
                'LENGTH' => [
                    'code' => 'LENGTH',
                    'name' => 'Length',
                    'standard' => 'METRE'
                ]
            ]
        ];

        $expected = [
            'code' => 'LENGTH',
            'name' => 'Length',
            'standard' => 'METRE'
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $config->getFamily('LENGTH'));
    }


    public function testGetAllUnits()
    {
        $value = [
            'units' => [
                'METRE' => [],
                'FEET' => []
            ]
        ];

        $expected = [
            'METRE' => [],
            'FEET' => []
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $config->getAllUnits());
    }

    public function testGetUnit()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'family' => 'LENGTH',
                    'code' => 'FEET',
                    'symbol' => 'ft',
                    'name' => 'Feet',
                    'sortOrder' => 100
                ]
            ]
        ];

        $expected = [
            'family' => 'LENGTH',
            'code' => 'FEET',
            'symbol' => 'ft',
            'name' => 'Feet',
            'sortOrder' => 100
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $config->getUnit('FEET'));
    }

    public function testGetUnitSymbol()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'symbol' => 'ft'
                ]
            ]
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('ft', $config->getUnitSymbol('FEET'));
    }

    public function testGetUnitName()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'name' => 'Feet'
                ]
            ]
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('Feet', $config->getUnitName('FEET'));
    }

    public function testGetUnitSortOrder()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'sort_order' => '10'
                ]
            ]
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(10, $config->getUnitSortOrder('FEET'));
    }

    public function testGetUnitFamilyCode()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'family' => 'LENGTH'
                ]
            ]
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('LENGTH', $config->getUnitFamilyCode('FEET'));
    }

    public function testGetUnitConversionStrategies()
    {
        $value = [
            'units' => [
                'FEET' => [
                    'code' => 'FEET',
                    'conversion_strategies' => [
                        'mul' => 0.3048,
                        'div' => 10
                    ]
                ]
            ]
        ];

        $expected = [
            'mul' => 0.3048,
            'div' => 10
        ];

        $this->cacheMock->expects($this->any())->method('load')->will($this->returnValue(serialize($value)));
        $config = new Config($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $config->getUnitConversionStrategies('FEET'));
    }
}
