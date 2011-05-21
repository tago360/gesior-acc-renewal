<?PHP
class ConfigLUA // NOT SAFE FUNCTION, LUA CONFIG CAN BE EXECUTED AS PHP CODE
{
	private $config;

    public function __construct($path = false)
    {
		if($path)
			$this->loadFromFile($path);
    }

	public function loadFromFile($path)
	{
		$content = file_get_contents($path);
		$this->loadFromString($content);
	}

	public function loadFromString($string)
	{
		$lines = explode("\n", $string);
		if(count($lines) > 0)
			foreach($lines as $line)
			{
				$tmp_exp = explode('=', $line);
				if(count($tmp_exp) >= 2)
				{
					$key = trim($tmp_exp[0]);
					if(trim(substr($key, 0, 2)) != '--')
					{
						unset($tmp_exp[0]);
						$value = trim(implode('=', $tmp_exp));
						$this->config[ $key ] = eval("return $value;");
					}
				}
			}
	}

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
			throw new Exception('Key ' . $key . ' doesn\'t exist.');
	}

	public function isSetKey($key)
	{
		return isset($this->config[ $key ]);
	}
}

class ConfigPHP
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
		$content = file_get_contents($path);
		$this->loadedFromPath = $path;
		$lines = explode("\n", $content);
		unset($lines[0]);
		unset($lines[count($lines)]);
		$this->loadFromString(implode("\n", $lines));
	}

	public function loadFromString($string)
	{
		$this->config = eval($string . chr(0x0A) . 'return $_web_config;');
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
		if(is_array($a))
			foreach($a as $k => $v)
			{
				if(is_array($v))
					$s .= self::arrayToPhpString($v, $d . '["' . $k . '"]');
				else
					$s .= $d . '["' . $k . '"] = ' . self::parsePhpVariableToText($v) . ';' . chr(0x0A);
			}
		else
			$s .= $d . ' = ' . self::parsePhpVariableToText($a) . ';' . chr(0x0A);
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
			throw new Exception('Key ' . htmlspecialchars($key) . ' doesn\'t exist.');
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

}
?>