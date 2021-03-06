<?php

/**
 * Parser.
 */

declare(strict_types=1);

namespace SEOCLI;

use SEOCLI\Parser\Headlines;
use SEOCLI\Parser\Links;
use SEOCLI\Parser\PaserInterface;
use SEOCLI\Parser\Text;
use SEOCLI\Parser\Title;

/**
 * Parser.
 */
class Parser
{
    /**
     * Get the parser results.
     */
    public function parseAll(Uri $uri, string $content): array
    {
        return array_map(function ($parser) use ($uri, $content) {
            // @var $parser PaserInterface
            return $parser->parse($uri, $content);
        }, $this->getParser());
    }

    /**
     * Get parser.
     */
    protected function getParser(): array
    {
        return [
            'links' => new Links(),
            'headlines' => new Headlines(),
            'title' => new Title(),
            'text' => new Text(),
        ];
    }
}
