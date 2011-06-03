<?PHP
if(!defined('INITIALIZED'))
	die('PHP script: Access denied.');
include_once('./loadConfig.php');
if($configServer->isSetKey('mysqlHost'))
{
	define('SERVERCONFIG_SQL_TYPE', 'sqlType');
	define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
	define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
	define('SERVERCONFIG_SQL_USER', 'mysqlUser');
	define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
	define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
	define('SERVERCONFIG_SQLITE_FILE', 'sqlFile');
}
elseif($configServer->isSetKey('sqlHost'))
{
	define('SERVERCONFIG_SQL_TYPE', 'sqlType');
	define('SERVERCONFIG_SQL_HOST', 'sqlHost');
	define('SERVERCONFIG_SQL_PORT', 'sqlPort');
	define('SERVERCONFIG_SQL_USER', 'sqlUser');
	define('SERVERCONFIG_SQL_PASS', 'sqlPass');
	define('SERVERCONFIG_SQL_DATABASE', 'sqlDatabase');
	define('SERVERCONFIG_SQLITE_FILE', 'sqlFile');
}
else
	new CriticError('#E-3', 'loadDatabase.php - There is no key <b>sqlHost</b> or <b>mysqlHost</b> in server config', array('INFO: use server config cache: <b>' . ($WEB->getValue('useServerConfigCache') ? 'true' : 'false') . '</b>'));
include_once('./classes/pot/OTS.php');
$OTS = POT::getInstance();
if($configServer->getValue(SERVERCONFIG_SQL_TYPE) == 'mysql')
{
	//connect to MySQL database
	try
	{
		$OTS->connect(POT::DB_MYSQL, array(
		'host' => $configServer->getValue(SERVERCONFIG_SQL_HOST),
		'user' => $configServer->getValue(SERVERCONFIG_SQL_USER),
		'password' => $configServer->getValue(SERVERCONFIG_SQL_PASS),
		'database' => $configServer->getValue(SERVERCONFIG_SQL_DATABASE)) );
	}
	catch(PDOException $error)
	{
		new CriticError('#E-4', 'Database error - can\'t connect to MySQL database. Possible reasons:<br />1. MySQL server is not running on host.<br />2. MySQL user, password, database or host isn\'t configured in: <b>' . $WEB->getValue('serverPath') . 'config.lua</b><br />3. MySQL user, password, database or host is wrong.');
	}
}
elseif($configServer->getValue(SERVERCONFIG_SQL_TYPE) == 'sqlite')
{
	//connect to SQLite database
	$_sqlitePath = $WEB->getValue('serverPath') . $configServer->getValue(SERVERCONFIG_SQLITE_FILE);
	try
	{
		$OTS->connect(POT::DB_SQLITE, array('database' => $_sqlitePath));
	}
	catch(PDOException $error)
	{
		new CriticError('#E-5', 'Database error - can\'t open SQLite database. Possible reasons:<br /><b>' . $_sqlitePath . '</b> - file isn\'t valid SQLite database.<br /><b>' . $_sqlitePath . '</b> - doesn\'t exist.<br /><font color="red">Wrong PHP configuration. Default PHP does not work with SQLite databases!</font>');
	}
}
else
	new CriticError('#E-6', 'Database error. Unknown database type in <b>' . $WEB->getValue('serverPath') . 'config.lua</b> . Must be equal to: "<b>mysql</b>" or "<b>sqlite</b>". Now is: "<b>' . $configServer->getValue(SERVERCONFIG_SQL_TYPE) . '</b>"');
$OTS->setPasswordsEncryption($configServer->getValue('encryptionType'));
$SQL = POT::getInstance()->getDBHandle();
?>

