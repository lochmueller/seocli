<?php

declare(strict_types = 1);

namespace SEOCLI;

class Application
{
    public function run()
    {
        \pcntl_signal(SIGINT, [$this, 'signalHandler']);
        $climate = new \League\CLImate\CLImate();
        $climate->arguments->add([
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
            $climate->arguments->parse();
        } catch (\Exception $ex) {
            $climate->usage();
            die();
        }

        $worker = \SEOCLI\Worker::getInstance();
        $worker->setDepth($climate->arguments->get('depth'));
        $worker->add(new \SEOCLI\Uri($climate->arguments->get('uri')));

        $climate->out('Start fetching elements...');
        $progress = $climate->progress()->total(\count($worker->get()));
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
        $climate->out('Result...');
        $table = [];
        foreach ($worker->getFetched() as $uri) {
            $table[] = ['uri' => (string)$uri] + $uri->getInfos();
        }
        $climate->table($table);
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
