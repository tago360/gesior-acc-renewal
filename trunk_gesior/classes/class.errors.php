<?PHP
class Errors
{
	const TYPE_BOTH = 0; // parameter for some functions to return 'errors and notices'
	const TYPE_NOTICE = 1; // show information for website user, for example 'this name contains illegal letters' [when create account]
	const TYPE_ERROR = 2; // show important information about bug that administrator must fix, for example 'file ./logs/paypal_transactions.log does not exist'
	const TYPE_CRITIC = 3; // show error information and block script execution

	private $errors = array();
	private $notices = array();

	public function addNotice($id = null, $text = null)
	{
		$this->notices[] = new Error($id, $text, Errors::TYPE_NOTICE);
	}

	public function addError($id = null, $text = null)
	{
		$this->errors[] = new Error($id, $text, Errors::TYPE_ERROR);
	}

	public function addCriticError($id = null, $text = null)
	{
		throw new Error_Critic($id, $text);
	}

	public function getErrors($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH) // to fix
			return $this->errors; // to fix
		elseif($type == Errors::TYPE_NOTICE)
			return $this->notices;
		elseif($type == Errors::TYPE_ERROR)
			return $this->errors;
		else
			return array();
	}

    public function getErrorsCount($type = Errors::TYPE_BOTH)
    {
		if($type == Errors::TYPE_BOTH)
			return count($this->errors);
		elseif($type == Errors::TYPE_NOTICE)
			return count($this->notices) + count($this->errors);
		elseif($type == Errors::TYPE_ERROR)
			return count($this->errors);
		else
			return 0;
    }
}

class Error
{
	private $errorId = 'unknown';
	private $errorText = 'no text';
	private $errorType = Errors::TYPE_ERROR;

	public function __construct($id = null, $text = null, $type = null)
	{
		if(isset($id))
			$this->errorId = $id;
		if(isset($text))
			$this->errorText = $text;
		if(isset($type))
			$this->errorType = $type;
	}

	public function getErrorId()
	{
		return $this->errorId;
	}

	public function getErrorText()
	{
		return $this->errorText;
	}

	public function getErrorType()
	{
		return $this->errorType;
	}
}

class Error_Critic
{
    public function __construct($id = '', $text = '', $errors = array())
    {
		echo '<h3>Error occured!</h3>';
		echo 'Error ID: <b>' . $id . '</b><br />';
		echo 'More info: <b>' . $text . '</b><br />';
		if(count($errors) > 0)
		{
			echo 'Errors list:<br />';
			foreach($errors as $error)
				echo '<li>' . $error . '</li>';
		}
		exit;
    }
}
?>