<?php

namespace SEOCLI;

class Parser {
	

	public function parseAll(Uri $uri, $content){
		$infos = [];
		foreach ($this->getParser() as $key => $parser) {
			$infos[$key] = $parser->parse( $uri, $content);
		}
		return $infos;
	}

	protected function getParser(){
		return [
			'links' => new \SEOCLI\Parser\Links(),
		];
	}
	

}