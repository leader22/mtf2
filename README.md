Music Tweets Finder2
=====

Twitterで音楽趣味のあう人を探そう！という拙作のWebサービスです。  
今になってみると、あれこれヒドい実装になってますが、まあ誰もが通る道ということで・・。 

もし仮にcloneして試される方がいる場合、以下の手順を踏んでください。
- Twitterのアプリ登録をする
- OAuthのトークンとシークレットを取得する
- 以下のファイルを設置する

```php
<?php

/**
 * @file
 * A single location to store configuration.
 */

define('CONSUMER_KEY',    'YOUR-CONSUMER-KEY');
define('CONSUMER_SECRET', 'YOUR-CONSUMER-SECRET');
define('OAUTH_CALLBACK',  'http://example.com/includes/twitteroauth/callback.php');
```

配置するパスは、``includes/twitteroauth/_config.php``です。
