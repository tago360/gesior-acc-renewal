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
include_once('./loadStatus.php');
include_once('./loadPage.php');

/*
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
*/
include($layoutPath . "layout.php");
ob_end_flush();
?>
