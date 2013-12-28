<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('_config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */

  /* ログイン状態を判別するための変数を変更＋各種追加 */
  $_SESSION['isVerifiedUser']['isVerified'] = true;
  $_SESSION['isVerifiedUser']['screenName'] = $access_token['screen_name'];
  $_SESSION['isVerifiedUser']['userId'] = $access_token['user_id'];
  /* ログイン状態を表示するために、名前とプロフィール画像を取得 */
  $verifiedUserInfo = $connection->get('account/verify_credentials', array('skip_status' => '1'));
  $_SESSION['isVerifiedUser']['name'] = $verifiedUserInfo->name;
  $_SESSION['isVerifiedUser']['profileImageUrl'] = $verifiedUserInfo->profile_image_url;

  header('Location: ../../');
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: ./clearsessions.php');
}
