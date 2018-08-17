<?php

declare(strict_types = 1);

namespace SEOCLI\Parser;

class Text
{
    public function parse(\SEOCLI\Uri $uri, $content)
    {
        $text = \preg_replace('/<script(.*?)<\/script>/is', '', $content);
        $text = \strip_tags($text);

        return [
            'text' => $text,
            'wordCount' => \str_word_count($text),
            'textRatio' => \round(\mb_strlen($text) * 100 / \mb_strlen($content), 2),
        ];
    }
}
