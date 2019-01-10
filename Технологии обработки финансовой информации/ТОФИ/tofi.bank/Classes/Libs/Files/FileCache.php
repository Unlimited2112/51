<?php
class FileCache 
{
	/**	 
	 * @param string $dataId
	 * @param mixed $data
	 * @param string $cacheId
	 * @param string $folder
	 * @return bool
	 */
	static public function setCache($dataId, $data, $cacheId = 'System', $folder = null)
	{
		if (!Config::$enableFileCache) return true;
		
		if ($folder == null)
		{
			$folder = CACHED_DATA;
		}
		
		$folder .= $cacheId . '/';
		
		if(FileSystem::installFolder($folder))
		{
			if(FileSystem::writeFile($folder.$dataId.'.txt', serialize($data)))
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @param string $dataId
	 * @param mixed $defaultData
	 * @param string $cacheId
	 * @param string $folder
	 * @return mixed $data
	 */
	static public function getCache($dataId, $defaultData, $cacheId = 'System', $folder = null)
	{
		if ($folder == null)
		{
			$folder = CACHED_DATA;
		}
		
		$folder .= $cacheId . '/';
		try 
		{
			$data = FileSystem::readFile($folder.$dataId.'.txt');
		}
		catch (Exception $e)
		{
			$data = serialize($defaultData);
		}
		
		return unserialize($data);
	}
	
	/**
	 * @param string $dataId
	 * @param string $cacheId
	 * @param string $folder
	 * @return bool $data
	 */
	static public function isCached($dataId, $cacheId = 'System', $folder = null)
	{
		if ($folder == null)
		{
			$folder = CACHED_DATA;
		}
		
		$folder .= $cacheId . '/';
		
		return file_exists($folder.$dataId.'.txt');
	}
	
	/**
	 * @param string $dataId
	 * @param string $cacheId
	 * @param string $folder
	 * @return bool
	 */
	static public function deleteCache($dataId, $cacheId = 'System', $folder = null)
	{
		if ($folder == null)
		{
			$folder = CACHED_DATA;
		}
		
		$folder .= $cacheId . '/';
		
		if(is_file($folder.$dataId.'.txt'))
		{
			return unlink($folder.$dataId.'.txt');
		}
		else 
		{
			return true;
		}
	}
	
	/**
	 * @param string $cacheId
	 * @param string $folder
	 * @return bool
	 */
	static public function deleteCacheGroup($cacheId = 'System', $folder = null)
	{
		if ($folder == null)
		{
			$folder = CACHED_DATA;
		}
		
		$folder .= $cacheId;
		
		if(is_dir($folder))
		{
			return FileSystem::deleteAllFilesInFolder($folder);
		}
		else 
		{
			return true;
		}
	}
}