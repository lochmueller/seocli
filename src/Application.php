<?php

/**
 * Application.
 */

declare(strict_types=1);

namespace SEOCLI;

use SEOCLI\Output\OutputInterface;
use SEOCLI\Output\Text;

/**
 * Application.
 */
class Application
{
    /**
     * CLI object.
     *
     * @var Cli
     */
    protected $climate;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 'On');
        pcntl_signal(SIGINT, [$this, 'signalHandler']);
        $this->climate = Cli::getInstance();
    }

    /**
     * Run the application.
     */
    public function run(): void
    {
        try {
            $worker = $this->getFinishedWorker();
            $this->renderOutput($worker->getFetched());
        } catch (\Exception $ex) {
            $this->climate->exception($ex);
        }
    }

    /**
     * Get finished worker.
     */
    protected function getFinishedWorker(): Worker
    {
        $worker = Worker::getInstance();
        $worker->setDepth($this->climate->arguments->get('depth'));
        $worker->add(new Uri($this->climate->arguments->get('uri')));

        $format = $this->climate->arguments->get('format');
        if ('text' === $format) {
            $this->climate->out('Start fetching elements...');

            $progress = $this->climate->progress(\count($worker->get()));
            $progress->current(0);
        }

        try {
            while ($currentUri = $worker->prefetchOne()) {
                pcntl_signal_dispatch();
                if ('text' === $format) {
                    $progress->current(\count($worker->getFetched()), $currentUri);
                    $progress->total(\count($worker->get()));
                }
            }
        } catch (InterruptException $ex) {
            // Do nothing to display the output
        }

        return $worker;
    }

    /**
     * Render output.
     */
    protected function renderOutput(array $uris): void
    {
        $format = $this->climate->arguments->get('format');
        $rendererName = Text::class;
        if (class_exists('SEOCLI\\Output\\'.ucfirst(mb_strtolower($format)))) {
            $rendererName = 'SEOCLI\\Output\\'.ucfirst(mb_strtolower($format));
        }
        /** @var OutputInterface $renderer */
        $renderer = new $rendererName();

        $table = $this->getBaseTable($uris);

        $topLists = [];

        $limit = (int) $this->climate->arguments->get('topCount');
        if ($limit) {
            $topLists = [
                'Slowest pages' => $this->sortAndLimitList($table, function ($a, $b) {
                    return ($a['timeInSeconds'] <=> $b['timeInSeconds']) * -1;
                }),
                'Biggest pages' => $this->sortAndLimitList($table, function ($a, $b) {
                    return ($a['documentSizeInMb'] <=> $b['documentSizeInMb']) * -1;
                }),
                'Shortest title' => $this->sortAndLimitList($table, function ($a, $b) {
                    return $a['titleLength'] <=> $b['titleLength'];
                }),
                'Longest title' => $this->sortAndLimitList($table, function ($a, $b) {
                    return ($a['titleLength'] <=> $b['titleLength']) * -1;
                }),
                'Lowest textRatio' => $this->sortAndLimitList($table, function ($a, $b) {
                    return $a['textRatio'] <=> $b['textRatio'];
                }),
            ];
        }
        echo $renderer->render($table, $topLists);
    }

    protected function getBaseTable(array $uris): array
    {
        $table = [];
        foreach ($uris as $uri) {
            // @var $uri Uri
            $table[] = ['uri' => (string) $uri] + $uri->getInfo();
        }

        usort($table, function ($a, $b) {
            return strcmp($a['uri'], $b['uri']);
        });

        return $table;
    }

    /**
     * Sort and limit list.
     */
    protected function sortAndLimitList(array $data, callable $sortFunction): array
    {
        $limit = (int) $this->climate->arguments->get('topCount');
        usort($data, $sortFunction);

        return \array_slice($data, 0, $limit);
    }

    /**
     * Signal handler.
     *
     * @throws InterruptException
     */
    protected function signalHandler(int $signal): void
    {
        throw new InterruptException('Trigger Signal: '.$signal);
    }
}
