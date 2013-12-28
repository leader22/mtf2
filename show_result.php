<?php require_once('header.php'); ?>
<?php require_once('search.php'); ?>
<?php if($_SESSION['isPC'] || isPC()): ?>
<div class="contents inner">
<?php if($_SESSION['isVerifiedUser']): ?>
	<?php if($_GET['q'] && $results = getTweetsList($_GET['q'], $_GET['l'])): ?>
	<section class="left-box" id="left_box">
		<h2 class="title-box"><span class="wf">Search results of</span> "<mark><?php echo(escapeString($_GET['q'])); ?></mark>"</h2>

		<ul class="tweet-lists" id="tweet_lists">
		<?php foreach($results as $i): ?>
			<li class="tweet-list <?php echo($i['tweetType']); ?>" data-screen_name="<?php echo($i['screen_name']); ?>" data-id_str="<?php echo($i['id_str']); ?>">
				<article class="tweet-box cf">
					<div class="tweet-box-l">
						<div class="user-info-box cf">
							<a href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>" class="user-thumbnail-box"><img class="user-thumbnail" width="30" height="30" src="<?php echo($i['profile_image_url']); ?>"></a>
							<div class="user-info-r">
								<div class="user-name-box">
									<a target="_blank" href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>"><?php echo($i['screen_name']); ?></a> <?php echo($i['name']); ?>
								</div>
								<div class="user-posted-box">
									<a target="_blank" href="https://twitter.com/webgets/status/<?php echo($i['id_str']); ?>"><?php echo($i['posted']); ?></a>
								</div>
							</div>
						</div>
						<div class="user-tweet-box cf">
							<ul class="tweet-action-box">
								<li>
									<a target="_blank" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo($i['id_str']); ?>" class="hover-img tw-rep"></a>
								</li>
								<li>
									<a target="_blank" href="https://twitter.com/intent/retweet?tweet_id=<?php echo($i['id_str']); ?>" class="hover-img tw-rt"></a>
								</li>
								<li>
									<a target="_blank" href="https://twitter.com/intent/favorite?tweet_id=<?php echo($i['id_str']); ?>" class="hover-img tw-fav"></a>
								</li>
							</ul>
							<div class="user-text-box">
								<?php echo($i['text']); ?>
							</div>
						</div>
					</div>
					<div class="tweet-box-m">
						<?php if($i['url']) :?>
						<a href="<?php echo($i['url']); ?>" target="_blank"><img src="<?php echo($i['thumbnail']); ?>" width="140" height="104" /></a>
						<?php else: ?>
						<img src="<?php echo($i['thumbnail']); ?>" width="140" height="104" />

						<?php endif; ?>
					</div>
					<div class="tweet-box-r">&raquo;</div>
				</article>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php if(count($results) < 19): ?>
		<div class="more-btn err">これ以上古いツイートはありません。</div>
		<?php else: ?>
		<div id="more_btn" class="more-btn">さらに結果を読み込む</div>
		<?php endif; ?>
	</section>
	<section class="right-box">
		<div class="title-box user-box" id="user_box">
			<div class="user-info-frame cf">
				<div class="user-box-thumb" id="selected_user_thumbnail"><img width="48" height="48" src="<?php echo($results[0]['profile_image_url']); ?>"></div>
				<h2>"<mark id="selected_user_name" data-screen_name="<?php echo($results[0]['screen_name']); ?>"><?php echo($results[0]['screen_name']); ?></mark>"<span class="wf"> likes..</span></h2>
			</div>
			<div class="user-detail-frame-wrapper" id="user_detail_frame_wrapper">
				<div class="user-detail-frame" id="user_detail_frame">
					<ul id="user_detail_box" class="user-detail-box cf"></ul>
				</div>
			</div>
			<div id="user_detail_scroller" class="user-detail-scroller">▼</div>
		</div>
	</section>


	<?php else: ?>
	<section>
		<h2 class="title-box">見つかりませんでした。</h2>
		・最近その音楽についてツイートした人はいないみたいです。<br>
		・検索キーワードを変えてみてください。<br>
	</section>
	<?php endif; ?>
<?php else: ?>
	<section>
		<h2 class="title-box">ログインしていません。</h2>
		・本サービスを利用するためには、Twitterアカウントで<a href="includes/twitteroauth/redirect.php">ログイン</a>をする必要があります。<br>
		・画面右上のボタンか、<a href="includes/twitteroauth/redirect.php">コチラ</a>からログインしてください。<br>
	</section>
<?php endif; ?>

</div>


