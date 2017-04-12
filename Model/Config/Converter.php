<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.01.17
 */

namespace Dopamedia\Measure\Model\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @param \DOMDocument $document
     * @return \DOMElement
     */
    protected function getRootElement(\DOMDocument $document)
    {
        return $this->getAllChildElements($document)[0];
    }

    /**
     * @param \DOMElement $parent
     * @param string $name
     * @return array|\DOMElement[]
     */
    protected function getChildrenByName(\DOMElement $parent, $name)
    {
        return array_filter($this->getAllChildElements($parent), function (\DomElement $child) use ($name) {
            return $child->nodeName === $name;
        });
    }

    /**
     * @param \DOMNode $parent
     * @return array|\DOMElement[]
     */
    protected function getAllChildElements(\DOMNode $parent)
    {
        return array_filter(iterator_to_array($parent->childNodes), function (\DOMNode $child) {
            return $child->nodeType === \XML_ELEMENT_NODE;
        });
    }

    /**
     * @param \DOMElement $unitNode
     * @return \Generator
     */
    private function gatherConversionStrategies(\DOMElement $unitNode)
    {
        foreach ($this->getChildrenByName($unitNode, 'convert') as $convertNode) {
            foreach ($this->getChildrenByName($convertNode, 'strategy') as $strategyNode) {
                yield $strategyNode->attributes->getNamedItem('name')->nodeValue =>
                    (float)$strategyNode->attributes->getNamedItem('value')->nodeValue;
            }
        }
    }

    /**
     * @param \DomElement $familyNode
     * @return \Generator
     */
    private function gatherUnits(\DomElement $familyNode)
    {
        $familyCode = $familyNode->attributes->getNamedItem('code')->nodeValue;
        foreach ($this->getChildrenByName($familyNode, 'unit') as $unitNode) {
            $unitCode = $unitNode->attributes->getNamedItem('code')->nodeValue;
            yield $unitCode => [
                'family' => $familyCode,
                'code' => $unitCode,
                'symbol' => $unitNode->attributes->getNamedItem('symbol')->nodeValue,
                'name' => $unitNode->attributes->getNamedItem('name')->nodeValue,
                'sort_order' => $unitNode->attributes->getNamedItem('sortOrder')->nodeValue,
                'conversion_strategies' => iterator_to_array($this->gatherConversionStrategies($unitNode))
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function convert($document)
    {
        $output = [];
        $rootElement = $this->getRootElement($document);
        foreach ($this->getChildrenByName($rootElement, 'family') as $familyNode) {
            $familyCode = $familyNode->attributes->getNamedItem('code')->nodeValue;
            $familyData = [
                'code' => $familyCode,
                'name' => $familyNode->attributes->getNamedItem('name')->nodeValue,
                'standard' => $familyNode->attributes->getNamedItem('standard')->nodeValue
            ];

            $output['families'][$familyCode] = $familyData;
            foreach ($this->gatherUnits($familyNode) as $unitCode => $unit) {
                $output['units'][$unitCode] = $unit;
            }
        }
        return $output;
    }
}