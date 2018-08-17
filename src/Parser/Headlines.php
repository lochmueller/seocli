<?php

declare(strict_types = 1);

namespace SEOCLI\Parser;

class Headlines implements PaserInterface
{
    /**
     * Search Regex.
     */
    const REGEX = '/<h([1-5])[^>]*>(.*?)<\/h[0-5]>/';

    /**
     * @param \SEOCLI\Uri $uri
     * @param string      $content
     *
     * @return array
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array
    {
        $headlines = [];
        if (\preg_match_all(self::REGEX, $content, $matches)) {
            foreach ($matches[1] as $key => $h) {
                $headlines[] = [
                    'h' => $h,
                    'text' => $matches[2][$key],
                ];
            }
        }

        return $headlines;
    }
}
