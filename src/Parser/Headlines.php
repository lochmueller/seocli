<?php

/**
 * Headlines.
 */

declare(strict_types=1);

namespace SEOCLI\Parser;

/**
 * Headlines.
 */
class Headlines implements PaserInterface
{
    /**
     * Search Regex.
     */
    public const REGEX = '/<h([1-5])[^>]*>(.*?)<\/h\1>/';

    /**
     * Parse headlines.
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array
    {
        $headlines = [];
        if (preg_match_all(self::REGEX, $content, $matches)) {
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
