<?php
class FileSystem 
{
	/**
	 * @param string $folder Folder path
	 * @return bool
	 */
	static public function installFolder($folder)
	{
		$folder =str_replace('\\', '/', $folder);
		if(!@is_dir($folder))
		{
			$base = Config::$filePath;
			$folder = str_replace($base, "", $folder);
			$a =explode('/', $folder);
			$p = $base;
			foreach($a as $part){
				$p .= ($part .'/');
				if(!@is_dir($p)){
					@mkdir($p, 0777);
					@chmod($p, 0777);
				}
			}
		}
		return @is_dir($folder);
	}
	
	/**
	 * @param string $file File path
	 * @param string $data File data
	 * @param string $mode File mode
	 * @return bool
	 */
	static public function writeFile($file, $data, $mode = 'wt')
	{
		$fh = fopen($file, $mode);
		if ($fh)
		{
			fwrite($fh, $data);
			fclose($fh);
			return true;
		}
		return false;
	}
	
	/**
	 * @param string $file File path
	 * @param string $mode File mode
	 * @return bool
	 */
	static public function readFile($file, $mode = 'r')
	{
		if (file_exists($file))
		{
			$fh = fopen($file, $mode);
			if ($fh)
			{
			    $content = fread($fh, filesize($file));
			    fclose($fh);
				return $content;
			}
			else 
			{
				throw new Exception('Файл "'.$file.'" не найден!');
			}
		}
	}
	
	/**
	 * @param string $path
	 */
	static public function deleteFolderWithFiles( $path )
	{
		self::deleteAllFilesInFolder($path);
		rmdir($path);
	}
		
	/**
	 * @param string $path
	 */
	static public function deleteAllFilesInFolder( $path )
	{
	    if( is_dir( $path ) )
	    {
	    	foreach( scandir( $path ) as $item )
	        {
	            if( !strcmp( $item, '.' ) || !strcmp( $item, '..' ) ) 
	            {
	            	continue;        
	            }
	            self::deleteAllFilesInFolder( $path . "/" . $item );
	        }    
	    }
	    else
	    {
	        unlink( $path );
	    }
	}
	
	/**
	 * @param string $size
	 * @param string $retstring
	 */
	static public function converFileSize ($size, $retstring = '%01.2f %s') 
	{
        $sizes = array('Бт', 'КБт', 'МБт', 'ГБт');
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) 
        {
                if ($size < 1024) 
                { 
                	break; 
                }
                if ($sizestring != $lastsizestring)
                {
                	$size /= 1024;
                }
        }
        if ($sizestring == $sizes[0])
        {
        	$retstring = '%01d %s';
        }
        return sprintf($retstring, $size, $sizestring);
	}
}