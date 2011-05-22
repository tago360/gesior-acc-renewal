<?PHP
include_once('./classes/class.config.php');
include_once('./classes/class.functions.php');
$configSite = new ConfigPHP('config/config.php');
if(!$configSite->isSetKey('installWebsite') || $configSite->getValue('installWebsite'))
{
	//header("Location: ?page=install");
	exit;
}
$configServer = new ConfigLUA($configSite->getValue('serverPath') . 'config.lua');
?>

