<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 06.01.17
 */

namespace Dopamedia\Measure\Test\Unit;

use Dopamedia\Measure\Model\UnitList;

class UnitListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnitList
     */
    protected $unitList;

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

        $this->unitList = new UnitList(
            $this->configMock,
            $this->unitFactoryMock
        );
    }

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
}
