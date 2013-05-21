<?php
	template::display('header');
?>

<?php if (template::getVar('NEWS_ACTIVE')): ?>
	<div class="fLeft" style="width: 74%;">
		<h1 class="title">News</h1>

		<div id="news">
			<?php
				if (isset(template::$blocks['news'])) {
					foreach (template::$blocks['news'] as $entry):
			?>
				
				<div class="entry">
					<div class="title">
						<h2><a href="./viewtopic.php?id=<?=$entry['TOPIC_ID']; ?>"><?=$entry['TITLE']; ?></a></h2>
						<span class="date"><?=$entry['DATE']; ?></span> &minus;
						<span class="category"><a href="./viewforum.php?id=<?=$entry['FORUM_ID']; ?>"><?=$entry['FORUM_TITLE']; ?></a></span>
					</div>

					<div class="content">
						<?=$entry['TEXT']; ?>
					</div>

					<div class="meta">
						<a href="./viewtopic.php?id=<?=$entry['TOPIC_ID']; ?>"><b><?=$entry['COMMENTS_NUM']; ?></b> Kommentar<? if ($entry['COMMENTS_NUM'] == 1): ?><?php else: ?>e<?php endif; ?></a>
					</div>
				</div>

			<?php
					endforeach;
				}
			?>
		</div>

		<?php if (template::getVar('PAGES_NUM') > 1): ?>
			Seite <?=template::getVar('PAGENR'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?>
		<?php endif; ?>
	</div>

	<div class="fRight" style="width: 24%;">
		<?php
			template::display('feed_sidebar');
		?>
	</div>

	<div class="clear"></div>
<?php else: ?>
	<?php
		template::display('feed');
	?>
<?php endif; ?>

<?php
	template::display('footer');
?>