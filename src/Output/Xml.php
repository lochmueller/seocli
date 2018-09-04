<?php

/**
 * Xml.
 */

declare(strict_types = 1);

namespace SEOCLI\Output;

/**
 * Xml.
 */
class Xml implements OutputInterface
{
    /**
     * @param array $table
     * @param array $topLists
     *
     * @return string
     */
    public function render(array $table, array $topLists = []): string
    {
        $doc = new \DomDocument('1.0');

        $root = $doc->createElement('seocli');
        $root = $doc->appendChild($root);

        $signed_values = ['a' => 'eee', 'b' => 'sd', 'c' => 'df'];

        foreach ($signed_values as $key => $val) {
            // add node for each row
            $occ = $doc->createElement('error');
            $occ = $root->appendChild($occ);
            // add a child node for each field
            foreach ($signed_values as $fieldname => $fieldvalue) {
                $child = $doc->createElement($fieldname);
                $child = $occ->appendChild($child);
                $value = $doc->createTextNode($fieldvalue);
                $value = $child->appendChild($value);
            }
        }
        $xml_string = $doc->saveXML();
        echo $xml_string;

        \var_dump($table);
        \var_dump($topLists);

        return 'XML';
    }

    protected function getItem(\DomDocument $doc, $item)
    {
        $item = $doc->createElement('item');

        $result = ' On items';

        return $result;
    }
}
