<?php

/**
 * Xml.
 */

declare(strict_types=1);

namespace SEOCLI\Output;

/**
 * Xml.
 */
class Xml implements OutputInterface
{
    /**
     * Render XML.
     */
    public function render(array $table, array $topLists = []): string
    {
        $doc = new \DomDocument('1.0');
        $doc->formatOutput = true;

        $root = $doc->createElement('seocli');
        $doc->appendChild($root);

        $all = $doc->createElement('all');
        foreach ($table as $item) {
            $all->appendChild($this->getItem($doc, $item));
        }
        $root->appendChild($all);

        foreach ($topLists as $label => $values) {
            $topList = $doc->createElement('topList');
            $topList->setAttribute('label', $label);
            foreach ($values as $item) {
                $topList->appendChild($this->getItem($doc, $item));
            }
            $root->appendChild($topList);
        }

        return $doc->saveXML();
    }

    /**
     * Get item.
     */
    protected function getItem(\DomDocument $doc, array $item): \DOMElement
    {
        $element = $doc->createElement('item');

        foreach ($item as $key => $value) {
            $inner = $doc->createElement($key);
            $inner->nodeValue = (string) $value;
            $element->appendChild($inner);
        }

        return $element;
    }
}
