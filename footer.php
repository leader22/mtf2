<?php
$pageUrl = 'http://mtf.lealog.net'.getCurrentPageUri();
if($_SESSION['isPC'] || isPC()):
?>
<footer class="footer wf">
	<div class="inner">
		Would you share me...? -> 
        <a href="http://b.hatena.ne.jp/entry/<?php echo $pageUrl; ?>" class="hatena-bookmark-button" data-hatena-bookmark-layout="standard" title="はてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="はてなブックマークに追加" width="20" height="20" style="border: none;" /></a>
		<iframe class="facebook-button" src="http://www.facebook.com/plugins/like.php?app_id=227705577242997&amp;href=<?php echo $pageUrl; ?>&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:20px;" allowTransparency="true"></iframe>
        <a href="https://twitter.com/share" data-counturl="<?php echo $pageUrl; ?>" data-url="<?php echo $pageUrl; ?>" class="twitter-share-button" data-count="horizontal"  data-lang="ja">Tweet</a>
	</div>

	<hr class="style-hr" />

	<div class="inner">
		This site is powerd by <a href="https://dev.twitter.com/" target="_blank" title="Twitter Developers">Twitter Search API ver 1.1</a><br>
		&copy; <?php echo(date(Y)); ?> @<a href="https://twitter.com/leader22" title="@leader22">leader22</a> All Rights Reserved.
	</div>
</footer>

</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.6/prefixfree.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//platform.twitter.com/widgets.js"></script>
<script src="//b.st-hatena.com/js/bookmark_button_wo_al.js" charset="utf-8" async="async"></script>
<script src="/js/g_ana.js" async="async"></script>
<?php if($GLOBALS['_dir'] === '/show_result'): ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0.beta6/handlebars.min.js"></script>
<script src="/js/mtf.js"></script>
<?php endif; ?>
<script>
;(function(){
	var logOutBtn = $('#logout_btn'),
	loginNameNode = logOutBtn.html(),
	logInBtn = $('#login_btn'),
	loadImg = new Image();
	loadImg.src = 'img/loading_mini.gif';

	logOutBtn.on({
		'mouseenter':function(e){
			logOutBtn.text('Click to Logout');
		},
		'mouseleave':function(e){
			logOutBtn.html(loginNameNode);
		}
	}).on('click', function(e){
		e.target.src = loadImg.src;
		e.target.height = e.target.width = 15;
		e.target.style.verticalAlign = '-2px';
	});
}());
</script>
</body>
</html>

<?php else: ?>

<footer class="footer wf">
	This site is powerd by <a href="https://dev.twitter.com/" target="_blank" title="Twitter Developers">Twitter Search API ver 1.1</a><br>
	&copy; <?php echo(date(Y)); ?> @<a href="https://twitter.com/leader22" title="@leader22">leader22</a> All Rights Reserved.
</footer>
</div>
<?php if($GLOBALS['_dir'] === '/show_result'): ?>
<div id="modal">
	<div class="tweet-box cf inner">
		<header class="tweet-header box title-box">
			<a href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>" class="user-thumbnail-box" id="selected_user_thumbnail" target="_blank"><img class="user-thumbnail" width="40" height="40" src="<?php echo($i['profile_image_url']); ?>"></a>
			<div class="flex">
				<span id="selected_user_name"><a target="_blank" href="https://twitter.com/intent/user?screen_name=<?php echo($i['screen_name']); ?>"><?php echo($i['screen_name']); ?></a></span><br>
				<span id="selected_user"><?php echo($i['name']); ?></span>
			</div>
		</header>
		<div class="tweet-detail-btn tap-btn" id="close_detail_btn">x</div>
		<div class="title-box">
			<ul class="tweet-lists" id="tweet_detail_box"></ul>
		</div>
		<div id="back_btn" class="btn btn-s tap-btn">検索結果に戻る</div>
	</div>
</div>
<script id="userDetailTemplate" type="text/x-handlebars-template">
{{#each this}}
<li>
	<article class="detail-box cf {{tweetType}}">
		<header class="detail-header">
			{{#if id_str}}
			<a target="_blank" href="https://twitter.com/webgets/status/{{id_str}}">{{posted}}</a>
			{{else}}
			{{posted}}
			{{/if}}
		</header>
		<div class="box">
			{{#if url}}
			<a href="{{url}}" target="_blank"><img src="{{thumbnail}}" width="70" height="52" /></a>
			{{else}}
			<img src="{{thumbnail}}" width="70" height="52" />
			{{/if}}
			<div class="user-text-box flex">
				{{text}}
			</div>
		</div>
	</article>
</li>
{{/each}}
</script>
<?php endif; ?>
<script src="//cdn.jqmobi.com/1.11/jq.mobi.min.js"></script>
<?php if($GLOBALS['_dir'] === '/show_result'): ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0.beta6/handlebars.min.js"></script>
<script src="/js/mtf_sp.js"></script>
<?php endif; ?>
<script>
;(function(win){
	var menuBtn = $('#menu_btn'),
	menuList = $('#menu_list'),
	tapDetect = function(scope){
		var i = 0, l = arguments.length;
		for(;i<l;i++){
			arguments[i].bind('touchstart', function() {
				$(this).addClass('tapping');
			}).bind('click', function() {
				$(this).removeClass('tapping');
			});
		}
	}

	menuBtn.on('click', function(){
		menuList.toggle();
	});
	tapDetect($('.tap-btn'));

}(this));
</script>
<script src="/js/g_ana.js" async="async"></script>
</body>
</html>
<?php endif; ?>