<?php

class datacache {
	private $dir;	// path with  a trailing slash, 'cache/'
	private $ttl;
	
	public function __construct($dir, $ttl = 86400) { // default ttl 86400s = 24h
		if(is_dir($dir)) $this->dir = $dir;
		else {
			if(mkdir($dir, 0775)) $this->dir = $dir;
			else die("Unable to create or use cache directory.");
		}
		$this->ttl = $ttl;
	}
	
	public function cache($var, $data = null, $ttl = null) {
		if(empty($data)) {
			$ttl = empty($ttl) ? $this->ttl : $ttl; // override default ttl if neededd
			if(file_exists($this->dir.$var) && time() - $ttl < filemtime($this->dir.$var)) {
				return unserialize(file_get_contents($this->dir.$var));
			}
			else return false;
		}
		else {
			$cached = fopen($this->dir.$var, 'w');
			fwrite($cached, serialize($data));
			fclose($cached);
		}
	}

}

?>
