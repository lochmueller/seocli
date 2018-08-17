<?php

declare(strict_types = 1);

namespace SEOCLI;

class Parser
{
    public function parseAll(Uri $uri, $content)
    {
        $infos = [];
        foreach ($this->getParser() as $key => $parser) {
            $infos[$key] = $parser->parse($uri, $content);
        }

        return $infos;
    }

    protected function getParser()
    {
        return [
            'links' => new \SEOCLI\Parser\Links(),
            'headlines' => new \SEOCLI\Parser\Headlines(),
            'title' => new \SEOCLI\Parser\Title(),
            'text' => new \SEOCLI\Parser\Text(),
        ];
    }
}
