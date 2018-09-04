<?php

/**
 * Text.
 */

declare(strict_types = 1);

namespace SEOCLI\Parser;

/**
 * Text.
 */
class Text implements PaserInterface
{
    /**
     * @param \SEOCLI\Uri $uri
     * @param string      $content
     *
     * @return array
     */
    public function parse(\SEOCLI\Uri $uri, string $content): array
    {
        $text = \preg_replace('/<script(.*?)<\/script>/is', '', $content);
        $text = \strip_tags($text);

        $contentLength = \mb_strlen($content);

        return [
            'text' => $text,
            'wordCount' => \str_word_count($text),
            'textRatio' => \round($contentLength ? \mb_strlen($text) * 100 / $contentLength : 0, 2),
        ];
    }
}
