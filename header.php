<?php
session_start();
require_once('includes/functions.php');
require_once('includes/twitteroauth/twitteroauth/twitteroauth.php');
require_once('includes/twitteroauth/_config.php');

if($_SESSION['isPC'] || isPC()):
?>
<!DOCTYPE HTML>
<html lang="ja-JP">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="google-site-verification" content="-7YiG_PsYezj78tOs6bd2BOxeaZpIGUnfqq-cQqO-Zo" />
<meta name="description" content="Twitterで、音楽をもっと。 Music Tweets Finderは、音楽に関するツイートの検索サイトです。" />
<meta name="keywords" content="Music 音楽 つぶやき 検索 #nowplaying Twitter" />
<meta property="og:title" content="Music Tweets Finder" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://mtf.lealog.net" />
<meta property="og:image" content="img/MscTwtsFndr.png" />
<meta property="og:site_name" content="Music Tweets Finder" />
<meta property="og:description" content="Twitterで、音楽をもっと。 Music Tweets Finderは、音楽に関するツイートの検索サイトです。" />
<meta property="fb:admins" content="100000270633761" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link rel="stylesheet" media="all" href="css/normalize.css" />
<link rel="stylesheet" media="all" href='//fonts.googleapis.com/css?family=Geo'>
<link rel="stylesheet" media="all" href="css/style.css" />
<!--[if IE]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<title><?php getTitle(); ?></title>
</head>
<body>
<div id="container">
<?php if(isOldBrowser()): ?>
<div style="text-align: center;padding: 10px 0;background-color: #b94047;color: #fff;font-size:1.5rem;">
	お使いのブラウザはサポート対象外のため、当サービスが一部動作しない可能性があります。
</div>
<?php endif; ?>
<noscript style="text-align: center;padding: 10px 0;background-color: #b94047;color: #fff;font-size:1.5rem;">
	当サービスは、JavaScriptをONにしてご利用ください。
</noscript>
<header class="header">
	<div class="inner cf">
		<section class="section header-box">
			<h1><a href="/" title="Music Tweets Finder" rel="home">Music Tweets Finder</a> 2.0</h1>
			<span class="wf">More tweets, more musics.</span>
		</section>
		<ul class="nav-box">
			<li class="txt-li"><a href="about.php">About</a></li><!--
		 --><li class="txt-li"><a href="faq.php">FAQ</a></li><!--
		 --><li class="img-li">
				<?php if($_SESSION['isVerifiedUser']): ?>
				<a class="logout-btn" id="logout_btn" href="includes/twitteroauth/logout.php"><img src="<?php echo($_SESSION['isVerifiedUser']['profileImageUrl'])?>" width="32" height="32" alt="<?php echo($_SESSION['isVerifiedUser']['name'])?>" style="margin-right: 8px;" /><?php echo($_SESSION['isVerifiedUser']['screenName'])?></a>
				<?php else: ?>
				<a href="includes/twitteroauth/redirect.php" id="login_btn"><img src="img/twitter/sign_in.png" width="158" height="28" alt="Sign in with Twitter" /></a>
				<?php endif; ?>
			</li>
		</ul>
	</div>
</header>
<?php else: ?>
<!DOCTYPE HTML>
<html lang="ja-JP">
<head>
<meta charset="utf-8" />
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="Twitterで、音楽をもっと。 Music Tweets Finderは、音楽に関するツイートの検索サイトです。" />
<meta name="keywords" content="Music 音楽 つぶやき 検索 #nowplaying Twitter" />
<meta property="og:title" content="Music Tweets Finder" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://mtf.lealog.net" />
<meta property="og:image" content="img/MscTwtsFndr.png" />
<meta property="og:site_name" content="Music Tweets Finder" />
<meta property="og:description" content="Twitterで、音楽をもっと。 Music Tweets Finderは、音楽に関するツイートの検索サイトです。" />
<meta property="fb:admins" content="100000270633761" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link rel="apple-touch-icon" href="img/MscTwtsFndr.png"/>
<link rel="stylesheet" media="all" href="css/normalize.css" />
<link rel="stylesheet" media="all" href='//fonts.googleapis.com/css?family=Geo'>
<link rel="stylesheet" media="all" href="css/style_sp.css" />
<title><?php getTitle(); ?></title>
</head>
<body>
<div id="container">
<header class="header inner">

	<div class="box">
		<section class="section header-box flex">
			<h1><a href="/" title="Music Tweets Finder" rel="home">Music Tweets Finder</a> 2.0</h1>
			<span class="wf">More tweets, more musics.</span>
		</section>
		<aside class="menu-box">
			<span class="menu-btn tap-btn" id="menu_btn">Menu</span>
		</aside>
	</div>
	<ul class="menu-list" id="menu_list">
		<li class="tap-btn"><a href="about.php">About</a></li><!--
	 --><li class="tap-btn"><a href="faq.php">FAQ</a></li><!--
	 --><li class="tap-btn">
			<?php if($_SESSION['isVerifiedUser']): ?>
			<a class="log-in-out-btn" href="includes/twitteroauth/logout.php">Log Out</a>
			<?php else: ?>
			<a class="log-in-out-btn" href="includes/twitteroauth/redirect.php">Log In</a>
			<?php endif; ?>
		</li>
	</ul>
</header>
<?php endif; ?>