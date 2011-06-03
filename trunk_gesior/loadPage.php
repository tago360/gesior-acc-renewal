<?php
if(!defined('INITIALIZED'))
	die('PHP script: Access denied.');
$_TMP_PARAMS = array();
if($WEB->getValue('useRewrite'))
{
	$_tmp_params = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
	foreach($_tmp_params as $_pi => $_tmp_param)
	{
		if(isset($_tmp_param) && in_array($_tmp_param, $WEB->getValue('pagesEnabled')))
		{
			$page = $_tmp_param;
			unset($_tmp_params[$_pi]);
			break;
		}
		unset($_tmp_params[$_pi]);
	}
	if(count($_tmp_params) > 0)
		foreach($_tmp_params as $_tmp_param)
			$_TMP_PARAMS[] = $_tmp_param;
}
else
{
	if(isset($_REQUEST['page']) && in_array($_REQUEST['page'], $WEB->getValue('pagesEnabled')))
		$page = $_REQUEST['page'];
	if(count($_REQUEST) > 0)
		foreach($_REQUEST as $key => $_tmp_param)
			if(substr($key, 0, 3) == 'amp')
				$_TMP_PARAMS[(int) substr($key, 3, 2)] = $_tmp_param;
}

if(!isset($page))
	$page = $WEB->getValue('pageDefault');
$pathToPage = './pages/' . $page;

for($_pi = 0; $_pi <= 10; $_pi++)
	if(isset($_TMP_PARAMS[$_pi]) && $WEB->isValidFolderName($_TMP_PARAMS[$_pi]) && file_exists($pathToPage . '/' . $_TMP_PARAMS[$_pi]))
	{
		$pathToPage .= '/' . $_TMP_PARAMS[$_pi];
		unset($_TMP_PARAMS[$_pi]);
	}
	else
		break;
foreach($_TMP_PARAMS as $_tmp_param)
	$_PARAM[] = $_tmp_param;
	
define('TITLE', $configServer->getValue('serverName') . " - " . ucwords($page));
define('PATH_LAYOUT', './layouts/' . $WEB->getValue('layoutName') . '/');
define('PATH_PAGE', $pathToPage);
define('PAGE', $page);
include("./classes/class.table.php");
include(PATH_LAYOUT . "layout.functions.php");
$main_content = '';
include(PATH_PAGE . '/index.php');
?>