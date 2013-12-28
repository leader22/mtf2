<?php
session_start();
require_once('functions.php');
require_once('twitteroauth/twitteroauth/twitteroauth.php');
require_once('twitteroauth/_config.php');

if($_GET['u']){
	getUserInfo($_GET['u']);
}elseif($_GET['q'] && $_GET['m']){
	getMoreTweetsList($_GET['q'], $_GET['l'], $_GET['m']);
}else{
	echo('');
}