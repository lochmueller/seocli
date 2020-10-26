<?php

/**
 * Uri.
 */

declare(strict_types=1);

namespace SEOCLI;

use League\Uri\Http;

/**
 * Uri.
 */
class Uri
{
    /**
     * URI object.
     *
     * @var Http|\League\Uri\AbstractUri
     */
    protected $uri;

    /**
     * Depth.
     *
     * @var int
     */
    protected $depth = 0;

    /**
     * Info.
     *
     * @var array
     */
    protected $info;

    /**
     * Uri constructor.
     */
    public function __construct(string $uri, int $depth = 0)
    {
        $this->uri = Http::createFromString($uri);
        $this->depth = $depth;
    }

    public function __toString(): string
    {
        return (string) $this->uri;
    }

    /**
     * @return Http|\League\Uri\AbstractUri
     */
    public function get()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getInfo(): ?array
    {
        return $this->info;
    }

    public function setInfo(array $info): void
    {
        $this->info = $info;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Migrate the links to absolute URIs, drop external and
     * remove the fragment.
     *
     * @return array
     */
    public function normalizeLinks(array $links)
    {
        $result = [];
        foreach ($links as $link) {
            try {
                $checkUri = Http::createFromString($link);
            } catch (\Exception $ex) {
                continue;
            }
            if ('' === (string) $checkUri->getHost()) {
                $checkUri = $checkUri->withPath('/'.ltrim($checkUri->getPath(), '/'));
                $checkUri = $checkUri->withHost($this->get()->getHost())->withScheme($this->get()->getScheme());
            }
            $checkUri = $checkUri->withFragment('');

            if ($this->get()->getHost() !== $checkUri->getHost()) {
                continue;
            }

            $result[] = (string) $checkUri;
        }

        return array_unique($result);
    }
}
