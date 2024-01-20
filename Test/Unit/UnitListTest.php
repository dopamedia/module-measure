<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Test\Unit;

use Dopamedia\Measure\Api\Data\UnitInterfaceFactory;
use Dopamedia\Measure\Model\ConfigInterface;
use Dopamedia\Measure\Model\UnitList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnitListTest extends TestCase
{

    /**
     * @var UnitList
     */
    protected $unitList;

    /**
     * @var MockObject|ConfigInterface
     */
    protected $configMock;

    /**
     * @var MockObject|UnitInterfaceFactory
     */
    protected $unitFactoryMock;

    public function testGetUnits()
    {
        $unitData = ['name' => 'Kilo'];
        $this->configMock->expects($this->any())->method('getAllUnits')->willReturn([$unitData]);
        $unitMock = $this->getMock('Dopamedia\Measure\Api\Data\UnitInterface');
        $this->unitFactoryMock->expects($this->once())
            ->method('create')
            ->with(['data' => $unitData])
            ->willReturn($unitMock);
        $units = $this->unitList->getUnits();
        $this->assertCount(1, $units);
        $this->assertContains($unitMock, $units);
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

        $this->unitList = new UnitList(
            $this->configMock,
            $this->unitFactoryMock
        );
    }
}
