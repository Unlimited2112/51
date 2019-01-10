<?php

final class Loader
{
	/**
	 * @param string $path
	 * @return bool
	 */
	public static function loadCustom($path, $name)
	{	
		return self::__tryLoad($path, $name);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function loadFunction($name)
	{	
		return self::__loadByName(FUNCTIONS, $name);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public static function loadCore($name)
	{	
		return self::__loadByName(CORE, $name);
	}
	
	/**
	 * @param string $name
	 * @param string $type
	 * @return bool
	 */
	public static function loadBlock($name, $type = '')
	{	
		return self::__loadByNameAndType(BLOCKS, $name, $type, array('Admin', 'AdminBase', 'Front', 'FrontBase', 'Form', 'Form/FormGenerator', 'Form/Input', 'Helper'));
	}
	
	/**
	 * @param string $name
	 * @param string $type
	 * @return bool
	 */
	public static function loadLib($name, $type = '')
	{	
		return self::__loadByNameAndType(LIBS, $name, $type, array('Files', 'Formaters', 'Other', 'Reformers', 'Vendors'));
	}
	
	/**
	 * @param string $name
	 * @param string $type
	 * @return bool
	 */
	public static function loadORM($name, $type = '')
	{	
		return self::__loadByNameAndType(ORM, $name, $type, array('Base', 'CMS', 'Models', 'Interfaces', 'DB', 'CMS/Handlers', 'Behaviors'));
	}
	
	/**
	 * @param string $name
	 * @param string $type
	 * @return bool
	 */
	public static function loadPage($name, $type = '')
	{	
		return self::__loadByNameAndType(PAGES, $name, $type, array('Admin', 'Front'));
	}
	
	/**
	 * @param string $basePath
	 * @param string $name
	 * @param string $type
	 * @param array $types
	 * @return bool
	 */
	protected static function __loadByNameAndType($basePath, $name, $type, $types)
	{
		$return = false;
		
		if (!empty($type))
		{
			if(self::__tryLoad($basePath . $type . '/' . $name . '/', $name) or self::__tryLoad($basePath . $type . '/', $name))
			{
				$return = true;
			}
		}
		else 
		{
			if(!self::__tryLoad($basePath, $name) and !self::__tryLoad($basePath . $name . '/', $name))
			{
				foreach($types as $type)
				{
					if(self::__tryLoad($basePath . $type . '/' . $name . '/', $name) or self::__tryLoad($basePath . $type . '/', $name))
					{
						$return = true;
					}
				}  
			}
			else 
			{
				$return = true;
			}
		}
		
		return $return;
	}
	
	/**
	 * @param string $basePath
	 * @param string $name
	 * @return bool
	 */
	protected static function __loadByName($basePath, $name)
	{
		$return = false;
		
		if(self::__tryLoad($basePath, $name) or self::__tryLoad($basePath . $name . '/', $name))
		{
			$return = true;
		}
		
		return $return;
	}
	
	/**
	 * @param string $file
	 * @return bool
	 */
	protected static function __tryLoad($path, $name)
	{
		if (file_exists($path . $name . '.php'))
		{
			require_once ($path . $name . '.php');
			
			return true;
		}
		return false;
	}
}