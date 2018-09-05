<?php

/**
 * Worker.
 */

declare(strict_types = 1);

namespace SEOCLI;

use SEOCLI\Traits\Singleton;

/**
 * Worker.
 */
class Worker
{
    use Singleton;

    /**
     * @var array
     */
    protected static $uris = [];

    /**
     * @var int
     */
    protected static $depth = 1;

    /**
     * @param Uri $uri
     */
    public function add(Uri $uri): void
    {
        self::$uris[] = $uri;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth): void
    {
        self::$depth = $depth;
    }

    /**
     * @return bool|string
     */
    public function prefetchOne()
    {
        foreach (self::$uris as $key => $uri) {
            /** @var $uri Uri */
            if (null === $uri->getInfo()) {
                $request = new Request($uri);

                $info = (array)$request->getMeta();
                $content = $request->getContent();
                $info['crawlDepth'] = $uri->getDepth();
                $info['documentSizeInMb'] = (new Format())->megaBytes(\mb_strlen($content));

                $headers = $request->getHeader();
                $info['contentType'] = isset($headers['Content-Type']) ? \implode('', $headers['Content-Type']) : '';
                // $infos['content'] = $request->getContent();
                // $infos['header'] = $request->getHeader();

                // meta Description, metaDescription Length
                // meta Keywords, metakeywords Length

                // wordCount
                // textRatio

                $parser = new Parser();
                $parserResult = $parser->parseAll($uri, $request->getContent());

                $info['title'] = $parserResult['title']['text'];
                $info['titleLength'] = $parserResult['title']['length'];
                $info['links'] = \count($parserResult['links']);

                $info['wordCount'] = $parserResult['text']['wordCount'];
                $info['textRatio'] = $parserResult['text']['textRatio'];

                $robotsTxt = new RobotsTxt();
                $info['robotsTxt'] = $robotsTxt->status($uri);

                $uri->setInfo($info);

                if ($uri->getDepth() < self::$depth) {
                    $worker = self::getInstance();
                    foreach ($this->cleanupLinksForWorker($uri, $parserResult['links']) as $link) {
                        $worker->add(new Uri($link, $uri->getDepth() + 1));
                    }
                }

                return (string)$uri . ' (' . $uri->getDepth() . ')';
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getOpen()
    {
        return \array_filter(self::$uris, function (Uri $uri) {
            return null === $uri->getInfo();
        });
    }

    /**
     * @return array
     */
    public function getFetched()
    {
        return \array_filter(self::$uris, function (Uri $uri) {
            return null !== $uri->getInfo();
        });
    }

    /**
     * @return array
     */
    public function get()
    {
        return self::$uris;
    }

    /**
     * @param Uri $uri
     * @param $links
     *
     * @return array
     */
    protected function cleanupLinksForWorker(Uri $uri, $links): array
    {
        $result = [];
        $alreadyQueued = \array_map(function ($uri) {
            return (string)$uri;
        }, self::$uris);

        foreach ($uri->normalizeLinks($links) as $link) {
            if (\in_array($link, $alreadyQueued, true)) {
                continue;
            }
            $result[] = $link;
        }

        return $result;
    }
}
