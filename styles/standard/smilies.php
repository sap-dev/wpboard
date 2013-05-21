<?php
	if (isset(template::$blocks['smilies'])):
		foreach(template::$blocks['smilies'] as $smilies):
?>
		<a onclick="return Smilie('<?=$smilies['EMOTION']; ?>');" href="#">
			<img style="margin:5px;" src="images/smilies/<?=$smilies['IMAGE']; ?>" border="0" />
		</a>
<?php
		endforeach;
	endif;
?>