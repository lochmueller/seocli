<?php

declare(strict_types = 1);

namespace SEOCLI;

use League\Uri\Schemes\Http;

class Worker extends Singleton
{
    protected static $uris = [];

    protected static $depth = 1;

    public function add(Uri $uri)
    {
        self::$uris[] = $uri;
    }

    public function setDepth($depth)
    {
        self::$depth = $depth;
    }

    public function prefetchOne()
    {
        foreach (self::$uris as $key => $uri) {
            if (null === $uri->getInfos()) {
                $request = new \SEOCLI\Request($uri);

                $infos = (array)$request->getMeta();
                $content = $request->getContent();
                $infos['contentSize'] = \mb_strlen($content);

                $headers = $request->getHeader();
                $infos['contentType'] = isset($headers['Content-Type']) ? \implode('', $headers['Content-Type']) : '';
                // $infos['content'] = $request->getContent();
                // $infos['header'] = $request->getHeader();

                // var_dump(strip_tags($content));

                // title, titleLength
                // meta Description, metaDescription Length
                // meta Keywords, metakeywords Length

                // wordCount
                // textRatio
                // crawlDepth
                // links

                $parser = new \SEOCLI\Parser();
                $parserResult = $parser->parseAll($uri, $request->getContent());

                $uri->setInfos($infos);

                if ($uri->getDepth() < self::$depth) {
                    $worker = \SEOCLI\Worker::getInstance();
                    foreach ($this->cleanupLinksForWorker($uri, $parserResult['links']) as $link) {
                        $worker->add(new \SEOCLI\Uri($link, $uri->getDepth() + 1));
                    }
                }

                return (string)$uri;
            }
        }

        return false;
    }

    public function getOpen()
    {
        return \array_filter(self::$uris, function (Uri $uri) {
            return null === $uri->getInfos();
        });
    }

    public function getFetched()
    {
        return \array_filter(self::$uris, function (Uri $uri) {
            return null !== $uri->getInfos();
        });
    }

    public function get()
    {
        return self::$uris;
    }

    protected function cleanupLinksForWorker(Uri $uri, $links)
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
                $checkUri = $checkUri->withHost($uri->get()->getHost());
                $checkUri = $checkUri->withScheme($uri->get()->getScheme());
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

        return $result;
    }
}
