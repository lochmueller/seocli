<?php


require_once('vendor/autoload.php');

$climate = new \League\CLImate\CLImate();
$climate->arguments->add([
    'uri' => [
        'prefix'       => 'u',
        'longPrefix'   => 'uri',
        'description'  => 'The base URI to start the SEO CLI',
        'required' => true,
        'castTo' => 'string',
    ],
    'depth' => [
        'prefix'      => 'd',
        'longPrefix'  => 'depth',
        'description' => 'The depth of the crawler',
        'required'    => false,
        'defaultValue' => 2,
        'castTo' 		=> 'int',
    ],
]);

try {
	$climate->arguments->parse();
} catch(\Exception $ex) {
	$climate->usage();
	die();
}

$worker = \SEOCLI\Worker::getInstance();
$worker->setDepth($climate->arguments->get('depth'));
$worker->add(new \SEOCLI\Uri($climate->arguments->get('uri')));

$climate->out('Start fetching elements...');
$progress = $climate->progress()->total(count($worker->get()));
while($worker->prefetchOne()) {
	$progress->current(count($worker->getFetched()));
	$progress->total(count($worker->get()));
}

// Output
$climate->out('Result...');
$table = [];
foreach ($worker->get() as $uri) {
	$table[] = ['uri' => (string)$uri]+$uri->getInfos();
}
$climate->table($table);