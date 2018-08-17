<?php

declare(strict_types=1);

namespace SEOCLI;

use RobotsTxtParser;

class RobotsTxt
{
    /**
     * Robots txt objects.
     *
     * @var array
     */
    protected $robotsObjects = [];

    /**
     * @param Uri $uri
     *
     * @return string
     */
    public function status(Uri $uri)
    {
        $host = $uri->get()->getHost();
        if (!isset($robotsObjects[$host])) {
            $robotsObjects[$host] = new RobotsTxtParser($this->getRobotsTxtContent($uri));
            $robotsObjects[$host]->setUserAgent(Request::USER_AGENT);
        }
        return $robotsObjects[$host]->isDisallowed($uri->get()->getPath()) ? 'XX' : 'OK';
    }

    /**
     * @param Uri $uri
     *
     * @return string
     */
    protected function getRobotsTxtContent(Uri $uri): string
    {
        return (string) (new Request(new Uri((string) $uri->get()->withQuery('')->withPath('/robots.txt'))))->getContent();
    }
}
