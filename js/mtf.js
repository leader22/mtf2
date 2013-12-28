(function(win){
// Var statement block. ///////////////////////////////////////////////////////////////////////////////

	var win = win,
	doc = win.document,
	$win = $(win),
	$doc = $(doc),
	container = $('#container'),
	leftBox = $('#left_box'),
	userBox = $('#user_box'),
	tweetLists = $('#tweet_lists'),
	tweetListsTemplate = $('#tweetListsTemplate'),
	moreBtn = $('#more_btn'),
	selectedUserName = $('#selected_user_name'),
	selectedUserThumbnail = $('#selected_user_thumbnail'),
	userDetailBox = $('#user_detail_box'),
	userDetailFrame = $('#user_detail_frame'),
	userDetailScroller = $('#user_detail_scroller').hide(),
	userDetailFrameWrapper = $('#user_detail_frame_wrapper');

// Function statement block. ///////////////////////////////////////////////////////////////////////////////

	var adjustUserBoxHeight = function() {
		var w = $win.height(),
		d = container.height();

		if(w > d){
			var maxHeight = leftBox.height() - 21;
			userBox.height(maxHeight);
			userDetailFrame.height(maxHeight);
			userDetailFrameWrapper.height(maxHeight);
		}else{
			userBox.height(w - 275);
			userDetailFrame.height(w - 295);
			userDetailFrameWrapper.height(w - 295);
		}
	};

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
			timeout: 5000,
			dataType: "json",
			cache: true,
			data: {
				q: parseUrlParam()['q'],
				l: parseUrlParam()['l'],
				m: minus1(tweetLists.children('li:last').data('id_str'))
			},
			success: function(d) {
				if(d){
					parseTweetTemplate(d);
					makeConvertLinks(tweetLists.find('.user-text-box').not('.cnvrtd').addClass('cnvrtd'));
				}else{
					moreBtn.addClass('err').html('これ以上古いツイートはありません。');
					return;
				}
				
				moreBtn.on('click', getMoreTweets);
				moreBtn.html(btnMessage);
			}
		});
	};

	var parseTweetTemplate = function(d){
		var source = $('#tweetListsTemplate').html(),
		template = Handlebars.compile(source);
		tweetLists.append(template(d));
	};

	var setLoadingImg = function(option){
		if(option.type === 'tweet'){
			option.target.html('<img src="/img/loading_mini.gif" height="15" widht="15" alt="読み込み中" />');
		}else{
			option.target.css('background', '').css('background', '#fff url(/img/loading.gif) no-repeat center center;');
		}
	};

	var parseUserTemplate = function(d){
		var source = $('#userInfoTemplate').html(),
		template = Handlebars.compile(source);
		userDetailBox.append(template(d));
	};

	var getUserInfo = function(newScreenName){
		userDetailBox.empty();
		userDetailScroller.hide();
		setLoadingImg({type: 'user', target: userBox});
		
		$.ajax({
			type: "GET",
			url: "includes/ajaxApiLoader.php",
			timeout: 5000,
			dataType: "json",
			cache: true,
			data: {
				u:newScreenName
			},
			success: function(d) {
				if(d){
					parseUserTemplate(d);
					adjustUserBoxHeight();
					makeConvertLinks(userDetailBox.find('.user-detail-text'));
				}else{
					return;
				}
				
				userBox.css('background','#fff');
				if(userDetailBox.height() > userDetailFrameWrapper.height()){
					userDetailScroller.show();
				}
				
			}
			
		});

	};
	
	var selectUser = function(){
		var $this = $(this),
		newScreenName, newThumbnail,
		oldScreenName = selectedUserName.data('screen_name');

		if($this.data('screen_name')){
			if($this.data('screen_name') === oldScreenName){return;}
			newScreenName = $this.data('screen_name');
			newThumbnail = $this.find('.user-thumbnail').clone().attr({width:48, height:48});
			selectedUserThumbnail.html(newThumbnail);
		}else{
			newScreenName = oldScreenName;
		}
		
		selectedUserName.html('<a href="https://twitter.com/intent/user?screen_name=' + newScreenName + '">' + newScreenName + '</a>');
		selectedUserName.data('screen_name', newScreenName);

		tweetLists.find('.tweet-list').removeClass('selected');
		tweetLists.find("[data-screen_name='" + newScreenName + "']").addClass('selected');
		
		getUserInfo(newScreenName);
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

// Execute, handle event block. ///////////////////////////////////////////////////////////////////////////////

	$win.on('scroll resize', adjustUserBoxHeight);
	moreBtn.on('click', getMoreTweets);
	tweetLists.delegate('.tweet-list', 'click', selectUser);

	$doc.ready(function(){
		adjustUserBoxHeight();
		selectUser();
		makeConvertLinks(tweetLists.find('.user-text-box').addClass('cnvrtd'));
	});
}(this));