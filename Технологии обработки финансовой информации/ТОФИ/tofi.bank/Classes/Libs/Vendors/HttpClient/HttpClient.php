<?php
class HttpClient{
	// settings
	public $proto		= "tcp";
	public $host		= "localhost";
	public $port		= 80;
	public $timeout		= 5;
	public $chunk		= 1048576;	// if $only_head=1 => $chunk=512
	public $only_headers	= 0;
	public $compare_length	= 1;		// compare Content-Length with Length of Answer
	
	// internal vars
	public $_socket	=NULL;
	public $error		=NULL;
	public $ans		=NULL;
	
	function __construct($opts =NULL){
		if(is_array($opts)){
			foreach($opts as $k =>$v){
				$this->{$k} =$v;
			}
		}
	}
	
	function __destruct(){
		$this->close();
	}
	
	function open(){
		$socket =NULL;
		if(! isset($this->_socket)){
			$this->error	=NULL;
			
			if(function_exists('pfsockopen')){
				if(! ($socket =@pfsockopen($this->proto. '://'. $this->host, $this->port, $errno, $errstr, $this->timeout)) or ($errno or $errstr)){
					return ($this->error ="Socket error: Can't create socket ($errstr)");
				}

				if(! @stream_set_blocking($socket, FALSE)){
					return ($this->error ="Socket error: Can't set nonblocking mode");
				}
				
				if(! @stream_set_timeout($socket, $this->timeout)){
					return ($this->error ="Socket error: Can't set timeout");
				}

				if($this->only_headers){
					$this->chunk =512;
				}
			}else{
				throw new Exception('Необходимо включить функции для sockets в php.ini');	
			}
			$this->_socket =$socket;
		}
		return (isset($this->_socket) and !$this->error);
	}
	
	function error(){
		return $this->error;
	}
	
	function send($msg){
		$stime	=$this->getmicrotime();
		while(@fwrite($this->_socket, $msg) <0 and $this->getmicrotime() -$stime <$this->timeout){
			continue;
		}
		return ($this->getmicrotime() -$stime <$this->timeout);
	}
	
	function ans(){
		$gret	=($ret =3);
		$stime	=$this->getmicrotime();
// NOT TESTED
		$gstime =$stime;
		$data	="";

		$err_mode =error_reporting();

		error_reporting(~E_ALL);
		while($ret >0 and $this->getmicrotime() -$stime <$this->timeout and !@feof($this->_socket)){
			while(($chunk =@fread($this->_socket, $this->chunk)) and !empty($chunk)){
				$data	.=$chunk;
				$ret	=$gret;

				if($this->only_headers && preg_match('/(.*?)(\r?\n){2,}/s', $data, $rexp)){
					$data =$rexp[1];
					$ret =0;
					break;
				}

				@usleep(300000);
			}
			
			// HTTP answer?
//			if(preg_match('/content-length:\s*?(\d+)/si', $data, $rexp) and preg_match('/.*?(\r?\n){2}(.*)/si', $data, $cont) and strlen($cont[2]) ==$rexp[1]){
//				break;
//			}
// NOT TESTED [start]
			if(isset($this->compare_length) and $this->compare_length and preg_match('/content-length:\s*?(\d+)/si', $data, $rexp) and preg_match('/.*?(\r?\n){2}(.*)/si', $data, $cont)){
				if(strlen($cont[2]) >=$rexp[1]){
					break;
				}else if(strlen($cont[2]) <$rexp[1]){
					if(($this->getmicrotime()+1) -$stime >= $this->timeout and $gstime-$stime < $this->timeout*1.5){
						++ $stime;
						$ret =$gret;
					}
				}
			}
// [end]


			$ret -=1;
			@usleep(500000);
		}
		error_reporting($err_mode);
		
		return $this->ans =$data;
	}

	function parse_head($head =null){
		$return =array();
		if(preg_match('/^((http\/\d+(\.\d+)?)\s+(\d+)\s+(.*))/im', (($head)? $head: $this->head()), $rexp)){
			$return['protocol'] =$rexp[2];
			$return['code'] =$rexp[4];
			$return['message'] =$rexp[5];
		}
		preg_match_all('/^([\w-]+):\s*(.*?)$/mi', (($head)? $head: $this->head()), $rexp);
		foreach($rexp[1] as $i =>$var){
			$return[strtolower($var)] = $rexp[2][$i];
		}
		return $return;
	}
	
	function head(){
		if(($this->ans or $this->ans()) and (preg_match('/^(.*?)(\r?\n){2}(.*)$/s', $this->ans, $rexp))){
			return $rexp[1];
		}
		return ($this->ans)? $this->ans: NULL;
	}
	
	function body(){
		if(($this->ans or $this->ans()) and (preg_match('/^(.*?)(\r?\n){2}(.*)?$/s', $this->ans, $rexp))){
			return $rexp[3];
		}
		return NULL;
	}
	
	function flush(){
		$this->ans();
		$this->ans =NULL;
	}

	function close(){
		if($this->_socket){
			@stream_set_blocking($this->_socket, TRUE);
			@fclose($this->_socket);

			unset($this->_socket);
			$this->_socket =null;
		}
	}
		
	// microtime
	function getmicrotime(){
		$mtime	=explode(" ", microtime());
		return (double)($mtime[0]) +(double)($mtime[1]);
	}	
}