<?php


namespace SEOCLI;

class Worker extends Singleton {

	static protected $uris = [];

	static protected $depth = 2;

	public function add(Uri $uri){
		self::$uris[] = $uri;
	}

	public function setDepth($depth){
		self::$depth = $depth;
	}

	public function prefetchOne(){
		foreach (self::$uris as $key => $uri) {
			if($uri->getInfos() === null) {

				
				$request = new \SEOCLI\Request($uri);

				$infos = (array)$request->getMeta();
				// $infos['content'] = $request->getContent();
				// $infos['header'] = $request->getHeader();


				$uri->setInfos($infos);

				//Create a new DOM document
				$dom = new \DOMDocument;

				//Parse the HTML. The @ is used to suppress any parsing errors
				//that will be thrown if the $html string isn't valid XHTML.
				@$dom->loadHTML($request->getContent());

				//Get all links. You could also use any other tag name here,
				//like 'img' or 'table', to extract other tags.
				$links = $dom->getElementsByTagName('a');

				//Iterate over the extracted links and display their URLs
				foreach ($links as $link){
				    echo (string)$link->getAttribute('href')."\n";
				}

				return true;
			}
		}
		return false;
	}


	public function getOpen(){
		return array_filter(self::$uris, function(Uri $uri){
			return $uri->getInfos() === null;
		});
	}

	public function getFetched(){
		return array_filter(self::$uris, function(Uri $uri){
			return $uri->getInfos() !== null;
		});
	}

	public function get(){
		return self::$uris;
	}

	
	

}