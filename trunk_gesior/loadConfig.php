<?PHP
if(!defined('INITIALIZED'))
	die('PHP script: Access denied.');
include_once('./classes/class.config.php');
include_once('./classes/class.functions.php');
class Website extends Functions
{
	private pageURL = 'http://127.0.0.1/'
    public function __construct($path = false)
    {
        parent::__construct($path);
    }

	public function getPageURL()
	{
		return $this->pageURL;
	}

	public function setPageURL($url)
	{
		$this->pageURL = $url;
	}
}
$WEB = new Website('./config/config.php');
if($WEB->getErrorsCount() > 0)
	new CriticError('#E-1', 'loadConfig.php - errors while loading website config', $WEB->getErrors());
if(!$WEB->isSetKey('installWebsite') || $WEB->getValue('installWebsite'))
{
	header("Location: ?page=install");
	exit;
}
if($WEB->getValue('useServerConfigCache'))
	$configServer = new ConfigPHP('./config/config.server.php');
else
	$configServer = new ConfigLUA($WEB->getValue('serverPath') . 'config.lua');
if($configServer->getErrorsCount() > 0)
	new CriticError('#E-2', 'loadConfig.php - errors while loading server config', $configServer->getErrors());
?>