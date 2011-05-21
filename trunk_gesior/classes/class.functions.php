<?PHP
// of course here could be just functions, but if we make class, it will be more readable in other scripts
class Functions
{
	public function isPremium($premdays, $lastday)
	{
		return ($premdays - (date("z", time()) + (365 * (date("Y", time()) - date("Y", $lastday))) - date("z", $lastday)) > 0);
	}

	public function checkAccountName($name)
	{
		if(strspn($name, "QWERTYUIOPASDFGHJKLZXCVBNM0123456789") != strlen($name))
			return false;
		if(strlen($name) > 32)
			return false;
		else
			return true;
	}

	public function checkCharName($name)	
	{
		$name_to_check = strtolower($name);
		$names_blocked = array('gm','cm', 'god', 'tutor');
		$first_words_blocked = array('gm ','cm ', 'god ','tutor ', "'", '-');
		$words_blocked = array('gamemaster', 'game master', 'game-master', "game'master", '--', "''","' ", " '", '- ', ' -', "-'", "'-", 'fuck', 'sux', 'suck', 'noob', 'tutor');
		foreach($first_words_blocked as $word)
			if($word == substr($name_to_check, 0, strlen($word)))
			return false;
		if(substr($name_to_check, -1) == "'" || substr($name_to_check, -1) == "-")
			return false;
		if(substr($name_to_check, 1, 1) == ' ')
			return false;
		if(substr($name_to_check, -2, 1) == " ")
			return false;
		foreach($names_blocked as $word)
			if($word == $name_to_check)
				return false;/*
		foreach($GLOBALS['config']['site']['monsters'] as $word)
			if($word == $name_to_check)
				return false;
		foreach($GLOBALS['config']['site']['npc'] as $word)
			if($word == $name_to_check)
				return false;*/
		for($i = 0; $i < strlen($name_to_check); $i++)
			if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
				return false;
		foreach($words_blocked as $word)
			if(!(strpos($name_to_check, $word) === false))
				return false;
		for($i = 0; $i < strlen($name_to_check); $i++)
			if($name_to_check[$i] == $name_to_check[($i+1)] && $name_to_check[$i] == $name_to_check[($i+2)])
				return false;
		for($i = 0; $i < strlen($name_to_check); $i++)
			if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
				return false;
		$temp = strspn($name, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- '");
		if ($temp != strlen($name))
			return false;
		else
			return (preg_match("/[a-zA-Z ']{1,25}/", $name))? true: false;
	}
	
	public function checkGuildRankName($name)
	{
		if(strspn($name, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-[ ] ") != strlen($name))
			return false;
		else
			return (preg_match("/[a-zA-Z ]{1,60}/", $name))? true: false;
	}

	public function checkGuildName($name)
	{
		if(strspn($name, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789- ") != strlen($name))
			return false;
		else
			return (preg_match("/[a-zA-Z ]{1,60}/", $name))? true: false;
	}

	public function getBanReasonName($reasonId)
	{
		switch($reasonId)
		{
			case 0:
				return "Offensive Name";
			case 1:
				return "Invalid Name Format";
			case 2:
				return "Unsuitable Name";
			case 3:
				return "Name Inciting Rule Violation";
			case 4:
				return "Offensive Statement";
			case 5:
				return "Spamming";
			case 6:
				return "Illegal Advertising";
			case 7:
				return "Off-Topic Public Statement";
			case 8:
				return "Non-English Public Statement";
			case 9:
				return "Inciting Rule Violation";
			case 10:
				return "Bug Abuse";
			case 11:
				return "Game Weakness Abuse";
			case 12:
				return "Using Unofficial Software to Play";
			case 13:
				return "Hacking";
			case 14:
				return "Multi-Clienting";
			case 15:
				return "Account Trading or Sharing";
			case 16:
				return "Threatening Gamemaster";
			case 17:
				return "Pretending to Have Influence on Rule Enforcement";
			case 18:
				return "False Report to Gamemaster";
			case 19:
				return "Destructive Behaviour";
			case 20:
				return "Excessive Unjustified Player Killing";
			case 21:
				return "Invalid Payment";
			case 22:
				return "Spoiling Auction";
			default:
				return "Unknown Reason";
		}
	}

	public function limitTextLenght($text, $lenght_limit)
	{
		if(strlen($text) > $lenght_limit)
			return substr($text, 0, strrpos(substr($text, 0, $lenght_limit), " ")).'...';
		else
			return $text;
	}

	public function isFileImage($path)
	{
		$txt = file_get_contents($path);
		if(preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt))
			return false;
		if(preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt))
			return false;
		if(preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt))
			return false;
		if(preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt))
			return false;
		if(preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt))
			return false;
		if(preg_match("#</*(applet|body|head|html|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt))
			return false;
		return true;
	}

	public function getOrderSQL($arr, $order, $default)
	{
		$type = 'asc';
		if(isset($_GET['order']))
		{
			$v = explode('_', strrev($_GET['order']), 2);
			if(count($v) == 2)
				if($orderBy = $arr[strrev($v[1])])
					$default = $orderBy;
			$type = (strrev($v[0]) == 'asc' ? 'desc' : 'asc');
		}
		return 'ORDER BY ' . $default . ' ' . $type;
	}

	public function getOrderParameter($arr, $order, $this)
	{
		$type = 'asc';
		if($orderBy = $arr[$this])
			if(isset($_GET[$order]))
			{
				$v = explode('_', strrev($_GET[$order]), 2);
				if(strrev($v[1]) == $this)
					$type = (strrev($v[0]) == 'asc' ? 'desc' : 'asc');
			}
		return $this . '_' . $type;
	}

	public function getPageViews()
	{
		return file_get_contents("./cache/pageviews.counter");
	}

	public function setPageViews($value)
	{
		file_put_contents("./cache/pageviews.counter", $value);
	}

	public function addPageView()
	{
		setPageViews(self::getPageViews()+1);
	}

/*

// Parse smiley bbcode into HTML images
function parsesmileys($message)
{
	foreach(array(
		"/\:\)/si" => "<img src='images/smiley/smile.gif' title='Smile'>",
		"/\;\)/si" => "<img src='images/smiley/wink.gif' title='Wink'>",
		"/\:\(/si" => "<img src='images/smiley/sad.gif' title='Sad'>",
		"/\:\|/si" => "<img src='images/smiley/frown.gif' title='Frown'>",
		"/\:o/si" => "<img src='images/smiley/shock.gif' title='Shock'>",
		"/\:p/si" => "<img src='images/smiley/pfft.gif' title='Pfft!'>",
		"/b\)/si" => "<img src='images/smiley/cool.gif' title='Cool...'>",
		"/\:d/si" => "<img src='images/smiley/grin.gif' title='Grin'>",
		"/\:@/si" => "<img src='images/smiley/angry.gif' title='Angry'>",
		"/\:rol:/si" => "<img title='Rolleyes...' src='images/smiley/roll.gif'>",
		"/\:uhoh:/si" => "<img title='Uh-Oh!' src='images/smiley/uhoh.gif'>",
		"/\:no:/si" => "<img title='Nope' src='images/smiley/no.gif'>",
		"/\:shy:/si" => "<img title='Shy' src='images/smiley/shy.gif'>",
		"/\:lol:/si" => "<img title='Laugh' src='images/smiley/laugh.gif'>",
		"/\:rip:/si" => "<img title='Dead...' src='images/smiley/dead.gif'>",
		"/\:yes:/si" => "<img title='Yeah' src='images/smiley/yes.gif'>",
		"/\:mad:/si" => "<img title='Mad' src='images/smiley/mad.gif'>",
		"/\:bigeek:/si" => "<img title='Big eek!' src='images/smiley/bigeek.gif'>",
		"/\:bigrazz:/si" => "<img title='Big razz' src='images/smiley/bigrazz.gif'>",
		"/\:smilewinkgrin:/si" => "<img title='Smile-Wink-Grin' src='images/smiley/smilewinkgrin.gif'>",
		"/\:sourgrapes:/si" => "<img title='Sour Grapes' src='images/smiley/sourgrapes.gif'>",
		"/\:confused:/si" => "<img title='Confused?' src='images/smiley/confused.gif'>",
		"/\:upset:/si" => "<img title='Upset' src='images/smiley/upset.gif'>",
		"/\:sleep:/si" => "<img title='Sleep' src='images/smiley/sleep.gif'>",
		"/\:yupi:/si" => "<img title='Yupi!' src='images/smiley/jupi.gif'>"
	) as $key => $img)
		$message = preg_replace($key, $img, $message);

	return $message;
}

// Parse bbcode into HTML code
function parseubb($text)
{
	global $account_logged;
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);

	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[s\](.*?)\[/s\]#si', '<s>\1</s>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $text);

	$text = preg_replace('#\[url\]([\r\n\s]*)(http://|ftp://|https://|ftps://)([^\s\'\"\+\(\)]*?)([\r\n\s]*)\[/url\]#sie', "'<a href=\''.str_replace('<br>', '', '\\2\\3').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2\\3').'\'>\\2\\3</a>'", $text);
	$text = preg_replace('#\[url\] ([\r\n]*)([^\s\'\"\+\(\)]*?)([\r\n]*) \[/url\]#sie', "'<a href=\'http://'.str_replace('<br>', '', '\\2').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2').'\'>\\2</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"\+\(\)]*?)\](.*?)([\r\n]*)\[/url\]#sie', "'<a href=\''.str_replace('<br>', '', '\\2\\3').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2\\3').'\'>\\4</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\"\+\(\)]*?)\](.*?)([\r\n]*)\[/url\]#sie', "'<a href=\'http://'.str_replace('<br>', '', '\\2').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2').'\'>\\3</a>'", $text);

	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);

	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<small>\1</small>', $text);
	$text = preg_replace('#\[color=(black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);

	if($account_logged)
		$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '\1', $text);

	$text = preg_replace('#\[size=(8|10|12|14|16|18|20)\](.*?)\[/size\]#si', '<span style=\'font-size: \1;\'>\2</span>', $text);
	$text = preg_replace('#\[marquee\](.*?)\[/marquee\]#si', '<marquee>\1</marquee>', $text);
	$text = preg_replace('#\[marquee=(left|down|up|right)\](.*?)\[/marquee\]#si', '<marquee direction=\'\1\'>\2</marquee>', $text);
	$text = preg_replace('#\[marquee=(left|down|up|right):(scroll|slide|alternate)\](.*?)\[/marquee\]#si', '<marquee direction=\'\1\' behavior=\'\2\'>\3</marquee>', $text);

	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";:\+]*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);
	$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<img src=\'\\1'.str_replace(array('.php','?','&','='),'','\\3').'\\4\' style=\'border:0px\' alt=\'\'>'",$text);

	$qcount = substr_count($text, "[quote]");
	for($i=0;$i<$qcount;$i++)
		$text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class=\'quote\'>\1</div>', $text);

	$ccount = substr_count($text, "[code]");
	for($i=0;$i<$ccount;$i++)
		$text = preg_replace('#\[code\](.*?)\[/code\]#si', '<div class=\'quote\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>\1<br><br><br></code></div>', $text);

	return descript($text, false);
}

function descript($text, $striptags = true)
{
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);

	$entities = count($search);
	for($i=0;$i<$entities;$i++)
		$text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);

	// kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
        //<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if(!$striptags)
		return $text;

	do
	{
		$tmp = $text;
		$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
	} while($tmp != $text);
	return $text;
}
*/
}
?>
