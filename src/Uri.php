<?php


namespace SEOCLI;

class Uri {

	protected $uri;

	protected $depth;

	protected $infos = null;

	public function __construct(string $uri, $depth = 0){
		$this->uri = $uri;
		$this->depth = $depth;
	}	

	public function __toString(){
		return $this->uri;
	}

	public function setInfos($infos){
		$this->infos = $infos;
	}

	public function getInfos(){
		return $this->infos;
	}

	public function getDepth(){
		$this->depth = $depth;
	}

}