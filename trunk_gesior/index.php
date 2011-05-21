<?PHP
$timeStart = microtime(true);
session_start();
ob_start("ob_gzhandler");
include_once('./loadConfig.php');
if($configSite->isSetKey('useProtectionScript') && $configSite->getValue('useProtectionScript'))
	require('./pageDefender.php');
include_once('./errorHandler.php');
include_once('./loadDatabase.php');
include_once('./loadLogin.php');

$_PARAMS = array();
if($configSite->getValue('useRewrite'))
{
	$_tmp_params = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
	if(isset($_tmp_params[0]) && in_array($_tmp_params[0], $configSite->getValue('pagesEnabled')))
		$page = $_tmp_params[0];
	else
		$page = $configSite->getValue('pageDefault');
	unset($_tmp_params[0]);
	if(count($_tmp_params) > 0)
		foreach($_tmp_params as $_tmp_param)
			$_PARAMS[] = $_tmp_param;
}
else
{
	if(count($_REQUEST) > 0)
		foreach($_REQUEST as $key => $_tmp_param)
			if(substr($key, 0, 3) == 'amp')
				$_PARAMS[(int) substr($key, 3, 2)] = $_tmp_param;
}


if(empty($topic))
	$title = $configServer->getValue('serverName') . " - OTS";
else
	$title = $$configServer->getValue('serverName') . " - " . $topic;

//#####LAYOUT#####
$layout_header = '<script type=\'text/javascript\'>
function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}

function MouseOverBigButton(source)
{
  source.firstChild.style.visibility = "visible";
}
function MouseOutBigButton(source)
{
  source.firstChild.style.visibility = "hidden";
}
function BigButtonAction(path)
{
  window.location = path;
}
var';
if($logged) { $layout_header .= "loginStatus=1; loginStatus='true';"; } else { $layout_header .= "loginStatus=0; loginStatus='false';"; };
$layout_header .= " var activeSubmenuItem='".$subtopic."';</script>";
include($layout_name."/layout.php");
ob_end_flush();
?>
