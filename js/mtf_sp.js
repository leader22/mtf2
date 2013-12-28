function log(a){console.log(a)}

(function(win){

// Var statement block. ///////////////////////////////////////////////////////////////////////////////
	var win = win,
	doc = win.document,
	$win = $(win),
	$doc = $(doc),
	tweetLists = $('#tweet_lists'),
	rawTweetListsTemplate = $('#tweetListsTemplate').html(),
	tweetListsTemplate = Handlebars.compile(rawTweetListsTemplate),
	moreBtn = $('#more_btn'),
	container = $('#container'),
	modal = $('#modal'),
	winWidth = win.innerWidth || doc.body.clientWidth,
	closeDetailBtn = $('#close_detail_btn'),
	scrollPosY = 0,
	selectedUserName = $('#selected_user_name'),
	selectedUserThumbnail = $('#selected_user_thumbnail'),
	selectedUser = $('#selected_user'),
	tweetDetailBox = $('#tweet_detail_box'),
	rawUserDetailTemplate = $('#userDetailTemplate').html(),
	userDetailTemplate = Handlebars.compile(rawUserDetailTemplate),
	backBtn = $('#back_btn').hide(),
	containerHeight = 0,
	modalHeight = 0,
	isHeightControll = 0;


// Function statement block. ///////////////////////////////////////////////////////////////////////////////

	var parseUrlParam = function(){
		var vars = [], hash;
		var hashes = win.location.href.slice(win.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	};

	var minus1 = function(strNumber) {
		var reg = /(([1-9])0+)$|([1-9])$/,
		ex = reg.exec(strNumber);
		
		if(ex && RegExp.$2){
			var regFirstDigit = parseInt(RegExp.$2, 10) - 1;
			if(regFirstDigit === 0){regFirstDigit = ''};
			var i = 0, l = RegExp.$1.length - 1;
			var regOtherDigit = '';
			for (; i < l; i++) {
				regOtherDigit += '9';
			}
			return RegExp.leftContext + regFirstDigit + regOtherDigit;
		}else if(ex) {
			var regFirstDigit = parseInt(ex, 10) - 1;
			
			return RegExp.leftContext + regFirstDigit;
		} else {
			return strNumber;
		}
	};

	var getMoreTweets = function() {
		var btnMessage = moreBtn.html();
		setLoadingImg({type: 'tweet', target: moreBtn});
		moreBtn.off('click', getMoreTweets);

		$.ajax({
			type: "GET",
			url: "includes/ajaxApiLoader.php",
			timeout: 3000,
			dataType: "json",
			cache: true,
			data: {
				q: parseUrlParam()['q'],
				l: parseUrlParam()['l'],
				m: minus1(tweetLists.children('li:last-child').data('id_str'))
			},
			success: function(d) {
				if(d){
					tweetLists.append(tweetListsTemplate(d));
					tapDetect(tweetLists);
				}else{
					moreBtn.addClass('err').html('これ以上古いツイートはありません。');
					return;
				}
				
				moreBtn.on('click', getMoreTweets);
				moreBtn.html(btnMessage);
			}
		});
	};

	var setLoadingImg = function(option){
		if(option.type === 'tweet'){
			option.target.html('<img src="/img/loading_mini.gif" height="16" widht="16" alt="読み込み中" style="vertical-align: -2px;"/>');
		}else{
			option.target.css('background', '#fff url(/img/loading.gif) no-repeat center center');
		}
	};

	var makeLink = function(str) {
		return str.replace(/[A-Za-z]+?:\/\/[A-Za-z0-9_-]+\.[A-Za-z0-9:%&\?\/.=~_-]+/g, function(m) {
			return ['<a href="', m, '" target="_blank">', m, '</a>'].join('');
		});
	};
	var make_user_link = function(str) {
		return str.replace(/@(\w+)/g, function(m, user) {
			return ['<a href="', 'https://twitter.com/', user, '" target="_blank">@', user, '</a>'].join('');
		});
	};
	var convert_link = function(str) {
		return parseHashtag(make_user_link(makeLink(str)));
	};
	
	var parseHashtag = function(str) {
		return str.replace(/[#|＃]+[A-Za-z0-9-_ぁ-ヶ亜-黑]+/g, function(t) {
			var tag = t.replace("#", "%23");
			return t.link("https://search.twitter.com/search?q=" + tag);
		});
	};

	var makeConvertLinks = function(targets){
		var convertedText = '';
		
		targets.each(function(){
			var $this = $(this),
			rawText = $this.text(),
			convertedText = convert_link(rawText);
			$this.html(convertedText);
		});
	}


	var getUserInfo = function(newScreenName){
		tweetDetailBox.empty();
		$.ajax({
			type: "GET",
			url: "includes/ajaxApiLoader.php",
			timeout: 3000,
			dataType: "json",
			cache: true,
			data: {
				u:newScreenName
			},
			success: function(d) {
				if(d){
					tweetDetailBox.append(userDetailTemplate(d));
					makeConvertLinks(tweetDetailBox.find('.user-text-box'));
				}else{
					tweetDetailBox.append(userDetailTemplate([{
						"posted": "通信エラー X(",
						"thumbnail": "img/thumb/error.png",
						"tweetType": "error",
						"text": "データを取得することができませんでした。このユーザーは音楽に関するツイートをしていないのかもしれません。"
					}]));
				}
				tweetDetailBox.css('background','#fff');
				containerHeight = container.height();
				modalHeight = modal.height();
				isHeightControll = 0;
				if(modalHeight < containerHeight){
					container.css({
						height: modalHeight + 'px',
						overflow: 'hidden'
					});
					isHeightControll = 1;
				}
				closeDetailBtn.on('click', closeModal);
				backBtn.on('click', closeModal).show();
			}
		});
	};

	var refreshUserDetail = function(selected){
		var $this = $(selected).parent(),
		newScreenName, newThumbnail, newUserName,
		oldScreenName = selectedUserName.data('screen_name');
		newScreenName = $this.data('screen_name');

		if(newScreenName != oldScreenName){
			setLoadingImg({type: '', target: tweetDetailBox});

			newUserName = $this.find('.user-name').text();	
			newThumbnail = $this.find('.user-thumbnail').clone();
			selectedUserThumbnail.empty().attr('href', 'https://twitter.com/intent/user?screen_name=' + newScreenName).append(newThumbnail);
	
			selectedUserName.html('<a target="_blank" href="https://twitter.com/intent/user?screen_name=' + newScreenName + '">' + newScreenName + '</a>');
			selectedUserName.data('screen_name', newScreenName);
			selectedUser.text(newUserName);

			getUserInfo(newScreenName);
		}
	};


	var openModal = function(){
		scrollPosY = win.scrollY;

		refreshUserDetail(this);

		setTimeout(function(){
			modal.addClass('show');
			win.scrollTo(0,0);
		}, 450);
		container.css('webkitTransform', 'translate3d(' + ~winWidth + 'px,0,0)');
	};
	
	var closeModal = function(){
		if(isHeightControll){
			container.css({
				height: '',
				overflow: ''
			});
		}

		modal.css('webkitTransform', 'translate3d(' + winWidth + 'px,0,0)');
		modal.removeClass('show');
		container.css('webkitTransform', 'translate3d(0px,0,0)');

		setTimeout(function(){
			win.scrollTo(0, scrollPosY);
			modal.css('webkitTransform', 'translate3d(0px,0,0)');
		}, 450);
		closeDetailBtn.off('click', closeModal);
		backBtn.off('click', closeModal).hide();

	};
	

	var tapDetect = function(scope){
		var i = 0, l = arguments.length;
		for(;i<l;i++){
			arguments[i].find('.tap-btn').bind('touchstart', function() {
				$(this).addClass('tapping');
			}).bind('click', function() {
				$(this).removeClass('tapping');
			});
		}
	}

// Execute, handle event block. ///////////////////////////////////////////////////////////////////////////////

	moreBtn.on('click', getMoreTweets);
	tweetLists.delegate('.tweet-detail-btn', 'click', openModal);
	$win.bind('orientationchange resize', function(){
		winWidth = win.innerWidth || doc.body.clientWidth;
		if(modal.hasClass('show')){
			container.css('webkitTransform', 'translate3d(' + ~winWidth + 'px,0,0)');
		}
	});

	$doc.ready(function(){
		container.css({
			'transition': '.5s ease all',
			'webkitTransition': '.5s ease all'
		});
		modal.css({
			'transition': '.5s ease all',
			'webkitTransition': '.5s ease all'
		});
		tapDetect(container,modal);
	});

}(this));