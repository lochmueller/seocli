<?php


namespace SEOCLI;

class Uri {

	protected $uri;

	public function __construct(string $uri){
		$this->uri = $uri;
	}	

	public function get(){
		return $this->uri;
	}
	

	
	

}