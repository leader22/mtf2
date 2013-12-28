<?php require_once('header.php'); ?>
<?php require_once('search.php'); ?>
<?php if($_SESSION['isPC'] || isPC()): ?>
<article class="contents inner">
	<h2 class="title-box wf page-h2">Music Tweets Finder 2.0 has come!</h2>
	<section class="title-box">
		<h3 class="page-h3">音楽に関するツイートだけを検索できるサービス</h3>
		<p>
		本サービスは、#nowplaying、YouTubeのURLといった音楽に関するツイートをまとめて検索できるサービスです。<br>
		どういったツイートを検索するかなどの詳細については、<a href="/about.php">About</a>ページをご覧ください。
		</p>

		<h3 class="page-h3">2.0って？</h3>
		<p>
		本サービスは、以前作ったMusic Tweets Finderを、Twitter APIのバージョンアップに対応すべく機能拡張・アップデートしたものです。<br>
		※本サービスを利用するためには、Twitterアカウントでのログインが必要です。
		</p>

		<?php if($_SESSION['isVerifiedUser']): ?>
		<h3 class="page-h3">簡単な使い方</h3>
		<p>
		画面上部の検索フォームにキーワードを入力して、音楽に関するツイートを検索！<br>
		検索対象を日本語のツイートだけにしたい場合は、日本国旗のところにチェックを入れてください。
		</p>
		<?php else: ?>
		<h3 class="page-h3">本サービスを利用するには</h3>
		<p>
		本サービスの利用には、Twitterアカウントによる<a href="includes/twitteroauth/redirect.php">ログイン</a>が必要です。<br>
		その他、何か問題や気づいた点があれば、<a href="https://twitter.com/leader22" target="_blank" title="りぃ (leader22)">中の人</a>までご連絡いただけると幸いです。
		</p>
		<?php endif; ?>
	</section>
</article>
<?php else: ?>
<article class="contents inner">
	<section class="title-box">
		<h3 class="page-h3">音楽に関するツイートをまとめて検索!</h3>
		<p>
		本サービスは、#nowplaying、YouTubeのURLといった音楽に関するツイートをまとめて検索できるサービスです。<br>
		どういったツイートを検索するかなどの詳細については、右上のメニューボタンから<a href="/about.php">About</a>ページをご覧ください。
		</p>

		<?php if($_SESSION['isVerifiedUser']): ?>
		<h3 class="page-h3">使い方</h3>
		<p>
		検索フォームにキーワードを入力して、音楽に関するツイートを検索！<br>
		検索対象を日本語のツイートだけにしたい場合は、チェックボックスにチェックを入れてください。
		</p>
		<?php else: ?>
		<h3 class="page-h3">本サービスを利用するには</h3>
		<p>
		本サービスの利用には、Twitterアカウントによる<a href="includes/twitteroauth/redirect.php">ログイン</a>が必要です。<br>
		<a href="includes/twitteroauth/redirect.php" class="btn btn-l tap-btn">ログイン</a>
		その他、何か問題や気づいた点があれば、<a href="https://twitter.com/leader22" target="_blank" title="りぃ (leader22)">中の人</a>までご連絡いただけると幸いです。
		</p>
		<?php endif; ?>
	</section>
</article>
<?php endif; ?>
<?php require_once('footer.php'); ?>