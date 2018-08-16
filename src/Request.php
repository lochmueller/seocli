<?php

declare(strict_types = 1);

namespace SEOCLI;

class Request
{
    /**
     * URI.
     *
     * @var \SEOCLI\URI
     */
    protected $uri;

    /**
     * Result.
     *
     * @var array
     */
    protected $result;

    public function __construct(\SEOCLI\URI $uri)
    {
        $this->uri = $uri;
    }

    public function getHeader()
    {
        $this->fetchResult();

        return $this->result['header'];
    }

    public function getContent()
    {
        $this->fetchResult();

        return $this->result['content'];
    }

    public function getMeta()
    {
        $this->fetchResult();

        return $this->result['meta'];
    }

    protected function fetchResult()
    {
        if (null !== $this->result) {
            return;
        }

        $starttime = \microtime(true);

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', (string)$this->uri);
        $stoptime = \microtime(true);

        $this->result = [
            'meta' => [
                'statusCode' => $res->getStatusCode(),
                'time' => \round($stoptime - $starttime, 2),
            ],
            'header' => $res->getHeaders(),
            'content' => (string)$res->getBody(),
        ];
    }
}
