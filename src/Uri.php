<?php


namespace SEOCLI;


use League\Uri\Schemes\Http;

class Uri {

	protected $uri;

	protected $depth;

	protected $infos = null;

	public function __construct(string $uri, $depth = 0){
		$this->uri = Http::createFromString($uri);
		$this->depth = $depth;
	}	

	public function __toString(){
		return (string)$this->uri;
	}

	public function get(){
		return $this->uri;
	}

	public function setInfos($infos){
		$this->infos = $infos;
	}

	public function getInfos(){
		return $this->infos;
	}

	public function getDepth(){
		return $this->depth;
	}

}