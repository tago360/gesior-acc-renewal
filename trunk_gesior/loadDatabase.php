<?PHP
include_once('./loadConfig.php');
if($configServer->isSetKey('mysqlHost'))
{
	const SERVERCONFIG_SQL_TYPE = 'sqlType';
	const SERVERCONFIG_SQL_HOST = 'mysqlHost';
	const SERVERCONFIG_SQL_PORT = 'mysqlPort';
	const SERVERCONFIG_SQL_USER = 'mysqlUser';
	const SERVERCONFIG_SQL_PASS = 'mysqlPass';
	const SERVERCONFIG_SQL_DATABASE = 'mysqlDatabase';
	const SERVERCONFIG_SQLITE_FILE = 'sqlFile';
}
elseif($configServer->isSetKey('sqlHost'))
{
	const SERVERCONFIG_SQL_TYPE = 'sqlType';
	const SERVERCONFIG_SQL_HOST = 'sqlHost';
	const SERVERCONFIG_SQL_PORT = 'sqlPort';
	const SERVERCONFIG_SQL_USER = 'sqlUser';
	const SERVERCONFIG_SQL_PASS = 'sqlPass';
	const SERVERCONFIG_SQL_DATABASE = 'sqlDatabase';
	const SERVERCONFIG_SQLITE_FILE = 'sqlFile';
}

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
		echo 'Database error - can\'t connect to MySQL database. Possible reasons:<br />1. MySQL server is not running on host.<br />2. MySQL user, password, database or host isn\'t configured in: <b>' . $configSite->getValue('serverPath') . 'config.lua</b><br />3. MySQL user, password, database or host is wrong.';
		exit;
	}
}
elseif($configServer->getValue(SERVERCONFIG_SQL_TYPE) == 'sqlite')
{
	//connect to SQLite database
	$_sqlitePath = $configSite->getValue('serverPath') . $configServer->getValue(SERVERCONFIG_SQLITE_FILE);
	try
	{
		$ots->connect(POT::DB_SQLITE, array('database' => _sqlitePath));
	}
	catch(PDOException $error)
	{
		echo 'Database error - can\'t open SQLite database. Possible reasons:<br /><b>' . _sqlitePath . '</b> - file isn\'t valid SQLite database.<br /><b>' . _sqlitePath . '</b> - doesn\'t exist.<br /><font color="red">Wrong PHP configuration. Default PHP does not work with SQLite databases!</font>';
		exit;
	}
}
else
{
	echo 'Database error. Unknown database type in <b>' . $configSite->getValue('serverPath') . 'config.lua</b> . Must be equal to: "<b>mysql</b>" or "<b>sqlite</b>". Now is: "<b>' . $configServer->getValue(SERVERCONFIG_SQL_TYPE) . '</b>"';
	exit;
}
$OTS->setPasswordsEncryption($configServer->getValue('encryptionType'));
$SQL = POT::getInstance()->getDBHandle();
?>

