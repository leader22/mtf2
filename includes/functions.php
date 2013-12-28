<?php
date_default_timezone_set('Asia/Tokyo');

function isOldBrowser(){
	$ua = getenv('HTTP_USER_AGENT');
	if(preg_match('/MSIE [678]/', $ua)){
		return true;
	}else{
		return false;
	}
}

function getCurrentPageUri(){
	return getenv('REQUEST_URI');
}

function getTitle(){
	$_dir = explode('.', getCurrentPageUri());
	$dir = $GLOBALS['_dir'] = $_dir[0];
	$siteName = 'Music Tweets Finder';

	switch($dir){
	case '/show_result':
		echo('"' . escapeString($_GET['q']) . '"の検索結果 | ' . $siteName);
		break;
	case '/about':
		echo('このサービスについて | ' . $siteName);
		break;
	case '/faq':
		echo('よくある質問 | ' . $siteName);
		break;
	case '/howto':
		echo('つかいかた | ' . $siteName);	
		break;
	default :
		echo($siteName);
		break;
	}
}

function escapeString($str){
	if (is_array($str)) {
		$str = array_map("escStr", $str);
	} else {
		$str = htmlentities($str, ENT_QUOTES, 'UTF-8');
	}
	if (get_magic_quotes_gpc()) {
		return stripslashes( $str );
	}else{
		return $str;
	}
}

function getThumbnailAnchor($url){
	if(isset($url)){
		$imgUrl = '';
		if (preg_match('%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
		    $id = $match[1];
	    	$imgUrl = 'http://i.ytimg.com/vi/'.$id.'/1.jpg';
		}else{
			$qs = parse_url($url);
			if( ($qs['host'] == 'nico.ms') || ((substr($qs['host'],-12)) == 'nicovideo.jp')){
				if( (substr($qs['path'],1,2)) == 'sm'){
					$id = substr($qs['path'],3);
					$host = $id%4 + 1;
					$imgUrl = 'http://tn-skr'.$host.'.smilevideo.jp/smile?i='.$id;
				}else{
					$imgUrl = 'img/thumb/nico_others.png';
				}
			}else{
				$imgUrl = 'img/thumb/other_movies.png';
			}
		}
	}else{
		$imgUrl = 'img/thumb/other_movies.png';
	}
	return $imgUrl;
}

function getPostedTime($originalDate){
	return date("Y/m/d H:i:s", strtotime($originalDate));
}

function uniteArray($array1, $array2){
	$unitedArray = array();
	$array1Length = count($array1->statuses);
	$array2Length = count($array2->statuses);

	for($i=0; $i < $array1Length; $i++){
		$unitedArray[$i]['tweetType'] = 'movie';
		$unitedArray[$i]['thumbnail'] = getThumbnailAnchor($array1->statuses[$i]->entities->urls[0]->expanded_url);
		$unitedArray[$i]['url'] = $array1->statuses[$i]->entities->urls[0]->expanded_url;
		$unitedArray[$i]['posted'] = getPostedTime($array1->statuses[$i]->created_at);
		$unitedArray[$i]['screen_name'] = $array1->statuses[$i]->user->screen_name;
		$unitedArray[$i]['name'] = $array1->statuses[$i]->user->name;
		$unitedArray[$i]['id_str'] = $array1->statuses[$i]->id_str;
		$unitedArray[$i]['id'] = $array1->statuses[$i]->id;
		$unitedArray[$i]['text'] = $array1->statuses[$i]->text;
		$unitedArray[$i]['profile_image_url'] = $array1->statuses[$i]->user->profile_image_url;
	}

	for($i=0; $i < $array2Length; $i++){
		$unitedArray[$array1Length+$i]['tweetType'] = 'nowplaying';
		$unitedArray[$array1Length+$i]['thumbnail'] = 'img/thumb/nowplaying.png';
		$unitedArray[$array1Length+$i]['url'] = '';
		$unitedArray[$array1Length+$i]['posted'] = getPostedTime($array2->statuses[$i]->created_at);
		$unitedArray[$array1Length+$i]['screen_name'] = $array2->statuses[$i]->user->screen_name;
		$unitedArray[$array1Length+$i]['name'] = $array2->statuses[$i]->user->name;
		$unitedArray[$array1Length+$i]['id_str'] = $array2->statuses[$i]->id_str;
		$unitedArray[$array1Length+$i]['id'] = $array2->statuses[$i]->id;
		$unitedArray[$array1Length+$i]['text'] = $array2->statuses[$i]->text;
		$unitedArray[$array1Length+$i]['profile_image_url'] = $array2->statuses[$i]->user->profile_image_url;
	}

	$tempArrayForSort = array();
	foreach ($unitedArray as $key) {
		$tempArrayForSort[] = $key['id'];
	}
	array_multisort($tempArrayForSort, SORT_DESC, SORT_NUMERIC, $unitedArray);

	return $unitedArray;
}

function getTweetsList($_q, $_l){
	$q = escapeString($_q);
	$l = escapeString($_l);
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);

	$results1 = $connection->get('search/tweets', array(
		'q' => '-#nowplaying filter:links nico OR youtu AND "' . $q . '"',
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 10,
		'locale' => ($l) ? 'ja' : '',
		'lang' => ($l) ? 'ja' : ''
	));
	
	$results2 = $connection->get('search/tweets', array(
		'q' => '#twitmusic OR #なうぷれ OR #nowplaying OR soundtracking OR #lastfm AND "' . $q . '"',
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 10,
		'locale' => ($l) ? 'ja' : '',
		'lang' => ($l) ? 'ja' : ''
	));
	
	$unitedArray = uniteArray($results1, $results2);

	if(count($unitedArray)){
		return $unitedArray;
	}else{
		return false;
	}

}

function getMoreTweetsList($_q, $_l, $_max){
	$q = escapeString($_q);
	$l = escapeString($_l);
	$max = escapeString($_max);
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);

	$results1 = $connection->get('search/tweets', array(
		'q' => '-#nowplaying filter:links nico OR youtu AND "' . $q . '"',
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 10,
		'locale' => ($l) ? 'ja' : '',
		'lang' => ($l) ? 'ja' : '',
		'max_id' => $max
	));
	
	$results2 = $connection->get('search/tweets', array(
		'q' => '#twitmusic OR #なうぷれ OR #nowplaying OR soundtracking OR #lastfm AND "' . $q . '"',
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 10,
		'locale' => ($l) ? 'ja' : '',
		'lang' => ($l) ? 'ja' : '',
		'max_id' => $max
	));
	
	$unitedArray = uniteArray($results1, $results2);

	if(count($unitedArray)){
		echo(json_encode($unitedArray));
	}else{
		echo('');
	}

}

function getUserInfo($_u){
	$u = escapeString($_u);
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);

	$results1 = $connection->get('search/tweets', array(
		'q' => '-#nowplaying filter:links nico OR youtu from:' . $u,
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 20
	));
	
	$results2 = $connection->get('search/tweets', array(
		'q' => '#twitmusic OR #なうぷれ OR #nowplaying OR soundtracking OR #lastfm from:' . $u,
		'include_entities' => true,
		'result_type' => 'recent',
		'count' => 20
	));
	
	$unitedArray = uniteArray($results1, $results2);

	if(count($unitedArray)){
		echo(json_encode($unitedArray));
	}else{
		echo('');
	}
}

function isPC(){
	require_once('Mobile_Detect.php');
	$detect = new Mobile_Detect;
	$_SESSION['isPC'] = ($detect->isMobile() || $detect->isTablet()) ? false : true;
	return $_SESSION['isPC'];
}