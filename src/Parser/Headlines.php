<?php

declare(strict_types = 1);

namespace SEOCLI\Parser;

class Headlines
{
    public function parse(\SEOCLI\Uri $uri, $content)
    {
        $regex = '/<h([1-5])[^>]*>(.*?)<\/h[0-5]>/';

        $headlines = [];
        if (\preg_match_all($regex, $content, $matches)) {
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
