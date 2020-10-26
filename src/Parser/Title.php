<?php

/**
 * Title.
 */

declare(strict_types=1);

namespace SEOCLI\Parser;

/**
 * Title.
 */
class Title implements PaserInterface
{
    /**
     * parse title.
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array
    {
        $regex = '/<title>(.*?)<\/title>/';
        if (preg_match_all($regex, $content, $matches)) {
            return [
                'text' => $matches['1'][0],
                'length' => mb_strlen($matches['1'][0]),
            ];
        }

        return [
            'text' => '',
            'length' => 0,
        ];
    }
}
