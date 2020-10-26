<?php

/**
 * RobotsTxt.
 */

declare(strict_types=1);

namespace SEOCLI;

use RobotsTxtParser;
use SEOCLI\Traits\Cache;

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
            $parser = new RobotsTxtParser($robotsTxt->getRobotsTxtContent($uri));
            $parser->setUserAgent(Request::USER_AGENT);

            return $parser;
        });

        return $parser->isDisallowed($uri->get()->getPath()) ? 'XX' : 'OK';
    }

    public function getRobotsTxtContent(Uri $uri): string
    {
        return (string) (new Request(new Uri((string) $uri->get()->withQuery('')->withPath('/robots.txt'))))->getContent();
    }
}
