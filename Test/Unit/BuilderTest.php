<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Test\Unit;

use Dopamedia\Measure\Api\Data\UnitInterface;
use Dopamedia\Measure\Api\Data\UnitInterfaceFactory;
use Dopamedia\Measure\Model\Builder;
use Dopamedia\Measure\Model\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var MockObject|UnitInterfaceFactory
     */
    protected $unitFactoryMock;

    /**
     * @var MockObject|ConfigInterface
     */
    protected $configMock;

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

    /**
     * @expectedException LocalizedException
     */
    public function testGetUnitThrowsException()
    {
        $this->configMock->expects($this->any())->method('getUnit')->willReturn([]);
        $this->builder->getUnit('UNKNOWN');
    }

    protected function setUp(): void
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

}