<script id="tweetListsTemplate" type="text/x-handlebars-template">
{{#each this}}
<li class="tweet-list {{tweetType}}" data-screen_name="{{screen_name}}" data-id_str="{{id_str}}">
	<article class="tweet-box cf">
		<div class="tweet-box-l">
			<div class="user-info-box cf">
				<a href="https://twitter.com/intent/user?screen_name={{screen_name}}" class="user-thumbnail-box"><img class="user-thumbnail" width="30" height="30" src="{{profile_image_url}}"></a>
				<div class="user-info-r">
					<div class="user-name-box">
						<a target="_blank" href="https://twitter.com/intent/user?screen_name={{screen_name}}">{{screen_name}}</a> {{name}}
					</div>
					<div class="user-posted-box">
						<a target="_blank" href="https://twitter.com/webgets/status/{{id_str}}">{{posted}}</a>
					</div>
				</div>
			</div>
			<div class="user-tweet-box cf">
				<ul class="tweet-action-box">
					<li>
						<a target="_blank" href="https://twitter.com/intent/tweet?in_reply_to={{id_str}}" class="hover-img tw-rep"></a>
					</li>
					<li>
						<a target="_blank" href="https://twitter.com/intent/retweet?tweet_id={{id_str}}" class="hover-img tw-rt"></a>
					</li>
					<li>
						<a target="_blank" href="https://twitter.com/intent/favorite?tweet_id={{id_str}}" class="hover-img tw-fav"></a>
					</li>
				</ul>
				<div class="user-text-box">
					{{text}}
				</div>
			</div>
		</div>
		<div class="tweet-box-m">
		{{#if url}}
			<a href="{{url}}" target="_blank"><img src="{{thumbnail}}" width="140" height="104" /></a>
		{{else}}
			<img src="{{thumbnail}}" width="140" height="104" />
		{{/if}}
		</div>
		<div class="tweet-box-r">&raquo;</div>
	</article>
</li>
{{/each}}
</script>
<script id="userInfoTemplate" type="text/x-handlebars-template">
{{#each this}}
<li class="user-detail-list" data-screen_name="{{screen_name}}" data-id_str="{{id_str}}">
	<article class="user-detail-tweet cf">
		<div class="user-detail-thumb  {{tweetType}}">
		{{#if url}}
			<a href="{{url}}" target="_blank"><img src="{{thumbnail}}" width="89" height="66" /></a>
		{{else}}
			<img src="{{thumbnail}}" width="89" height="66" />
		{{/if}}
		</div>
		<div class="user-detail-text">{{text}}</div>
		<div class="user-detail-info"><a target="_blank" href="https://twitter.com/webgets/status/{{id_str}}"><img src="/img/twitter/twbird.png" height="11" width="14" />{{posted}}</a></div>
	</article>
</li>
{{/each}}
</script>
<?php else: ?>
<div class="contents inner">
<?php if($_SESSION['isVerifiedUser']): ?>
	<?php if($_GET['q'] && $results = getTweetsList($_GET['q'], $_GET['l'])): ?>
	<ul class="tweet-lists" id="tweet_lists">
	<?php foreach($results as $i): ?>
		<li class="title-box tweet-list <?php echo($i['tweetType']); ?>" data-id_str="<?php echo($i['id_str']); ?>">
			<article class="tweet-box cf" data-screen_name="<?php echo($i['screen_name']); ?>">
				<header class="tweet-header box">
					<a href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>" class="user-thumbnail-box"><img class="user-thumbnail" width="40" height="40" src="<?php echo($i['profile_image_url']); ?>"></a>
					<div class="flex">
						@<a target="_blank" href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>"><?php echo($i['screen_name']); ?></a> <span class="user-name"><?php echo($i['name']); ?></span><br>
						<a target="_blank" href="https://twitter.com/webgets/status/<?php echo($i['id_str']); ?>"><?php echo($i['posted']); ?></a>
					</div>
				</header>
				<div class="user-text-box">
					<?php echo($i['text']); ?>
				</div>
				<div class="tweet-detail-btn wf tap-btn">More</div>
			</article>
		</li>
	<?php endforeach; ?>
	</ul>
	<?php if(count($results) < 19): ?>
	<div class="btn btn-s err">これ以上古いツイートはありません。</div>
	<?php else: ?>
	<div id="more_btn" class="btn btn-s">さらに結果を読み込む</div>
	<?php endif; ?>

	<?php else: ?>
	<section>
		<h2 class="title-box">見つかりませんでした。</h2>
		<ul class="page-list title-box">
		<li>最近その音楽についてツイートした人はいないみたいです。</li>
		<li>検索キーワードを変えてみてください。</li>
		</ul>
	</section>
	<?php endif; ?>
<?php else: ?>
	<section>
		<h2 class="title-box">ログインしていません。</h2>
		<ul class="page-list title-box">
			<li>本サービスを利用するためには、Twitterアカウントで<a href="includes/twitteroauth/redirect.php">ログイン</a>をする必要があります。</li>
		</ul>
		<a href="includes/twitteroauth/redirect.php" class="btn btn-l">ログイン</a>
	</section>
<?php endif; ?>
</div>
<script id="tweetListsTemplate" type="text/x-handlebars-template">
{{#each this}}
	<li class="title-box tweet-list {{tweetType}}" data-id_str="{{id_str}}">
		<article class="tweet-box cf" data-screen_name="{{screen_name}}">
			<header class="tweet-header box">
				<a href="https://twitter.com/intent/user?screen_name={{screen_name}}" class="user-thumbnail-box"><img class="user-thumbnail" width="40" height="40" src="{{profile_image_url}}"></a>
				<div class="flex">
					@<a target="_blank" href="https://twitter.com/intent/user?screen_name={{screen_name}}">{{screen_name}}</a> <span class="user-name">{{name}}</span><br>
					<a target="_blank" href="https://twitter.com/webgets/status/{{id_str}}">{{posted}}</a>
				</div>
			</header>
			<div class="user-text-box">
				{{text}}
			</div>
			<div class="tweet-detail-btn wf tap-btn">More</div>
		</article>
	</li>
{{/each}}
</script>
<?php endif; ?>
<?php require_once('footer.php'); ?>