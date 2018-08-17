<?php

declare(strict_types = 1);

namespace SEOCLI;

use League\Uri\Http;

class Uri
{
    /**
     * URI object.
     *
     * @var \League\Uri\AbstractUri|Http
     */
    protected $uri;

    /**
     * Depth.
     *
     * @var int
     */
    protected $depth;

    /**
     * Info.
     *
     * @var array
     */
    protected $info;

    /**
     * Uri constructor.
     *
     * @param string $uri
     * @param int    $depth
     */
    public function __construct(string $uri, $depth = 0)
    {
        $this->uri = Http::createFromString($uri);
        $this->depth = $depth;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->uri;
    }

    /**
     * @return \League\Uri\AbstractUri|Http
     */
    public function get()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param array $info
     */
    public function setInfo(array $info)
    {
        $this->info = $info;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }
}
