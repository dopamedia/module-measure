<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Test\Unit;

use Dopamedia\Measure\Api\Data\UnitInterface;
use Dopamedia\Measure\Model\Builder;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\Measure\Model\ConfigInterface
     */
    protected $configMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\Measure\Api\Data\UnitInterfaceFactory
     */
    protected $unitFactoryMock;

    protected function setUp()
    {
        $this->configMock = $this->getMock('Dopamedia\Measure\Model\ConfigInterface');

        $this->unitFactoryMock = $this->getMock(
            'Dopamedia\Measure\Api\Data\UnitInterfaceFactory',
            ['create'],
            [],
            '',
            false
        );

        $this->builder = new Builder(
            $this->configMock,
            $this->unitFactoryMock
        );
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testGetUnitThrowsException()
    {
        $this->configMock->expects($this->any())->method('getUnit')->willReturn([]);
        $this->builder->getUnit('UNKNOWN');
    }

    public function testGetUnit()
    {
        $unitData = [
            'name' => 'Kilo'
        ];
        $this->configMock->expects($this->any())->method('getUnit')->willReturn($unitData);
        $unitMock = $this->getMock('Dopamedia\Measure\Api\Data\UnitInterface');
        $this->unitFactoryMock->expects($this->once())
            ->method('create')
            ->with(['data' => $unitData])
            ->willReturn($unitMock);
        $unit = $this->builder->getUnit('UNIT');
        $this->assertInstanceOf(UnitInterface::class, $unit);
    }

}
