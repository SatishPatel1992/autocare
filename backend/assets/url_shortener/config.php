<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

// db options
define('DB_NAME', 'autocare121');
define('DB_USER', 'satishpatel1992');
define('DB_PASSWORD', 'S@t!sh@123');
define('DB_HOST', 'localhost');
define('DB_TABLE', 'shortenedurls');

$config['hostname'] = DB_HOST;
$config['username'] = DB_USER;
$config['password'] = DB_PASSWORD;
$config['database'] = DB_NAME;
$config['dbdriver'] = 'mysqli';

// $CI =& get_instance();
// $CI->load->database($config);
// connect to database
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
// mysql_select_db();

// base location of script (include trailing slash)
define('BASE_HREF', 'http://' . $_SERVER['HTTP_HOST'].'/');

// change to limit short url creation to a single IP
define('LIMIT_TO_IP', $_SERVER['REMOTE_ADDR']);

// change to TRUE to start tracking referrals
define('TRACK', FALSE);

// check if URL exists first
define('CHECK_URL', FALSE);

// change the shortened URL allowed characters
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

// do you want to cache?
define('CACHE', TRUE);

// if so, where will the cache files be stored? (include trailing slash)
define('CACHE_DIR', dirname(__FILE__) . '/cache/');
