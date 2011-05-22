<?php
$_P_PARAMS = array();
if($configSite->getValue('useRewrite'))
{
	$_tmp_params = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
	foreach($_tmp_params as $_pi => $_tmp_param)
	{
		if(isset($_tmp_param) && in_array($_tmp_param, $configSite->getValue('pagesEnabled')))
		{
			$page = $_tmp_param;
			unset($_tmp_params[$_pi]);
			break;
		}
		unset($_tmp_params[$_pi]);
	}
	if(count($_tmp_params) > 0)
		foreach($_tmp_params as $_tmp_param)
			$_P_PARAMS[] = $_tmp_param;
}
else
{
	if(isset($_REQUEST['page']) && in_array($_REQUEST['page'], $configSite->getValue('pagesEnabled')))
		$page = $_REQUEST['page'];
	else
		$page = $configSite->getValue('pageDefault');
	if(count($_REQUEST) > 0)
		foreach($_REQUEST as $key => $_tmp_param)
			if(substr($key, 0, 3) == 'amp')
				$_P_PARAMS[(int) substr($key, 3, 2)] = $_tmp_param;
}
$title = $configServer->getValue('serverName') . " - " . ucwords($page);
$layoutPath = './layouts/' . $configSite->getValue('layoutName') . '/';
include($layoutPath . "layout.functions.php");

if(!isset($page))
	$page = $configSite->getValue('pageDefault');
$pathToPage = './pages/' . $page;

for($_pi = 0; $_pi <= 10; $_pi++)
	if(isset($_P_PARAMS[$_pi]) && file_exists($pathToPage . '/' . $_P_PARAMS[$_pi]))
	{
		$pathToPage .= '/' . $_P_PARAMS[$_pi];
		unset($_P_PARAMS[$_pi]);
	}
	else
		break;
foreach($_P_PARAMS as $_tmp_param)
	$_PARAM[] = $_tmp_param;
	print_r($_PARAM);
include($pathToPage . '/index.php');
?>