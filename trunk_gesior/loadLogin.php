<?PHP
$logged = false;
if(isset($_SESSION['account']))
{
	$account_logged = new OTS_Account();
	$account_logged->load($_SESSION['account']);
	if($account_logged->isLoaded() && $account_logged->getPassword() == $_SESSION['password'])
		$logged = true;
	else
	{
		unset($_SESSION['account']);
		unset($account_logged);
	}
}
if(!$logged && !empty($_POST['account_login']) && !empty($_POST['password_login']))
{
	$_login_account = strtoupper(trim($_POST['account_login']));
	$account_logged = new OTS_Account();
	$account_logged->find($_login_account);
	$_login_password = $OTS->encryptPassword(trim($_POST['password_login']));
	if($account_logged->isLoaded() && $account_logged->getPassword() == $_login_password)
	{
		$_SESSION['account'] = $account_logged->getId();
		$_SESSION['password'] = $_login_password;
		$logged = true;
		$account_logged->setCustomField("page_lastday", time());
	}
}
?>