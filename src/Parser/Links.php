<?php

declare(strict_types = 1);

namespace SEOCLI\Parser;

class Links implements PaserInterface
{
    /**
     * @param \SEOCLI\Uri $uri
     * @param string      $content
     *
     * @return array
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array
    {
        //Create a new DOM document
        $dom = new \DOMDocument();

        //Parse the HTML. The @ is used to suppress any parsing errors
        //that will be thrown if the $html string isn't valid XHTML.
        @$dom->loadHTML($content);

        //Get all links. You could also use any other tag name here,
        //like 'img' or 'table', to extract other tags.
        $links = $dom->getElementsByTagName('a');

        //Iterate over the extracted links and display their URLs
        $result = [];
        foreach ($links as $link) {
            $result[] = (string)$link->getAttribute('href');
        }

        return $result;
    }
}
