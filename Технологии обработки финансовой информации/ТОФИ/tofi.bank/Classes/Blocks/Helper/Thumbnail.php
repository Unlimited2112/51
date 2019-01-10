<?php
Loader::loadLib('Transliterator', 'Formaters');

class Thumbnail extends Block
{
	protected $params;
	public function initialize($params=array()) {
		$this->params = $params;
	}
	
	function process()
	{
		Loader::loadLib('Thumbnail', 'Vendors');
		
		$params = $this->params;
		
		$image = isset($params['image']) ? $params['image'] : '';
		$width = isset($params['width']) ? $params['width'] : 100;
		$height = isset($params['height']) ? $params['height'] : 100;
		$method = isset($params['method']) ? $params['method'] : 'resize';
		$out = '<img';
		foreach ($params as $k => $v)
		{
			if (!in_array($k, array("image", "method", "width", "height")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		
		if (!empty($image))
		{
			$imageinfo = pathinfo(urldecode($image));
			$thumbpath = substr(str_replace(Config::$contentPath, Config::$contentPath . 'Thumbnails/', $imageinfo['dirname']).'/', 1);
			$thumbimage = $thumbpath . $width . 'x' . $height . '_' . $imageinfo['basename'];
			
			if (is_file(Config::$filePath . $image) and (!is_file(Config::$filePath . $thumbimage) or filemtime(Config::$filePath . $thumbimage) < filemtime(Config::$filePath . $image)))
			{
				$thumb = PhpThumbFactory::create(Config::$filePath . $image);
				$thumb->$method($width, $height);
				
				FileSystem::installFolder(Config::$filePath . $thumbpath);
				
				$thumb->save(Config::$filePath . $thumbimage);
			}
			
			$out .= ' src="/' . $thumbimage .'"';
		}
		
		$out .= ' />';
		
		return $out;
	}
}