<?php

/**
 * RobotsTxt.
 */

declare(strict_types=1);

namespace SEOCLI;

use SEOCLI\Traits\Cache;
use t1gor\RobotsTxtParser\RobotsTxtParser;

/**
 * RobotsTxt.
 */
class RobotsTxt
{
    use Cache;

    public function status(Uri $uri): string
    {
        $host = $uri->get()->getHost();
        $parser = $this->getCache($host, function () use ($uri) {
            $robotsTxt = new self();

            return (new RobotsTxtParser())->setContent($robotsTxt->getRobotsTxtContent($uri));
        });

        return $parser->isDisallowed($uri->get()->getPath(), Request::USER_AGENT) ? 'XX' : 'OK';
    }

    public function getRobotsTxtContent(Uri $uri): string
    {
        return (string) (new Request(new Uri((string) $uri->get()->withQuery('')->withPath('/robots.txt'))))->getContent();
    }
}
