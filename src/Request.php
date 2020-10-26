<?php

/**
 * Request.
 */

declare(strict_types=1);

namespace SEOCLI;

use GuzzleHttp\Client;

/**
 * Request.
 */
class Request
{
    /**
     * User agent.
     */
    public const USER_AGENT = 'CERN-LineMode/2.15';

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

    /**
     * Request constructor.
     */
    public function __construct(Uri $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Get header.
     */
    public function getHeader(): array
    {
        $this->fetchResult();

        return (array) $this->result['header'];
    }

    /**
     * Get content.
     */
    public function getContent(): string
    {
        $this->fetchResult();

        return (string) $this->result['content'];
    }

    /**
     * Get meta.
     */
    public function getMeta(): array
    {
        $this->fetchResult();

        return (array) $this->result['meta'];
    }

    /**
     * Fetch result.
     */
    protected function fetchResult(): void
    {
        if (null !== $this->result) {
            return;
        }

        $startTime = microtime(true);

        try {
            $client = $this->getClient();
            $res = $client->request('GET', (string) $this->uri);
            $stopTime = microtime(true);

            $this->result = [
                'meta' => [
                    'statusCode' => $res->getStatusCode(),
                    'timeInSeconds' => round($stopTime - $startTime, 2),
                ],
                'header' => $res->getHeaders(),
                'content' => (string) $res->getBody(),
            ];
        } catch (\Exception $ex) {
            $stopTime = microtime(true);
            $this->result = [
                'meta' => [
                    'statusCode' => 'XX',
                    'timeInSeconds' => round($stopTime - $startTime, 2),
                ],
                'header' => [],
                'content' => '',
            ];
        }
    }

    /**
     * Get connection client.
     */
    protected function getClient(): Client
    {
        $jar = new \GuzzleHttp\Cookie\CookieJar();

        return new Client([
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => self::USER_AGENT,
                'Accept' => 'text/plain, */*',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'en-US,en;q=0.8',
            ],
        ]);
    }
}
