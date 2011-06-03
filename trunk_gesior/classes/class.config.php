<?PHP
class ConfigLUA extends Errors // NOT SAFE CLASS, LUA CONFIG CAN BE EXECUTED AS PHP CODE
{
	private $config;

    public function __construct($path = false)
    {
		if($path)
			$this->loadFromFile($path);
    }

	public function loadFromFile($path)
	{
		if(file_exists($path))
		{
			$content = file_get_contents($path);
			$this->loadFromString($content);
		}
		else
			self::addError('#C-2', 'ERROR: <b>#C-2</b> : Class::ConfigLUA - LUA config file doesn\'t exist. Path: <b>' . $path . '</b>');
	}

	public function loadFromString($string)
	{
		$lines = explode("\n", $string);
		if(count($lines) > 0)
			foreach($lines as $ln => $line)
			{
				$tmp_exp = explode('=', $line);
				if(count($tmp_exp) >= 2)
				{
					$key = trim($tmp_exp[0]);
					if(substr($key, 0, 2) != '--')
					{
						unset($tmp_exp[0]);
						$value = trim(implode('=', $tmp_exp)); // in MOTD/serverName can be =
						if(is_numeric($value))
							$this->config[ $key ] = (float) $value;
						elseif(in_array(substr($value, 0 , 1), array("'", '"')) && in_array(substr($value, -1 , 1), array("'", '"')))
							$this->config[ $key ] = (string) substr(substr($value, 1), 0, -1);
						elseif(in_array($value, array('true', 'false')))
							$this->config[ $key ] = ($value == 'true') ? true : false;
						else
						{
							foreach($this->config as $tmp_key => $tmp_value)
								$value = str_replace($tmp_key, $tmp_value, $value);
							$ret = @eval("return $value;");
							if((string) $ret == '') // = parser error
								self::addError('#C-1', 'ERROR: <b>#C-1</b> : Class::ConfigLUA - Line <b>' . ($ln + 1) . '</b> of LUA config file is not valid [key: <b>' . $key . '</b>]');
							$this->config[ $key ] = $ret;
						}
					}
				}
			}
	}

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
			self::addError('#C-3', 'ERROR: <b>#C-3</b> : Class::ConfigLUA - Key <b>' . $key . '</b> doesn\'t exist.');
	}

	public function isSetKey($key)
	{
		return isset($this->config[ $key ]);
	}

	public function getConfig()
	{
		return $this->config;
	}
}

class ConfigPHP extends Errors
{
	private $config;
	private $loadedFromPath;

    public function __construct($path = false)
    {
		if($path)
			$this->loadFromFile($path);
    }

	public function loadFromFile($path)
	{
		if(file_exists($path))
		{
			
			$content = file_get_contents($path);
			$this->loadedFromPath = $path;
			$lines = explode("\n", $content);
			unset($lines[0]);
			unset($lines[count($lines)]);
			$this->loadFromString(implode("\n", $lines));
		}
		else
			self::addError('#C-4', 'ERROR: <b>#C-4</b> : Class::ConfigPHP - PHP config file doesn\'t exist. Path: <b>' . $path . '</b>');
	}

	public function loadFromString($string)
	{
		
		eval('$_web_config = array();' . chr(0x0A) . $string . chr(0x0A) . '');
		$this->config = $_web_config;
		unset($_web_config);
	}

	private function parsePhpVariableToText($value)
	{
		if(is_bool($value))
			return ($value) ? 'true' : 'false';
		elseif(is_numeric($value))
			return $value;
		else
			return '"' . str_replace('"', '\"' , $value) . '"';
	}

	public function arrayToPhpString($a, $d)
	{
		$s = '';
		if(is_array($a) && count($a) > 0)
			foreach($a as $k => $v)
			{
				if(is_array($v))
					$s .= self::arrayToPhpString($v, $d . '["' . $k . '"]');
				else
					$s .= $d . '["' . $k . '"] = ' . self::parsePhpVariableToText($v) . ';' . chr(0x0A);
			}
		//else
			//$s .= $d . ' = ' . self::parsePhpVariableToText($a) . ';' . chr(0x0A);
		return $s;
	}

	public function getConfigAsString()
	{
		return self::arrayToPhpString($this->config, '$_web_config');
	}

	public function saveToFile($path = false)
	{
		if($path)
			$savePath = $path;
		else
			$savePath = $this->loadedFromPath;
		file_put_contents($savePath, '<?PHP' . chr(0x0A) . $this->getConfigAsString() . '?>');
	}

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
			self::addError('#C-5', 'ERROR: <b>#C-5</b> : Class::ConfigPHP - Key <b>' . $key . '</b> doesn\'t exist.');
	}

	public function setValue($key, $value)
	{
		$this->config[ $key ] = $value;
	}

	public function removeKey($key)
	{
		if(isset($this->config[ $key ]))
			unset($this->config[ $key ]);
	}

	public function isSetKey($key)
	{
		return isset($this->config[ $key ]);
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function setConfig($value)
	{
		$this->config = $value;
	}
}
?>