<?php if($_SESSION['isPC'] || isPC()): ?>
<div class="search-box">
	<div class="inner">
		<?php if($_SESSION['isVerifiedUser']): ?>
		<form class="search-form" action="show_result.php" method="get">
			<input name="q" type="text" value="<?php echo(escapeString($_GET['q'])); ?>" placeholder="探したい曲名・アーティスト名などキーワードを入力！" size="75">
			<label><input type="checkbox" name="l" <?php echo ($_GET['l']) ? 'checked="checked"' : ''; ?> /> <img src="img/jp.png" alt="jp" width="33" height="25" style="vertical-align: -7px;" /></label>
			<input type="submit" value="Find it!" class="wf">
		</form>
		<?php endif; ?>
	</div>
</div>
<?php else: ?>
<?php if($_SESSION['isVerifiedUser']): ?>
<div class="search-box">
	<form class="inner search-form" action="show_result.php" method="get">
		<label onclick="javascript:void 0"><input type="checkbox" name="l" <?php echo(($_GET['l']) ? 'checked="checked"' : ''); ?> /><span>: 日本語ツイートのみを検索する</span></label>
		<div class="box">
			<input name="q" type="text" value="<?php echo(escapeString($_GET['q'])); ?>" class="inpt-reset flex" />
			<input class="inpt-reset tap-btn" type="submit" value="Find it!" class="wf"/>
		</div>
	</form>
</div>
<?php endif; ?>
<?php endif; ?>