<?php

declare(strict_types = 1);

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
            $content = $this->getRobotsTxtContent($uri);
            $robotsObjects[$host] = new RobotsTxtParser($content);
            $robotsObjects[$host]->setUserAgent(Request::USER_AGENT);
        }

        if ($robotsObjects[$host]->isDisallowed($uri->get()->getPath())) {
            return 'XX';
        }

        return 'OK';
    }

    /**
     * @param Uri $uri
     *
     * @return string
     */
    protected function getRobotsTxtContent(Uri $uri): string
    {
        $robotsUri = new Uri($uri->get()->withQuery('')->withPath('/robots.txt'));

        $request = new Request($robotsUri);
        $content = $request->getContent();

        return $content;
    }
}
