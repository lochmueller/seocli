<?php

declare(strict_types = 1);

namespace SEOCLI;

use League\Uri\Http;

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
                $info['documentSizeInMb'] = \round(\mb_strlen($content) / 1024 / 1024, 2);

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

        foreach ($links as $link) {
            try {
                $checkUri = Http::createFromString($link);
            } catch (\Exception $ex) {
                continue;
            }
            if ('' === (string)$checkUri->getHost()) {
                $checkUri = $checkUri->withPath('/' . \ltrim($checkUri->getPath(), '/'));
                $checkUri = $checkUri->withHost($uri->get()->getHost())->withScheme($uri->get()->getScheme());
            }
            $checkUri = $checkUri->withFragment('');

            if ($uri->get()->getHost() !== $checkUri->getHost()) {
                continue;
            }

            if (\in_array((string)$checkUri, $alreadyQueued, true)) {
                continue;
            }

            $result[] = (string)$checkUri;
        }

        return \array_unique($result);
    }
}
