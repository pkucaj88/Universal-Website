<?php
$date = date('Y-m-d H:i:s');
$timestamp = time();
$ip_client = getUserIP();
$pattern = "/[^A-Za-z0-9_\.\-\+\!\@\#\$]/";
$pattern_space = "/[^A-Za-z0-9_\.\-\+\!\@\#\$\040]/";
$pattern_num = "/[^0-9]/";
$pattern_email = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i";
$pattern_code = "/[^A-Fa-f0-9]/";


function getUserIP()
{
	$alt_ip = $_SERVER['REMOTE_ADDR'];

	if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$alt_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

	else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] AS $ip) {
			if (!preg_match('/#^(10|172\.16|192\.168)\./', $ip)) {
				$alt_ip = $ip;
				break;
			}
		}
	}

	else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$alt_ip = $_SERVER['HTTP_CLIENT_IP'];
	}

	else if (isset($_SERVER['HTTP_FROM'])) {
		$alt_ip = $_SERVER['HTTP_FROM'];
	}

	return $alt_ip;
}

function domain_exists($Email, $record = 'MX')
{
   list($user, $domain) = explode('@', $Email);
   return checkdnsrr($domain, $record);
}

function sec_session_start()
{
	$session_name = "sec_session_id";
	$lifetime = 60*60*24*2;
	$httponly = true;    // This stops JavaScript being able to access the session id.

	// Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: index.php");
        exit();
    }

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($lifetime,
        $cookieParams["path"],
        $cookieParams["domain"],
		$cookieParams["secure"],
        $httponly);

    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}


function between($number,$a,$b)
{
    if ($number>=$a AND $number<$b) return '1';
    else return '0';
}


function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}

function banIP($ip_client, array $banned_ip_range)
{
	foreach($banned_ip_range as $range) {
		$range = str_replace('*','(.*)', $range);
		if(preg_match('/'.$range.'/', $ip_client))	return true;
	}
	return false;
}

?>
