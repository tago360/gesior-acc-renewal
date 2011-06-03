<?php
if($logged)
	include('pages/account/view/index.php');
else
	include('pages/account/login/index.php');
?>