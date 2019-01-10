<?php
class Debugger 
{
	private static $DebugString = array();
	
	/**
	 * Добавляет строку в лог запуска
	 * 
	 * @param string $information
	 * @param integer $debugLevel
	 */
	static public function addString($information, $debugLevel = 255) 
	{
		if ($debugLevel <= Config::$debugLevel)
		{
			self::$DebugString[] = (getFormattedMicrotime()-$GLOBALS['CoreStartTime']).' '.$information;
		}
	}
	
	/**
	 * Выводит лог запуска
	 */
	static public function outputLog() 
	{
		if (!Config::$debugLevel) 
		{
			return;
		}
		
		$CoreEndTime = getFormattedMicrotime();
		
		echo '
			<script type="text/javascript" src="'.Config::$rootPath.'js/cms/3dparty/debugger.js"></script>
			<div class="debugger-container" style="padding: 30px 8px 8px 8px; margin:0px; font: 10px/1.4 Verdana; color:#999;">
				<div style="padding:0px; border-top:1px solid #999">
					<br />
						<strong>DEBUGGER:</strong> 
						<img src="'.Config::$rootPath.'images/cms/hide.gif" alt="" id="debug_hide_butt_Debugger" title="" class="hand" onclick="HideDebugger(\'Debugger\');" />	
						<img src="'.Config::$rootPath.'images/cms/show.gif" alt="" id="debug_show_butt_Debugger" title="" class="hand" onclick="ShowDebugger(\'Debugger\');" style="display: none;" />
				</div>
	            <div id="debug_Debugger" style="padding:0px;">
		';
		
		self::dump($CoreEndTime-$GLOBALS['CoreStartTime'], 'RENDERING');
		self::dump(self::$DebugString, 'PROCCESSES');
		self::dump($_POST, 'POST');
		self::dump($_GET, 'GET');
		self::dump($_SESSION, 'SESSION');
		self::dump($_COOKIE, 'COOKIE');
		self::dump($_FILES, 'FILES');
		self::dump($_SERVER, 'SERVER');
		self::dump(Core::getInstance()->tplVars, 'TEMPLATES');
		
		echo '
					</div>
				<script type="text/javascript">DebuggerCheck(\'Debugger\');</script>
			</div>';
	}
	
	/**
	 * Выводит подробную информацию по переданным данным
	 *
	 * @param mixed $var
	 * @param string $name
	 * @param integer $indent
	 * @return string
	 */
	static public function getVarInfo($var, $name = '', $indent = 0) 
	{
		static $methods = array ();
		$indent > 0 or $methods = array ();
	
		$indent_chars = '  ';
		$spc = $indent > 0 ? str_repeat ($indent_chars, $indent ) : '';
	
		$out = '';
		if (is_array ($var)) 
		{
			$out .= "<span>Array</span> " . count ($var) . " (\n";
			foreach (array_keys ($var) as $key) 
			{
				$out .= "$spc  [<span style='color:#FF9933;'>$key</span>] => ";
				if (($indent == 0) && ($name != '') && (! is_int ($key)) && ($name == $key)) 
				{
					$out .= "LOOP\n";
				} 
				else 
				{
					$out .= self::getVarInfo ($var[$key], $name, $indent + 1);
				}
			}
			$out .= "$spc)";
		} 
		elseif (is_object ($var)) 
		{
			$class = get_class ($var);
			$out .= "<span style='color:;'>Object</span> $class";
			$parent = get_parent_class ($var);
			$out .= $parent != '' ? " <span style='color:;'>extends</span> $parent" : '';
			$out .= " (\n";
			$arr = get_object_vars ($var);
			while (list($prop, $val) = each($arr)) 
			{
				$out .= "$spc  " . "-><span style='color:;'>$prop</span> = ";
				$out .= self::getVarInfo ($val, $name != '' ? $prop : '', $indent + 1);
			}
			$arr = get_class_methods ($var);
			$out .= "$spc  " . "$class methods: " . count ($arr) . " ";
			if (in_array ($class, $methods)) 
			{
				$out .= "[already listed]\n";
			}
			else
			{
				$out .= "(\n";
				$methods[] = $class;
				while (list($prop, $val) = each($arr)) 
				{
					if ($val != $class) 
					{
						$out .= $indent_chars . "$spc  " . "-><span style='color:#000;'>$val</span>();\n";
					} 
					else 
					{
						$out .= $indent_chars . "$spc  " . "-><span style='color:#FF9933;'>$val</span>(); [constructor]\n";
					}
				}
				$out .= "$spc  " . ")\n";
			}
			$out .= "$spc)";
		} 
		elseif (is_resource ($var)) 
		{
			$out .= "<span style='color:;'>Resource</span> [" . get_resource_type($var) . "] ( <span style='color:#0069CE;'>" . $var . "</span> )";
		} 
		elseif (is_int ($var)) 
		{
			$out .= "<span style='color:;'>Integer</span> ( <span style='color:#0069CE;'>" . $var . "</span> )";
		} 
		elseif (is_float ($var)) 
		{
			$out .= "<span style='color:;'>Float</span> ( <span style='color:#0069CE;'>" . $var . "</span> )";
		} 
		elseif (is_numeric ($var))
		{
			$out .= "<span style='color:;'>Numeric string</span> " . strlen($var) . " ( \"<span style='color:#0069CE;'>" . $var . "</span>\" )";
		}
		elseif (is_string ($var))
		{
			if ($name == 'SQL Debug') 
			{
				$out .= '<span style="color:;">String</span> ' . strlen($var) . ' ( "<span style="color:#000;">' . $var . '</span>" )';
			}
			else
			{
				$out .= '<span style="color:;">String</span> ' . strlen($var) . ' ( "<span style="color:#000;">' . htmlspecialchars(nl2br($var)) . '</span>" )';
			}
		}
		elseif (is_bool ($var))
		{
			$out .= "<span style='color:;'>Boolean</span> ( <span style='color:#0065CE;'>" . ($var ? 'True' : 'False') . "</span> )";
		}
		elseif (! isset ($var))
		{
			$out .= "<b>Null</b>";
		}
		else
		{
			$out .= "<b>Other</b> ( " . $var . " )";
		}
	
		return $out . "\n";
	}

	/**
	 * Выводит подробную информацию по переданным данным и завершает работу приложения
	 *
	 * @param mixed $var
	 * @param string $name
	 */
	static public function edump($var, $name = '') 
	{
		self::dump($var, $name);
		exit();
	}
	
	/**
	 * Выводит оформленную подробную информацию по переданным данным
	 *
	 * @param mixed $var
	 * @param string $name
	 */
	static public function dump($var, $name = '') 
	{
		echo ($name != '' ? '
	            <br clear="all" />
		    	<div style="padding: 0px;">
		    		<b>'.$name.'</b> : 
		    		<img src="'.Config::$rootPath.'images/cms/hide.gif" alt="" id="debug_hide_butt_'.$name.'" title="" class="hand" onclick="HideDebugger(\''.$name.'\');" />	
		    		<img src="'.Config::$rootPath.'images/cms/show.gif" alt="" id="debug_show_butt_'.$name.'" title="" class="hand" onclick="ShowDebugger(\''.$name.'\');" style="display: none;" />
		    	</div>
	            <div id="debug_'.$name.'" style="padding:0px;">
		' : '') .
		"<pre style=\"padding: 15px 8px 8px 8px; font: 10px/1.4 Verdana; color:#999;\">" .
			self::getVarInfo ($var, $name) .
		"</pre>";
		if($name != "") echo '</div>
			<script type="text/javascript">DebuggerCheck(\''.$name.'\');</script>
		';
	}
}