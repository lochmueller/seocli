<?php

declare(strict_types = 1);

namespace SEOCLI;

class Request
{
    /**
     * URI.
     *
     * @var Uri
     */
    protected $uri;

    /**
     * Result.
     *
     * @var array
     */
    protected $result;

    public function __construct(Uri $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        $this->fetchResult();

        return $this->result['header'];
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        $this->fetchResult();

        return $this->result['content'];
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        $this->fetchResult();

        return $this->result['meta'];
    }

    /**
     * Fetch result.
     */
    protected function fetchResult()
    {
        if (null !== $this->result) {
            return;
        }

        try {
            $startTime = \microtime(true);
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', (string)$this->uri);
            $stopTime = \microtime(true);

            $this->result = [
                'meta' => [
                    'statusCode' => $res->getStatusCode(),
                    'timeInSeconds' => \round($stopTime - $startTime, 2),
                ],
                'header' => $res->getHeaders(),
                'content' => (string)$res->getBody(),
            ];
        } catch (\Exception $ex) {
            $this->result = [
                'meta' => [
                    'statusCode' => 'XX',
                    'timeInSecods' => \round($stopTime - $startTime, 2),
                ],
                'header' => [],
                'content' => '',
            ];
        }
    }
}
