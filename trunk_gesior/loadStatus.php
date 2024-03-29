<?PHP
if(!defined('INITIALIZED'))
	die('PHP script: Access denied.');
$statusTimeout = max(1, $configServer->getValue('statusTimeout') / 1000);
$serverStatusCache = new ConfigPHP('./cache/serverstatus');
if($serverStatusCache->isSetKey('lastCheck') && $serverStatusCache->getValue('lastCheck') + $statusTimeout < time())
{
	$serverStatusCache->setValue('lastCheck', $statusTimeout);
	$sock = @fsockopen($configServer->getValue('ip'), $configServer->getValue('statusPort'), $errno, $errstr, 1);
	if($sock)
	{
		fwrite($sock, chr(6).chr(0).chr(255).chr(255).'info');
		$data = '';
		while(!feof($sock))
			$data .= fgets($sock, 1024);
		fclose($sock);
		$serverStatusCache->setValue('online', true);
		preg_match('/players online="(\d+)" max="(\d+)"/', $data, $matches);
		$serverStatusCache->setValue('players', $matches[1]);
		$serverStatusCache->setValue('playersMax', $matches[2]);
		preg_match('/uptime="(\d+)"/', $data, $matches);
		$serverStatusCache->setValue('uptimeString', floor($matches[1] / 3600) . 'h ' . floor(($matches[1] - $h*3600) / 60) . 'm');
		$serverStatusCache->setValue('uptimeH', floor($matches[1] / 3600));
		$serverStatusCache->setValue('uptimeM', floor(($matches[1] - $serverStatusCache->getValue('uptimeH') * 3600) / 60));
		$serverStatusCache->setValue('uptimeS', $matches[1] - $serverStatusCache->getValue('uptimeH') * 3600 - $serverStatusCache->getValue('uptimeM') * 60);
		preg_match('/monsters total="(\d+)"/', $data, $matches);
		$serverStatusCache->setValue('monsters', $matches[1]);
	}
	else
	{
		$serverStatusCache->setValue('online', false);
		$serverStatusCache->setValue('players', 0);
		$serverStatusCache->setValue('playersMax', 0);
	}
	$serverStatusCache->saveToFile();
}
?>

