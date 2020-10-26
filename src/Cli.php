<?php

/**
 * Cli.
 */

declare(strict_types=1);

namespace SEOCLI;

use League\CLImate\CLImate;
use SEOCLI\Traits\SingletonInstance;

/**
 * Cli.
 */
class Cli extends CLImate
{
    use SingletonInstance;

    /**
     * Cli constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->arguments->add([
            'uri' => [
                'prefix' => 'u',
                'longPrefix' => 'uri',
                'description' => 'The base URI to start the SEO CLI',
                'required' => true,
                'castTo' => 'string',
            ],
            'depth' => [
                'prefix' => 'd',
                'longPrefix' => 'depth',
                'description' => 'The depth of the crawler',
                'required' => false,
                'defaultValue' => 1,
                'castTo' => 'int',
            ],
            'format' => [
                'prefix' => 'f',
                'longPrefix' => 'format',
                'description' => 'The format of the output [text,json,xml,csv,none]',
                'required' => false,
                'defaultValue' => 'text',
                'castTo' => 'string',
            ],
            'topCount' => [
                'prefix' => 't',
                'longPrefix' => 'top-count',
                'description' => 'The number of items in the top lists [0=disable]',
                'required' => false,
                'defaultValue' => 5,
                'castTo' => 'int',
            ],
        ]);

        try {
            $this->arguments->parse();
        } catch (\Exception $ex) {
            $this->exception($ex);
            $this->usage();
            die();
        }
    }

    /**
     * Render a exception.
     */
    public function exception(\Exception $exception): void
    {
        $this->br();
        $this->red()->blink()->border('*', 30);
        $this->red()->flank('Exception', '*');
        $this->red()->blink()->border('*', 30);
        $this->error('Message: '.$exception->getMessage());
        $this->error('File: '.$exception->getFile());
        $this->error('Line: '.$exception->getLine());
        $this->red()->blink()->border('*', 30);
        $this->br();
    }
}
