<?php

/**
 * Links.
 */

declare(strict_types=1);

namespace SEOCLI\Parser;

/**
 * Links.
 */
class Links implements PaserInterface
{
    /**
     * Parse links.
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
        //https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
        $result = [];
        foreach ($links as $link) {
            $href = (string) $link->getAttribute('href');
            if(false === preg_match('/^([a-z]+)s?\:/', $href, $match) || 'http' == $match[1]) {
                $result[] = $href;
            }
        }

        return $result;
    }
}
