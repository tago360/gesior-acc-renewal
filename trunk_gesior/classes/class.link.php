<?PHP
class Link extends Errors
{
	private $page = '';
	private $parameters = array();
	private $customParameters = array();
	private $useRewrite = $GLOBALS['WEB']->getKey('useRewrite');
	private $URL = $GLOBALS['WEB']->getPageURL();
    public function __construct($page = '', $parameters = array(), $custom_parameters = array())
    {
		if(isset($page))
			$this->page = $page;
		if(isset($parameters))
			$this->parameters = $parameters;
		if(isset($custom_parameters))
			$this->customParameters = $custom_parameters;
    }

    public function setPage(string $page)
    {
		$this->page = $page;
    }

    public function setParameters(Array $parameters)
    {
		$this->parameters = $parameters;
    }

    public function setCustomParameters(Array $custom_parameters)
    {
		$this->customParameters = $custom_parameters;
    }

    public function setParameter($number, $parameter = '')
    {
		$this->parameter[$number] = $parameter;
    }

    public function setCustomParameter($number, $custom_parameter = '')
    {
		$this->customParameter[$number] = $custom_parameter;
    }

    public function getPage()
    {
		return $this->page;
    }

    public function getParameters()
    {
		return $this->parameters;
    }

    public function getCustomParameters()
    {
		return $this->customParameters;
    }

    public function getParameter($number)
    {
		if(isset($this->parameter[$number]))
			return $this->parameter[$number];
		else
		{
			self::addError('#C-6', 'ERROR: Class::Link - getParameter(\$number) Param: <b>' . htmlspecialchars($number) . '</b>');
			return false;
		}
    }

    public function getCustomParameter($number)
    {
		if(isset($this->customParameter[$number]))
			return $this->customParameter[$number];
		else
		{
			self::addError('#C-7', 'ERROR: Class::Link - getCustomParameter(\$number) Param: <b>' . htmlspecialchars($number) . '</b>');
			return false;
		}
	}

    public function getString()
    {
		$ret = $this->URL;
		if($this->useRewrite)
		{
			$ret .= $this->page . '/';
		}
		else
		{
			$tmp_params = array();
			if($this->page != '')
				$tmp_params[] = 'page=' . urlencode($this->page);
			if(count($this->parameters) > 0)
				foreach($this->parameters as $key => $value)
					$tmp_params[] = 'amp' . urlencode($key) . '=' . urlencode($value);
			if(count($this->customParameters) > 0)
				foreach($this->customParameters as $key => $value)
					$tmp_params[] = urlencode($key) . '=' . urlencode($value);
			if(!empty($tmp_params))
				$ret .= '?' . implode('&', $tmp_params);
		}
		return $ret;
	}
}
?>

