<?php

declare(strict_types = 1);

namespace SEOCLI;

use League\CLImate\CLImate;

class Application
{
    /**
     * @var CLImate
     */
    protected $climate;

    /**
     * @throws \Exception
     */
    public function run()
    {
        $this->init();
        $this->setArguments();

        $worker = Worker::getInstance();
        $worker->setDepth($this->climate->arguments->get('depth'));
        $worker->add(new Uri($this->climate->arguments->get('uri')));

        $this->climate->out('Start fetching elements...');
        $progress = $this->climate->progress()->total(\count($worker->get()));
        $progress->current(0);
        try {
            while ($currentUri = $worker->prefetchOne()) {
                \pcntl_signal_dispatch();
                $progress->current(\count($worker->getFetched()), $currentUri);
                $progress->total(\count($worker->get()));
            }
        } catch (InterruptException $ex) {
            // Do nothing to display the output
        }

        // Output
        $this->renderOutput($worker->getFetched());
    }

    /**
     * @throws \Exception
     */
    protected function setArguments()
    {
        $this->climate->arguments->add([
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
        ]);

        try {
            $this->climate->arguments->parse();
        } catch (\Exception $ex) {
            $this->climate->usage();
            die();
        }
    }

    /**
     * @param array $uris
     */
    protected function renderOutput(array $uris)
    {
        // Output
        $table = [];
        foreach ($uris as $uri) {
            /* @var $uri Uri */
            $table[] = ['uri' => (string)$uri] + $uri->getInfo();
        }

        \usort($table, function ($a, $b) {
            return \strcmp($a['uri'], $b['uri']);
        });

        $this->climate->blue('All result:');
        $this->climate->table($table);

        $this->renderTopList('Slowest pages', $table, function ($a, $b) {
            return $a['timeInSeconds'] < $b['timeInSeconds'];
        });

        $this->renderTopList('Biggest pages', $table, function ($a, $b) {
            return $a['documentSizeInMb'] < $b['documentSizeInMb'];
        });

        $this->renderTopList('Shortest title', $table, function ($a, $b) {
            return $a['titleLength'] > $b['titleLength'];
        });

        $this->renderTopList('Longest title', $table, function ($a, $b) {
            return $a['titleLength'] < $b['titleLength'];
        });

        $this->renderTopList('Lowest textRatio', $table, function ($a, $b) {
            return $a['textRatio'] > $b['textRatio'];
        });
    }

    protected function init()
    {
        \error_reporting(E_ALL);
        \pcntl_signal(SIGINT, [$this, 'signalHandler']);
        $this->climate = new CLImate();
    }

    /**
     * @param $label
     * @param $data
     * @param callable $sortFunction
     */
    protected function renderTopList($label, $data, callable $sortFunction)
    {
        $limit = 5;
        \usort($data, $sortFunction);
        $this->climate->red('Top ' . $limit . ': ' . $label);
        $this->climate->table(\array_slice($data, 0, $limit));
    }

    /**
     * signal handler.
     *
     * @param int $signal
     *
     * @throws InterruptException
     */
    protected function signalHandler(int $signal)
    {
        throw new InterruptException('Trigger Signal: ' . $signal);
    }
}
