<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Test\Unit\Config;

use Dopamedia\Measure\Model\Config\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @var Converter
     */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter();
    }

    private function createSource($xml)
    {
        $source = new \DOMDocument();
        $source->loadXML($xml);
        return $source;
    }

    public function testReturnsEmptyArrayForEmptyDocument()
    {
        $xml = '<empty/>';
        $this->assertSame(
            [],
            $this->converter->convert($this->createSource($xml))
        );
    }

    public function testIdAddsEachFamily()
    {
        $xml = <<<XML
<config>
    <family code="LENGTH" name="Length" standard="METRE"/>
    <family code="VOLUME" name="Volume" standard="CUBIC_METER"/>
</config>
XML;

        $result = $this->converter->convert($this->createSource($xml));

        $expected = [
            'LENGTH' => [
                'code' => 'LENGTH',
                'name' => 'Length',
                'standard' => 'METRE'
            ],
            'VOLUME' => [
                'code' => 'VOLUME',
                'name' => 'Volume',
                'standard' => 'CUBIC_METER'
            ]
        ];

        $this->assertSame($expected, $result['families']);
    }

    public function testIdAddsEachUnit()
    {
        $xml = <<<XML
<config>
    <family code="LENGTH" name="Length" standard="METRE">
         <unit code="DECIMETRE" symbol="dm" name="Decimetre" sortOrder="30">
            <convert>
                <strategy name="multiply" value="0.1"/>
            </convert>
        </unit>
        <unit code="METRE" symbol="m" name="Metre" sortOrder="40">
            <convert>
                <strategy name="multiply" value="1"/>
            </convert>
        </unit>
    </family>
</config>
XML;

        $result = $this->converter->convert($this->createSource($xml));

        $expected = [
            'DECIMETRE' => [
                'family' => 'LENGTH',
                'code' => 'DECIMETRE',
                'symbol' => 'dm',
                'name' => 'Decimetre',
                'sort_order' => '30',
                'conversion_strategies' => [
                    'multiply' => 0.1
                ]
            ],
            'METRE' => [
                'family' => 'LENGTH',
                'code' => 'METRE',
                'symbol' => 'm',
                'name' => 'Metre',
                'sort_order' => '40',
                'conversion_strategies' => [
                    'multiply' => 1.0
                ]
            ]
        ];

        $this->assertSame($expected, $result['units']);
    }
}
